<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\UserDocument;
use App\Models\Document;
use Illuminate\Validation\Rule;

class DocumentUploadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.status');
    }

    /**
     * Show document upload form for users
     */
    public function index()
    {
        $user = auth()->user();

        // Get user's uploaded documents
        $userDocuments = UserDocument::where('user_id', $user->id)
            ->whereNotNull('file_path') // Only uploaded documents
            ->with('document')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get required document types for user's role
        $requiredDocuments = $this->getRequiredDocumentTypes($user);

        return view('documents.upload.index', compact('userDocuments', 'requiredDocuments'));
    }

    /**
     * Show upload form for specific document type
     */
    public function create($documentType)
    {
        $user = auth()->user();

        // Validate document type is allowed for user
        $allowedTypes = $this->getRequiredDocumentTypes($user);
        if (!isset($allowedTypes[$documentType])) {
            abort(403, 'Document type not allowed for your account type.');
        }

        $documentInfo = $allowedTypes[$documentType];

        return view('documents.upload.create', compact('documentType', 'documentInfo'));
    }

    /**
     * Store uploaded document
     */
    public function store(Request $request, $documentType)
    {
        $user = auth()->user();

        // Validate document type
        $allowedTypes = $this->getRequiredDocumentTypes($user);
        if (!isset($allowedTypes[$documentType])) {
            abort(403, 'Document type not allowed for your account type.');
        }

        $documentInfo = $allowedTypes[$documentType];

        // Validate file upload
        $request->validate([
            'document_file' => [
                'required',
                'file',
                'max:' . ($documentInfo['max_size'] ?? 5120), // 5MB default
                Rule::when(isset($documentInfo['allowed_mimes']), function ($rule) use ($documentInfo) {
                    return $rule->mimes($documentInfo['allowed_mimes']);
                }),
            ],
            'notes' => 'nullable|string|max:500',
        ]);

        $file = $request->file('document_file');

        // Generate unique filename
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $filename = $documentType . '_' . $user->id . '_' . time() . '.' . $extension;

        // Store file
        $path = $file->storeAs('user-documents', $filename, 'public');

        // Create or update user document record
        $userDocument = UserDocument::updateOrCreate(
            [
                'user_id' => $user->id,
                'document_type' => $documentType,
            ],
            [
                'file_path' => $path,
                'file_name' => $originalName,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'status' => 'pending_review',
                'notes' => $request->notes,
                'uploaded_at' => now(),
            ]
        );

        return redirect()->route('documents.upload.index')
            ->with('success', ucfirst($documentInfo['name']) . ' uploaded successfully and is pending review.');
    }

    /**
     * Download user's uploaded document
     */
    public function download($documentId)
    {
        $user = auth()->user();

        $userDocument = UserDocument::where('id', $documentId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if (!$userDocument->file_path || !Storage::disk('public')->exists($userDocument->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('public')->download($userDocument->file_path, $userDocument->file_name);
    }

    /**
     * Delete uploaded document
     */
    public function destroy($documentId)
    {
        $user = auth()->user();

        $userDocument = UserDocument::where('id', $documentId)
            ->where('user_id', $user->id)
            ->where('status', '!=', 'approved') // Don't allow deletion of approved documents
            ->firstOrFail();

        // Delete file from storage
        if ($userDocument->file_path && Storage::disk('public')->exists($userDocument->file_path)) {
            Storage::disk('public')->delete($userDocument->file_path);
        }

        // Delete record
        $userDocument->delete();

        return redirect()->route('documents.upload.index')
            ->with('success', 'Document deleted successfully.');
    }

    /**
     * Get required document types for user role
     */
    private function getRequiredDocumentTypes($user)
    {
        $documents = [];

        if ($user->isPlayer()) {
            $documents = [
                'national_id' => [
                    'name' => 'National ID/Passport',
                    'description' => 'Copy of your national ID or passport',
                    'required' => true,
                    'allowed_mimes' => ['pdf', 'jpg', 'jpeg', 'png'],
                    'max_size' => 5120, // 5MB
                ],
                'birth_certificate' => [
                    'name' => 'Birth Certificate',
                    'description' => 'Official birth certificate',
                    'required' => true,
                    'allowed_mimes' => ['pdf', 'jpg', 'jpeg', 'png'],
                    'max_size' => 5120,
                ],
                'medical_clearance' => [
                    'name' => 'Medical Clearance',
                    'description' => 'Medical certificate confirming fitness to play',
                    'required' => true,
                    'allowed_mimes' => ['pdf', 'jpg', 'jpeg', 'png'],
                    'max_size' => 5120,
                ],
                'parent_consent' => [
                    'name' => 'Parent/Guardian Consent',
                    'description' => 'Signed consent form from parent or guardian',
                    'required' => true,
                    'allowed_mimes' => ['pdf', 'jpg', 'jpeg', 'png'],
                    'max_size' => 5120,
                ],
                'school_letter' => [
                    'name' => 'School Letter',
                    'description' => 'Letter from school confirming enrollment',
                    'required' => false,
                    'allowed_mimes' => ['pdf', 'jpg', 'jpeg', 'png'],
                    'max_size' => 5120,
                ],
            ];
        } elseif ($user->isPartner()) {
            $documents = [
                'business_registration' => [
                    'name' => 'Business Registration',
                    'description' => 'Certificate of business registration or incorporation',
                    'required' => true,
                    'allowed_mimes' => ['pdf', 'jpg', 'jpeg', 'png'],
                    'max_size' => 10240, // 10MB
                ],
                'tax_clearance' => [
                    'name' => 'Tax Clearance Certificate',
                    'description' => 'Valid tax clearance certificate',
                    'required' => true,
                    'allowed_mimes' => ['pdf', 'jpg', 'jpeg', 'png'],
                    'max_size' => 5120,
                ],
                'directors_id' => [
                    'name' => 'Director\'s ID',
                    'description' => 'National ID of company director(s)',
                    'required' => true,
                    'allowed_mimes' => ['pdf', 'jpg', 'jpeg', 'png'],
                    'max_size' => 5120,
                ],
                'company_profile' => [
                    'name' => 'Company Profile',
                    'description' => 'Detailed company profile and history',
                    'required' => false,
                    'allowed_mimes' => ['pdf', 'doc', 'docx'],
                    'max_size' => 10240,
                ],
                'partnership_agreement' => [
                    'name' => 'Partnership Agreement',
                    'description' => 'Signed partnership agreement with Vipers Academy',
                    'required' => false,
                    'allowed_mimes' => ['pdf'],
                    'max_size' => 10240,
                ],
            ];
        }

        return $documents;
    }
}
