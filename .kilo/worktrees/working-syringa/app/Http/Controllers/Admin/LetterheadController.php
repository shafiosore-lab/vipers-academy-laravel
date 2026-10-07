<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrganizationDocument;
use App\Models\OrganizationLetterhead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class LetterheadController extends Controller
{
    /**
     * Display the letterhead management page.
     */
    public function index()
    {
        $organization = $this->getCurrentOrganization();

        if (!$organization) {
            return redirect()->route('admin.dashboard')->with('error', 'No organization found.');
        }

        $letterheads = OrganizationLetterhead::where('organization_id', $organization->id)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $documents = OrganizationDocument::where('organization_id', $organization->id)
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('admin.letterhead.index', compact('organization', 'letterheads', 'documents'));
    }

    /**
     * Show the letterhead editor/create form.
     */
    public function create()
    {
        $organization = $this->getCurrentOrganization();

        if (!$organization) {
            return redirect()->route('admin.dashboard')->with('error', 'No organization found.');
        }

        $styles = OrganizationLetterhead::getStyles();
        $alignments = OrganizationLetterhead::getAlignments();

        return view('admin.letterhead.create', compact('organization', 'styles', 'alignments'));
    }

    /**
     * Store a new letterhead.
     */
    public function store(Request $request)
    {
        $organization = $this->getCurrentOrganization();

        if (!$organization) {
            return redirect()->route('admin.dashboard')->with('error', 'No organization found.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'style' => 'required|in:classic,modern,minimal',
            'header_alignment' => 'required|in:left,center',
            'primary_color' => 'required|string|max:20',
            'show_watermark' => 'boolean',
            'show_page_numbers' => 'boolean',
            'custom_footer_note' => 'nullable|string|max:500',
            'signature_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'signature_name' => 'nullable|string|max:255',
            'signature_title' => 'nullable|string|max:255',
            'is_default' => 'boolean',
        ]);

        $validated['organization_id'] = $organization->id;
        $validated['show_watermark'] = $request->boolean('show_watermark', true);
        $validated['show_page_numbers'] = $request->boolean('show_page_numbers', true);
        $validated['is_default'] = $request->boolean('is_default', false);

        // Handle signature image upload
        if ($request->hasFile('signature_image')) {
            $path = $request->file('signature_image')->store('signatures', 'public');
            $validated['signature_image'] = $path;
        }

        // If setting as default, unset other defaults
        if ($validated['is_default']) {
            OrganizationLetterhead::where('organization_id', $organization->id)
                ->update(['is_default' => false]);
        }

        $letterhead = OrganizationLetterhead::create($validated);

        return redirect()->route('admin.letterhead.index')
            ->with('success', 'Letterhead created successfully.');
    }

    /**
     * Show the letterhead edit form.
     */
    public function edit(OrganizationLetterhead $letterhead)
    {
        $organization = $this->getCurrentOrganization();

        // Verify ownership
        if ($letterhead->organization_id !== $organization->id) {
            return redirect()->route('admin.letterhead.index')
                ->with('error', 'Unauthorized access.');
        }

        $styles = OrganizationLetterhead::getStyles();
        $alignments = OrganizationLetterhead::getAlignments();

        return view('admin.letterhead.edit', compact('letterhead', 'styles', 'alignments'));
    }

    /**
     * Update an existing letterhead.
     */
    public function update(Request $request, OrganizationLetterhead $letterhead)
    {
        $organization = $this->getCurrentOrganization();

        // Verify ownership
        if ($letterhead->organization_id !== $organization->id) {
            return redirect()->route('admin.letterhead.index')
                ->with('error', 'Unauthorized access.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'style' => 'required|in:classic,modern,minimal',
            'header_alignment' => 'required|in:left,center',
            'primary_color' => 'required|string|max:20',
            'show_watermark' => 'boolean',
            'show_page_numbers' => 'boolean',
            'custom_footer_note' => 'nullable|string|max:500',
            'signature_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'signature_name' => 'nullable|string|max:255',
            'signature_title' => 'nullable|string|max:255',
            'is_default' => 'boolean',
        ]);

        $validated['show_watermark'] = $request->boolean('show_watermark', true);
        $validated['show_page_numbers'] = $request->boolean('show_page_numbers', true);
        $validated['is_default'] = $request->boolean('is_default', false);

        // Handle signature image upload
        if ($request->hasFile('signature_image')) {
            // Delete old signature if exists
            if ($letterhead->signature_image) {
                Storage::disk('public')->delete($letterhead->signature_image);
            }
            $path = $request->file('signature_image')->store('signatures', 'public');
            $validated['signature_image'] = $path;
        }

        // If setting as default, unset other defaults
        if ($validated['is_default']) {
            OrganizationLetterhead::where('organization_id', $organization->id)
                ->where('id', '!=', $letterhead->id)
                ->update(['is_default' => false]);
        }

        $letterhead->update($validated);

        return redirect()->route('admin.letterhead.index')
            ->with('success', 'Letterhead updated successfully.');
    }

    /**
     * Delete a letterhead.
     */
    public function destroy(OrganizationLetterhead $letterhead)
    {
        $organization = $this->getCurrentOrganization();

        // Verify ownership
        if ($letterhead->organization_id !== $organization->id) {
            return redirect()->route('admin.letterhead.index')
                ->with('error', 'Unauthorized access.');
        }

        // Delete signature image if exists
        if ($letterhead->signature_image) {
            Storage::disk('public')->delete($letterhead->signature_image);
        }

        $letterhead->delete();

        return redirect()->route('admin.letterhead.index')
            ->with('success', 'Letterhead deleted successfully.');
    }

    /**
     * Show the document editor.
     */
    public function documentCreate()
    {
        $organization = $this->getCurrentOrganization();

        if (!$organization) {
            return redirect()->route('admin.dashboard')->with('error', 'No organization found.');
        }

        $letterheads = OrganizationLetterhead::where('organization_id', $organization->id)
            ->orderBy('is_default', 'desc')
            ->get();

        // Get default letterhead
        $defaultLetterhead = $letterheads->firstWhere('is_default', true) ?? $letterheads->first();

        return view('admin.letterhead.document-create', compact('organization', 'letterheads', 'defaultLetterhead'));
    }

    /**
     * Store a new document.
     */
    public function documentStore(Request $request)
    {
        $organization = $this->getCurrentOrganization();

        if (!$organization) {
            return redirect()->route('admin.dashboard')->with('error', 'No organization found.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'letterhead_id' => 'nullable|exists:organization_letterheads,id',
            'status' => 'in:draft,published',
        ]);

        $validated['organization_id'] = $organization->id;
        $validated['created_by'] = Auth::id();
        $validated['status'] = $validated['status'] ?? 'draft';

        // Estimate page count (rough estimate: ~3000 characters per page)
        $charCount = strlen(strip_tags($validated['content']));
        $validated['page_count'] = max(1, ceil($charCount / 3000));

        // Verify letterhead belongs to organization
        if (!empty($validated['letterhead_id'])) {
            $letterhead = OrganizationLetterhead::find($validated['letterhead_id']);
            if ($letterhead && $letterhead->organization_id !== $organization->id) {
                $validated['letterhead_id'] = null;
            }
        }

        $document = OrganizationDocument::create($validated);

        return redirect()->route('admin.letterhead.documents')
            ->with('success', 'Document created successfully.');
    }

    /**
     * Show the document list.
     */
    public function documents()
    {
        $organization = $this->getCurrentOrganization();

        if (!$organization) {
            return redirect()->route('admin.dashboard')->with('error', 'No organization found.');
        }

        $documents = OrganizationDocument::where('organization_id', $organization->id)
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('admin.letterhead.documents', compact('organization', 'documents'));
    }

    /**
     * Show the document editor for editing.
     */
    public function documentEdit(OrganizationDocument $document)
    {
        $organization = $this->getCurrentOrganization();

        // Verify ownership
        if ($document->organization_id !== $organization->id) {
            return redirect()->route('admin.letterhead.documents')
                ->with('error', 'Unauthorized access.');
        }

        $letterheads = OrganizationLetterhead::where('organization_id', $organization->id)
            ->orderBy('is_default', 'desc')
            ->get();

        return view('admin.letterhead.document-edit', compact('document', 'letterheads'));
    }

    /**
     * Update an existing document.
     */
    public function documentUpdate(Request $request, OrganizationDocument $document)
    {
        $organization = $this->getCurrentOrganization();

        // Verify ownership
        if ($document->organization_id !== $organization->id) {
            return redirect()->route('admin.letterhead.documents')
                ->with('error', 'Unauthorized access.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'letterhead_id' => 'nullable|exists:organization_letterheads,id',
            'status' => 'in:draft,published',
        ]);

        $validated['status'] = $validated['status'] ?? $document->status;

        // Estimate page count
        $charCount = strlen(strip_tags($validated['content']));
        $validated['page_count'] = max(1, ceil($charCount / 3000));

        // Verify letterhead belongs to organization
        if (!empty($validated['letterhead_id'])) {
            $letterhead = OrganizationLetterhead::find($validated['letterhead_id']);
            if ($letterhead && $letterhead->organization_id !== $organization->id) {
                $validated['letterhead_id'] = null;
            }
        }

        $document->update($validated);

        return redirect()->route('admin.letterhead.documents')
            ->with('success', 'Document updated successfully.');
    }

    /**
     * Delete a document.
     */
    public function documentDestroy(OrganizationDocument $document)
    {
        $organization = $this->getCurrentOrganization();

        // Verify ownership
        if ($document->organization_id !== $organization->id) {
            return redirect()->route('admin.letterhead.documents')
                ->with('error', 'Unauthorized access.');
        }

        $document->delete();

        return redirect()->route('admin.letterhead.documents')
            ->with('success', 'Document deleted successfully.');
    }

    /**
     * Preview document as PDF.
     */
    public function documentPreview(OrganizationDocument $document)
    {
        $organization = $this->getCurrentOrganization();

        // Verify ownership
        if ($document->organization_id !== $organization->id) {
            return redirect()->route('admin.letterhead.documents')
                ->with('error', 'Unauthorized access.');
        }

        // Increment view count
        $document->incrementViews();

        // Get letterhead
        $letterhead = $document->letterhead;
        if (!$letterhead) {
            $letterhead = OrganizationLetterhead::where('organization_id', $organization->id)
                ->where('is_default', true)
                ->first() ?? OrganizationLetterhead::where('organization_id', $organization->id)->first();
        }

        $data = [
            'document' => $document,
            'organization' => $organization,
            'letterhead' => $letterhead,
        ];

        $pdf = Pdf::loadView('admin.letterhead.pdf.document-preview', $data);
        $pdf->setPaper('a4');

        return $pdf->stream($document->title . '.pdf');
    }

    /**
     * Download document as PDF.
     */
    public function documentDownload(OrganizationDocument $document)
    {
        $organization = $this->getCurrentOrganization();

        // Verify ownership
        if ($document->organization_id !== $organization->id) {
            return redirect()->route('admin.letterhead.documents')
                ->with('error', 'Unauthorized access.');
        }

        // Get letterhead
        $letterhead = $document->letterhead;
        if (!$letterhead) {
            $letterhead = OrganizationLetterhead::where('organization_id', $organization->id)
                ->where('is_default', true)
                ->first() ?? OrganizationLetterhead::where('organization_id', $organization->id)->first();
        }

        $data = [
            'document' => $document,
            'organization' => $organization,
            'letterhead' => $letterhead,
        ];

        $pdf = Pdf::loadView('admin.letterhead.pdf.document-preview', $data);
        $pdf->setPaper('a4');

        return $pdf->download($document->title . '.pdf');
    }

    /**
     * Get the current user's organization.
     */
    private function getCurrentOrganization(): ?Organization
    {
        $user = Auth::user();

        // Super admin doesn't have organization
        if ($user->hasRole('super-admin')) {
            // For super admin, try to get first organization or return null
            return Organization::first();
        }

        // Get organization from user
        return $user->organization ?? null;
    }
}
