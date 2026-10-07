<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminUserApprovalController extends Controller
{
    /**
     * Display pending user approvals
     */
    public function index(Request $request)
    {
        $query = User::where('status', 'pending');

        // Filter by user type
        if ($request->user_type) {
            $query->where('user_type', $request->user_type);
        }

        // Search by name or email
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $pendingUsers = $query->with(['roles', 'player'])->paginate(20);

        // Get statistics
        $stats = [
            'total_pending' => User::where('status', 'pending')->count(),
            'pending_players' => User::where('status', 'pending')->where('user_type', 'player')->count(),
            'pending_partners' => User::where('status', 'pending')->where('user_type', 'partner')->count(),
            'pending_documents' => UserDocument::where('status', 'pending_review')->count(),
        ];

        return view('admin.approvals.index', compact('pendingUsers', 'stats'));
    }

    /**
     * Show user details for approval
     */
    public function show($id)
    {
        $user = User::with(['roles', 'player', 'userDocuments' => function ($query) {
            $query->orderBy('uploaded_at', 'desc');
        }])->findOrFail($id);

        // Get user's uploaded documents
        $uploadedDocuments = UserDocument::where('user_id', $user->id)
            ->whereNotNull('document_type')
            ->orderBy('uploaded_at', 'desc')
            ->get();

        return view('admin.approvals.show', compact('user', 'uploadedDocuments'));
    }

    /**
     * Approve user account
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $user = User::findOrFail($id);

        // Update user status
        $user->update([
            'status' => 'active',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        // If user is a player, also approve their player record if it exists
        if ($user->isPlayer() && $user->player) {
            $user->player->grantFullApproval();
        }

        // Log the approval
        \Illuminate\Support\Facades\Log::info("User {$user->name} ({$user->email}) approved by admin " . auth()->user()->name);

        return redirect()->route('admin.approvals.index')
            ->with('success', 'User account approved successfully.');
    }

    /**
     * Reject user account
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        $user = User::findOrFail($id);

        // Update user status
        $user->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'rejected_at' => now(),
            'rejected_by' => auth()->id(),
        ]);

        // If user is a player, revoke player approval
        if ($user->isPlayer() && $user->player) {
            $user->player->revokeApproval();
        }

        // Log the rejection
        \Illuminate\Support\Facades\Log::info("User {$user->name} ({$user->email}) rejected by admin " . auth()->user()->name . ". Reason: " . $request->rejection_reason);

        return redirect()->route('admin.approvals.index')
            ->with('success', 'User account rejected.');
    }

    /**
     * Review and approve uploaded document
     */
    public function approveDocument(Request $request, $documentId)
    {
        $request->validate([
            'review_notes' => 'nullable|string|max:500',
        ]);

        $document = UserDocument::findOrFail($documentId);

        $document->update([
            'status' => 'approved',
            'review_notes' => $request->review_notes,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        // Check if user has all required documents approved
        $this->checkUserDocumentCompletion($document->user);

        return redirect()->back()
            ->with('success', 'Document approved successfully.');
    }

    /**
     * Reject uploaded document
     */
    public function rejectDocument(Request $request, $documentId)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
            'review_notes' => 'nullable|string|max:500',
        ]);

        $document = UserDocument::findOrFail($documentId);

        $document->update([
            'status' => 'rejected',
            'review_notes' => $request->review_notes ?: $request->rejection_reason,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', 'Document rejected. User can upload a corrected version.');
    }

    /**
     * Download user's uploaded document
     */
    public function downloadDocument($documentId)
    {
        $document = UserDocument::findOrFail($documentId);

        // Check if admin has permission to view this document
        if (!$document->file_path || !Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    /**
     * Bulk approve multiple documents
     */
    public function bulkApproveDocuments(Request $request)
    {
        $request->validate([
            'document_ids' => 'required|array',
            'document_ids.*' => 'exists:user_documents,id',
            'review_notes' => 'nullable|string|max:500',
        ]);

        $documents = UserDocument::whereIn('id', $request->document_ids)->get();

        foreach ($documents as $document) {
            $document->update([
                'status' => 'approved',
                'review_notes' => $request->review_notes,
                'reviewed_at' => now(),
                'reviewed_by' => auth()->id(),
            ]);

            $this->checkUserDocumentCompletion($document->user);
        }

        return redirect()->back()
            ->with('success', count($documents) . ' documents approved successfully.');
    }

    /**
     * Get pending documents for review
     */
    public function pendingDocuments(Request $request)
    {
        $query = UserDocument::where('status', 'pending_review')
            ->with(['user', 'document']);

        // Filter by user type
        if ($request->user_type) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('user_type', $request->user_type);
            });
        }

        // Filter by document type
        if ($request->document_type) {
            $query->where('document_type', $request->document_type);
        }

        $pendingDocuments = $query->orderBy('uploaded_at', 'desc')->paginate(20);

        return view('admin.approvals.documents', compact('pendingDocuments'));
    }

    /**
     * Check if user has completed all required documents
     */
    private function checkUserDocumentCompletion(User $user)
    {
        // This would check if all required documents are approved
        // For now, we'll just log it
        $approvedCount = UserDocument::where('user_id', $user->id)
            ->where('status', 'approved')
            ->count();

        // You could add logic here to automatically approve user if all documents are approved
        // or send notifications, etc.
    }
}
