<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrganizationDocument;
use App\Models\DocumentApproval;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DocumentManagementController extends Controller
{
    /**
     * Display a listing of documents for an organization.
     */
    public function index(Organization $organization)
    {
        $this->authorize('viewAny', [OrganizationDocument::class, $organization]);

        $documents = OrganizationDocument::with(['creator', 'approvals'])
            ->forOrganization($organization->id)
            ->latest()
            ->paginate(10);

        return view('super-admin.documents.index', compact('organization', 'documents'));
    }

    /**
     * Show the form for creating a new document.
     */
    public function create(Organization $organization)
    {
        $this->authorize('create', [OrganizationDocument::class, $organization]);

        $documentTypes = OrganizationDocument::getDocumentTypes();
        $categories = OrganizationDocument::getCategories();
        $statuses = OrganizationDocument::getStatuses();

        return view('super-admin.documents.create', compact(
            'organization', 'documentTypes', 'categories', 'statuses'
        ));
    }

    /**
     * Store a newly created document in storage.
     */
    public function store(Request $request, Organization $organization)
    {
        $this->authorize('create', [OrganizationDocument::class, $organization]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document_type' => 'required|string|in:' . implode(',', array_keys(OrganizationDocument::getDocumentTypes())),
            'category' => 'required|string|in:' . implode(',', array_keys(OrganizationDocument::getCategories())),
            'status' => 'required|string|in:' . implode(',', array_keys(OrganizationDocument::getStatuses())),
            'is_template' => 'boolean',
            'content' => 'nullable|array',
            'file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();
        $data['organization_id'] = $organization->id;
        $data['created_by'] = Auth::id();
        $data['version'] = '1.0';

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('organization_documents/' . $organization->id, 'public');
            $data['file_path'] = $path;
            $data['file_size'] = $file->getSize();
            $data['mime_type'] = $file->getMimeType();
        }

        $document = OrganizationDocument::create($data);

        // Create approval workflow if document requires approval
        if ($data['status'] === 'pending_approval') {
            $this->createApprovalWorkflow($document, $organization);
        }

        return redirect()->route('super-admin.organizations.documents.index', $organization)
            ->with('success', 'Document created successfully.');
    }

    /**
     * Display the specified document.
     */
    public function show(Organization $organization, OrganizationDocument $document)
    {
        $this->authorize('view', [$document, $organization]);

        $document->load(['creator', 'approver', 'approvals.approver']);

        return view('super-admin.documents.show', compact('organization', 'document'));
    }

    /**
     * Show the form for editing the specified document.
     */
    public function edit(Organization $organization, OrganizationDocument $document)
    {
        $this->authorize('update', [$document, $organization]);

        $documentTypes = OrganizationDocument::getDocumentTypes();
        $categories = OrganizationDocument::getCategories();
        $statuses = OrganizationDocument::getStatuses();

        return view('super-admin.documents.edit', compact(
            'organization', 'document', 'documentTypes', 'categories', 'statuses'
        ));
    }

    /**
     * Update the specified document in storage.
     */
    public function update(Request $request, Organization $organization, OrganizationDocument $document)
    {
        $this->authorize('update', [$document, $organization]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document_type' => 'required|string|in:' . implode(',', array_keys(OrganizationDocument::getDocumentTypes())),
            'category' => 'required|string|in:' . implode(',', array_keys(OrganizationDocument::getCategories())),
            'status' => 'required|string|in:' . implode(',', array_keys(OrganizationDocument::getStatuses())),
            'is_template' => 'boolean',
            'content' => 'nullable|array',
            'file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($document->file_path) {
                Storage::disk('public')->delete($document->file_path);
            }

            $file = $request->file('file');
            $path = $file->store('organization_documents/' . $organization->id, 'public');
            $data['file_path'] = $path;
            $data['file_size'] = $file->getSize();
            $data['mime_type'] = $file->getMimeType();
            $data['version'] = $this->incrementVersion($document->version);
        }

        $document->update($data);

        return redirect()->route('super-admin.organizations.documents.index', $organization)
            ->with('success', 'Document updated successfully.');
    }

    /**
     * Remove the specified document from storage.
     */
    public function destroy(Organization $organization, OrganizationDocument $document)
    {
        $this->authorize('delete', [$document, $organization]);

        // Delete file if exists
        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->route('super-admin.organizations.documents.index', $organization)
            ->with('success', 'Document deleted successfully.');
    }

    /**
     * Approve a document.
     */
    public function approve(Request $request, Organization $organization, OrganizationDocument $document)
    {
        $this->authorize('approve', [$document, $organization]);

        $validator = Validator::make($request->all(), [
            'comments' => 'nullable|string',
            'approval_level' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();
        $data['organization_document_id'] = $document->id;
        $data['approver_id'] = Auth::id();
        $data['status'] = 'approved';
        $data['approved_at'] = now();

        DocumentApproval::create($data);

        // Check if all required approvals are complete
        if ($this->areAllApprovalsComplete($document)) {
            $document->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);
        }

        return redirect()->route('super-admin.organizations.documents.show', [$organization, $document])
            ->with('success', 'Document approved successfully.');
    }

    /**
     * Reject a document.
     */
    public function reject(Request $request, Organization $organization, OrganizationDocument $document)
    {
        $this->authorize('reject', [$document, $organization]);

        $validator = Validator::make($request->all(), [
            'comments' => 'required|string',
            'approval_level' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();
        $data['organization_document_id'] = $document->id;
        $data['approver_id'] = Auth::id();
        $data['status'] = 'rejected';
        $data['approved_at'] = now();

        DocumentApproval::create($data);

        $document->update([
            'status' => 'rejected',
        ]);

        return redirect()->route('super-admin.organizations.documents.show', [$organization, $document])
            ->with('success', 'Document rejected successfully.');
    }

    /**
     * Download document file.
     */
    public function download(Organization $organization, OrganizationDocument $document)
    {
        $this->authorize('view', [$document, $organization]);

        if (!$document->file_path) {
            return redirect()->back()->with('error', 'No file available for download.');
        }

        return Storage::disk('public')->download($document->file_path, $document->name);
    }

    /**
     * Create approval workflow for a document.
     */
    protected function createApprovalWorkflow(OrganizationDocument $document, Organization $organization)
    {
        // Get users with approval permissions in the organization
        $approvers = User::whereHas('roles', function ($query) {
            $query->where('name', 'like', '%approver%');
        })->where('organization_id', $organization->id)->get();

        $approvalLevel = 1;
        foreach ($approvers as $approver) {
            DocumentApproval::create([
                'organization_document_id' => $document->id,
                'approver_id' => $approver->id,
                'status' => 'pending',
                'required_approval_level' => $approvalLevel,
                'approval_sequence' => $approvalLevel,
                'is_final_approval' => $approvalLevel === $approvers->count(),
            ]);
            $approvalLevel++;
        }
    }

    /**
     * Check if all required approvals are complete.
     */
    protected function areAllApprovalsComplete(OrganizationDocument $document): bool
    {
        $pendingApprovals = DocumentApproval::where('organization_document_id', $document->id)
            ->where('status', 'pending')
            ->count();

        return $pendingApprovals === 0;
    }

    /**
     * Increment document version.
     */
    protected function incrementVersion(string $version): string
    {
        $parts = explode('.', $version);
        $parts[count($parts) - 1]++;
        return implode('.', $parts);
    }
}
