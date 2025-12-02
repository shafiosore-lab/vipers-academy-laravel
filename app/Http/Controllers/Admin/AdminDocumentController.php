<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Models\Document;
use App\Models\User;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminDocumentController extends Controller
{

    /**
     * Display a listing of documents.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Document::with(['userDocuments' => function ($query) {
            $query->with('user');
        }, 'userDocuments.user']);

        // Apply filters
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'mandatory') {
                $query->where('is_mandatory', true);
            } elseif ($request->status === 'expiring') {
                $query->where('is_active', true)
                      ->whereNotNull('expiry_days');
            }
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('file_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('language')) {
            $query->where('language', $request->language);
        }

        if ($request->filled('requires_signature')) {
            $query->where('requires_signature', $request->boolean('requires_signature'));
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        $validSortFields = [
            'title', 'category', 'created_at', 'updated_at',
            'file_size', 'version', 'is_active'
        ];

        if (in_array($sortBy, $validSortFields)) {
            $query->orderBy($sortBy, $sortDirection);
        }

        $baseQuery = $query->get();
        $documents = $query->paginate(15);

        // Create paginated collections for each tab
        $activeDocuments = (clone $query)->where('is_active', true)->paginate(15, ['*'], 'page', 1);
        $mandatoryDocuments = (clone $query)->where('is_mandatory', true)->paginate(15, ['*'], 'page', 1);
        $expiringDocuments = (clone $query)->where('is_active', true)
                                          ->whereNotNull('expiry_days')
                                          ->paginate(15, ['*'], 'page', 1);

        // Get filter data
        $categories = Document::select('category')
            ->distinct()
            ->where('category', '!=', '')
            ->get()
            ->pluck('category')
            ->map(function ($category) {
                return [
                    'value' => $category,
                    'label' => $this->formatCategoryLabel($category)
                ];
            });

        $statistics = $this->getDocumentStatistics();

        return view('admin.documents.index', compact(
            'documents',
            'activeDocuments',
            'mandatoryDocuments',
            'expiringDocuments',
            'categories',
            'statistics',
            'request'
        ));
    }

    /**
     * Show the form for creating a new document.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = $this->getAvailableCategories();
        $targetRoles = $this->getAvailableTargetRoles();

        return view('admin.documents.create', compact('categories', 'targetRoles'));
    }

    /**
     * Store a newly created document in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'subcategory' => 'nullable|string|max:100',
            'file' => 'required|file|mimes:pdf,doc,docx,txt,rtf|max:20480', // 20MB max
            'language' => 'required|string|size:2',
            'version' => 'nullable|string|max:20',
            'is_mandatory' => 'boolean',
            'target_roles' => 'required|array|min:1',
            'target_roles.*' => 'string',
            'requires_signature' => 'boolean',
            'expiry_days' => 'nullable|integer|min:1|max:3650',
            'is_active' => 'boolean',
            'metadata' => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            // Generate unique document ID
            $validated['document_id'] = $this->generateUniqueDocumentId($validated['category']);

            // Handle file upload
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filePath = $this->uploadDocumentFile($file, $validated['document_id']);
                $validated['file_path'] = $filePath;
                $validated['file_name'] = $file->getClientOriginalName();
                $validated['mime_type'] = $file->getMimeType();
                $validated['file_size'] = $file->getSize();
            }

            // Convert array fields to JSON
            $validated['target_roles'] = array_unique($validated['target_roles']);
            if (isset($validated['metadata'])) {
                $validated['metadata'] = json_encode($validated['metadata']);
            }

            // Set published_at if active
            if ($validated['is_active']) {
                $validated['published_at'] = now();
            }

            $document = Document::create($validated);

            // Log the creation
            $this->logDocumentAction($document, 'created', null, $validated);

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'document' => $document,
                    'message' => 'Document created successfully.'
                ], 201);
            }

            return redirect()->route('admin.documents.edit', $document)
                           ->with('success', 'Document created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating document: ' . $e->getMessage());

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating document.'
                ], 500);
            }

            return back()->withInput()
                        ->withErrors(['error' => 'Failed to create document. Please try again.']);
        }
    }

    /**
     * Display the specified document.
     *
     * @param Document $document
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Document $document)
    {
        $document->load(['userDocuments' => function ($query) {
            $query->with('user');
        }]);

        if ($request->wantsJson()) {
            return response()->json(['document' => $document]);
        }

        return view('admin.documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified document.
     *
     * @param Document $document
     * @return \Illuminate\View\View
     */
    public function edit(Document $document)
    {
        $categories = $this->getAvailableCategories();
        $targetRoles = $this->getAvailableTargetRoles();
        $document->load(['userDocuments' => function ($query) {
            $query->with('user');
        }]);

        return view('admin.documents.edit', compact('document', 'categories', 'targetRoles'));
    }

    /**
     * Update the specified document in storage.
     *
     * @param Request $request
     * @param Document $document
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'subcategory' => 'nullable|string|max:100',
            'file' => 'nullable|file|mimes:pdf,doc,docx,txt,rtf|max:20480',
            'language' => 'required|string|size:2',
            'version' => 'nullable|string|max:20',
            'is_mandatory' => 'boolean',
            'target_roles' => 'required|array|min:1',
            'target_roles.*' => 'string',
            'requires_signature' => 'boolean',
            'expiry_days' => 'nullable|integer|min:1|max:3650',
            'is_active' => 'boolean',
            'metadata' => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            $oldValues = $document->toArray();

            // Handle file upload if new file provided
            if ($request->hasFile('file')) {
                // Delete old file
                if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                    Storage::disk('public')->delete($document->file_path);
                }

                $file = $request->file('file');
                $filePath = $this->uploadDocumentFile($file, $document->document_id);
                $validated['file_path'] = $filePath;
                $validated['file_name'] = $file->getClientOriginalName();
                $validated['mime_type'] = $file->getMimeType();
                $validated['file_size'] = $file->getSize();
            }

            // Convert array fields to JSON
            $validated['target_roles'] = array_unique($validated['target_roles']);
            if (isset($validated['metadata'])) {
                $validated['metadata'] = json_encode($validated['metadata']);
            }

            // Update published_at if becoming active
            if ($validated['is_active'] && !$document->is_active) {
                $validated['published_at'] = now();
            }

            $document->update($validated);

            // Log the update
            $this->logDocumentAction($document, 'updated', $oldValues, $validated);

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'document' => $document,
                    'message' => 'Document updated successfully.'
                ]);
            }

            return redirect()->route('admin.documents.edit', $document)
                           ->with('success', 'Document updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating document: ' . $e->getMessage());

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating document.'
                ], 500);
            }

            return back()->withErrors(['error' => 'Failed to update document. Please try again.']);
        }
    }

    /**
     * Download the document file.
     *
     * @param Document $document
     * @return BinaryFileResponse
     */
    public function download(Document $document): BinaryFileResponse
    {
        if (!$document->fileExists()) {
            abort(404, 'File not found.');
        }

        return response()->download(
            Storage::disk('public')->path($document->file_path),
            $document->file_name
        );
    }

    /**
     * Preview the document (if viewable).
     *
     * @param Document $document
     * @return BinaryFileResponse|StreamedResponse
     */
    public function preview(Document $document)
    {
        if (!$document->fileExists()) {
            abort(404, 'File not found.');
        }

        $filePath = Storage::disk('public')->path($document->file_path);

        if ($this->isViewableFile($document->mime_type)) {
            return response()->file($filePath);
        } else {
            return response()->download($filePath, $document->file_name);
        }
    }

    /**
     * Remove the specified document from storage.
     *
     * @param Document $document
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function destroy(Document $document)
    {
        DB::beginTransaction();

        try {
            $oldValues = $document->toArray();

            // Delete associated file
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            // Delete user document relations
            $document->userDocuments()->delete();

            // Delete the document
            $document->delete();

            // Log the deletion
            $this->logDocumentAction($document, 'deleted', $oldValues, null);

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Document deleted successfully.'
                ]);
            }

            return redirect()->route('admin.documents.index')
                           ->with('success', 'Document deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting document: ' . $e->getMessage());

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting document.'
                ], 500);
            }

            return back()->withErrors(['error' => 'Failed to delete document. Please try again.']);
        }
    }

    /**
     * Bulk operations on documents.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulk(Request $request): JsonResponse
    {
        $request->validate([
            'action' => 'required|string|in:delete,activate,deactivate,categorize,duplicate',
            'document_ids' => 'required|array',
            'document_ids.*' => 'integer|exists:documents,id'
        ]);

        $documentIds = $request->document_ids;
        $action = $request->action;

        DB::beginTransaction();

        try {
            switch ($action) {
                case 'delete':
                    $documents = Document::whereIn('id', $documentIds)->get();

                    foreach ($documents as $document) {
                        // Log deletion for each
                        $this->logDocumentAction($document, 'bulk_deleted', $document->toArray(), null);

                        // Delete files
                        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                            Storage::disk('public')->delete($document->file_path);
                        }
                    }

                    $count = Document::whereIn('id', $documentIds)->delete();
                    break;

                case 'activate':
                    $count = Document::whereIn('id', $documentIds)
                              ->where('is_active', false)
                              ->update(['is_active' => true, 'published_at' => now()]);
                    break;

                case 'deactivate':
                    $count = Document::whereIn('id', $documentIds)
                              ->where('is_active', true)
                              ->update(['is_active' => false]);
                    break;

                case 'categorize':
                    $request->validate(['category' => 'required|string|max:100']);
                    $count = Document::whereIn('id', $documentIds)
                              ->update(['category' => $request->category]);
                    break;

                case 'duplicate':
                    foreach ($documentIds as $id) {
                        $document = Document::find($id);
                        if ($document) {
                            $newDocument = $document->replicate();
                            $newDocument->document_id = $this->generateUniqueDocumentId($document->category);
                            $newDocument->title = $document->title . ' (Copy)';
                            $newDocument->save();

                            $this->logDocumentAction($newDocument, 'duplicated', null, $newDocument->toArray());
                        }
                    }
                    $count = count($documentIds);
                    break;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Bulk {$action} completed successfully. ({$count} documents affected)"
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk document operation error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Bulk operation failed.'
            ], 500);
        }
    }

    /**
     * Get document statistics for dashboard.
     *
     * @return JsonResponse
     */
    public function statistics(): JsonResponse
    {
        $stats = $this->getDocumentStatistics();

        return response()->json($stats);
    }

    // ========================================
    // HELPER METHODS
    // ========================================

    /**
     * Get available categories.
     *
     * @return array
     */
    private function getAvailableCategories(): array
    {
        return [
            'codes_of_conduct' => 'Codes of Conduct',
            'safety_protection' => 'Safety & Protection',
            'academy_policies' => 'Academy Policies',
            'contracts_agreements' => 'Contracts & Agreements',
            'academy_information' => 'Academy Information',
            'administrative' => 'Administrative',
            'training' => 'Training Materials',
            'medical' => 'Medical Forms',
            'financial' => 'Financial Documents',
            'legal' => 'Legal Documents',
        ];
    }

    /**
     * Get available target roles.
     *
     * @return array
     */
    private function getAvailableTargetRoles(): array
    {
        return [
            'player' => 'Players',
            'parent' => 'Parents/Guardians',
            'coach' => 'Coaches',
            'staff' => 'Staff',
            'admin' => 'Administrators',
        ];
    }

    /**
     * Generate unique document ID.
     *
     * @param string $category
     * @return string
     */
    private function generateUniqueDocumentId(string $category): string
    {
        do {
            $id = $category . '_' . strtoupper(substr(md5(uniqid(microtime())), 0, 8));
        } while (Document::where('document_id', $id)->exists());

        return $id;
    }

    /**
     * Upload document file.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $documentId
     * @return string
     */
    private function uploadDocumentFile($file, string $documentId): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = $documentId . '_' . time() . '.' . $extension;
        $path = $file->storeAs('documents', $filename, 'public');

        return $path;
    }

    /**
     * Get document statistics.
     *
     * @return array
     */
    private function getDocumentStatistics(): array
    {
        return [
            'total_documents' => Document::count(),
            'active_documents' => Document::active()->count(),
            'mandatory_documents' => Document::where('is_mandatory', true)->active()->count(),
            'documents_by_category' => Document::select('category', DB::raw('count(*) as count'))
                ->groupBy('category')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->category => $item->count];
                }),
            'total_views' => Document::sum(DB::raw('COALESCE((
                SELECT SUM(download_count)
                FROM user_documents ud
                WHERE ud.document_id = documents.id
            ), 0)')),
            'total_signatures' => Document::sum(DB::raw('COALESCE((
                SELECT COUNT(*)
                FROM user_documents ud
                WHERE ud.document_id = documents.id AND ud.status = "signed"
            ), 0)')),
            'expiration_summary' => [
                'expiring_soon' => Document::whereHas('userDocuments', function ($query) {
                    $query->expiringSoon();
                })->count(),
                'expired_documents' => Document::whereHas('userDocuments', function ($query) {
                    $query->expired();
                })->count(),
            ]
        ];
    }

    /**
     * Log document action.
     *
     * @param Document $document
     * @param string $action
     * @param array|null $oldValues
     * @param array|null $newValues
     * @return void
     */
    private function logDocumentAction(Document $document, string $action, ?array $oldValues, ?array $newValues): void
    {
        // Basic logging - can be extended to store in dedicated audit table
        Log::info('Document Action', [
            'action' => $action,
            'document_id' => $document->id,
            'document_title' => $document->title,
            'user_id' => Auth::id(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'timestamp' => now(),
        ]);
    }

    /**
     * Check if file is viewable in browser.
     *
     * @param string $mimeType
     * @return bool
     */
    private function isViewableFile(string $mimeType): bool
    {
        $viewableTypes = [
            'application/pdf',
            'text/plain',
            'text/html',
        ];

        return in_array($mimeType, $viewableTypes);
    }

    /**
     * Format category label.
     *
     * @param string $category
     * @return string
     */
    private function formatCategoryLabel(string $category): string
    {
        return match($category) {
            'codes_of_conduct' => 'Codes of Conduct',
            'safety_protection' => 'Safety & Protection',
            'academy_policies' => 'Academy Policies',
            'contracts_agreements' => 'Contracts & Agreements',
            'academy_information' => 'Academy Information',
            'administrative' => 'Administrative',
            default => ucwords(str_replace(['_', '-'], ' ', $category))
        };
    }
}
