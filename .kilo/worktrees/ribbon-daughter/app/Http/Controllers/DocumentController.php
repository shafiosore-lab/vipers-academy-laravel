<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Document;
use App\Models\UserDocument;
use App\Models\User;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.status');
    }

    /**
     * Display documents accessible to the current user
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $userRole = $this->getUserRole($user);

        // Get user ID for personalization
        $playerId = null;
        if ($user->isPlayer()) {
            $player = \App\Models\Player::where('email', $user->email)->first();
            $playerId = $player?->id;
        }

        // Build query with role-based access
        $query = Document::active()->whereJsonContains('target_roles', $userRole);

        // Apply filters
        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->mandatory_only) {
            $query->where('is_mandatory', true);
        }

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $documents = $query->with(['userDocuments' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->orderBy('category')->orderBy('title')->paginate(20);

        // Get categories for filter
        $categories = Document::active()->whereJsonContains('target_roles', $userRole)
            ->distinct()->pluck('category')->toArray();

        // Get user document stats
        $stats = $this->getUserDocumentStats($user, $userRole);

        return view('documents.index', compact('documents', 'categories', 'stats', 'userRole'));
    }

    /**
     * Show specific document details
     */
    public function show($documentId)
    {
        $user = auth()->user();
        $userRole = $this->getUserRole($user);

        $document = Document::where('document_id', $documentId)->firstOrFail();

        // Check if user has access to this document
        if (!$document->isAccessibleByRole($userRole)) {
            abort(403, 'Access denied. You do not have permission to view this document.');
        }

        // Get or create user document record for tracking
        $userDocument = UserDocument::firstOrCreate(
            [
                'user_id' => $user->id,
                'document_id' => $document->id
            ],
            [
                'is_mandatory_for_user' => $document->isMandatoryForRole($userRole) ||
                                          ($document->expiry_days ? true : false),
                'expires_at' => $document->expiry_days ? now()->addDays($document->expiry_days) : null,
            ]
        );

        // Mark as viewed (this time)
        $userDocument->markAsViewed();

        // Get document views stats
        $viewStats = [
            'total_views' => $document->view_count,
            'total_downloads' => $document->download_count,
            'signed_count' => $document->signature_count,
        ];

        return view('documents.show', compact('document', 'userDocument', 'viewStats', 'userRole'));
    }

    /**
     * Download document file
     */
    public function download($documentId): BinaryFileResponse
    {
        $user = auth()->user();
        $userRole = $this->getUserRole($user);

        $document = Document::where('document_id', $documentId)->firstOrFail();

        // Check access permissions
        if (!$document->isAccessibleByRole($userRole)) {
            abort(403, 'Access denied.');
        }

        // Check if file exists
        if (!$document->fileExists()) {
            abort(404, 'Document file not found.');
        }

        // Track download in user document record
        $userDocument = UserDocument::firstOrCreate(
            ['user_id' => $user->id, 'document_id' => $document->id]
        );
        $userDocument->markAsDownloaded();

        return response()->download(storage_path('app/public/' . $document->file_path));
    }

    /**
     * Sign document (mark as electronically signed)
     */
    public function sign(Request $request, $documentId)
    {
        $user = auth()->user();
        $userRole = $this->getUserRole($user);

        $document = Document::where('document_id', $documentId)->firstOrFail();

        if (!$document->isAccessibleByRole($userRole) || !$document->requires_signature) {
            abort(403, 'Access denied or document does not require signature.');
        }

        $request->validate([
            'agree_to_terms' => 'required|accepted',
            'signature_data' => 'nullable|string', // For digital signature
            'notes' => 'nullable|string|max:500',
        ]);

        $userDocument = UserDocument::firstOrCreate(
            ['user_id' => $user->id, 'document_id' => $document->id]
        );

        // Handle digital signature if provided (base64 image data)
        $signaturePath = null;
        if ($request->signature_data) {
            $signaturePath = $this->storeDigitalSignature($request->signature_data, $user, $document);
        }

        $userDocument->markAsSigned($signaturePath);

        if ($request->notes) {
            $userDocument->update(['notes' => $request->notes]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Document signed successfully.',
            'signed_at' => $userDocument->signed_at->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Track document view time for compliance
     */
    public function trackView($documentId)
    {
        $user = auth()->user();

        $document = Document::where('document_id', $documentId)->firstOrFail();

        $userDocument = UserDocument::firstOrCreate(
            ['user_id' => $user->id, 'document_id' => $document->id]
        );

        $userDocument->markAsViewed();

        return response()->json(['success' => true]);
    }

    /**
     * Get user's required documents status
     */
    public function getRequiredDocuments()
    {
        $user = auth()->user();
        $userRole = $this->getUserRole($user);

        $mandatoryDocs = Document::whereJsonContains('target_roles', $userRole)
            ->where('is_mandatory', true)
            ->active()
            ->get();

        $pendingCount = 0;
        $signedCount = 0;
        $expiredCount = 0;

        foreach ($mandatoryDocs as $doc) {
            $userDoc = UserDocument::where('user_id', $user->id)
                ->where('document_id', $doc->id)
                ->first();

            if (!$userDoc || (!$userDoc->isSigned() && !$userDoc->isExpired())) {
                $pendingCount++;
            } elseif ($userDoc->isSigned()) {
                $signedCount++;
            } elseif ($userDoc->isExpired()) {
                $expiredCount++;
            }
        }

        return response()->json([
            'mandatory_total' => $mandatoryDocs->count(),
            'pending' => $pendingCount,
            'signed' => $signedCount,
            'expired' => $expiredCount,
            'completion_percentage' => $mandatoryDocs->count() > 0
                ? round(($signedCount / $mandatoryDocs->count()) * 100)
                : 100
        ]);
    }

    /**
     * Get expiring documents for the user
     */
    public function getExpiringDocuments()
    {
        $user = auth()->user();

        $expiringSoon = UserDocument::where('user_id', $user->id)
            ->with('document')
            ->expiringSoon(7) // 7 days
            ->whereHas('document', function ($query) {
                $query->active();
            })
            ->get();

        return response()->json([
            'expiring_count' => $expiringSoon->count(),
            'documents' => $expiringSoon->map(function ($userDoc) {
                return [
                    'id' => $userDoc->document->document_id,
                    'title' => $userDoc->document->title,
                    'days_left' => $userDoc->getDaysUntilExpiry(),
                    'expires_at' => $userDoc->expires_at->format('M d, Y'),
                ];
            })
        ]);
    }

    // ========================================
    // HELPER METHODS
    // ========================================

    /**
     * Get user role string for document access control
     */
    private function getUserRole(User $user): string
    {
        if ($user->isAdmin()) return 'admin';

        $player = \App\Models\Player::where('email', $user->email)->first();

        if ($player && $player->isApproved()) {
            return 'player';
        }

        // Check if user is registered as parent or guardian
        if ($player && str_contains(strtolower($user->name), 'parent')) {
            return 'parent';
        }

        // Check if user is coach (admin approval required)
        if ($user->isPartner() && $user->status === 'approved') {
            // Here you would check if this partner is specifically a coach
            // For now, return partner role
            return 'coach';
        }

        return 'admin'; // Fallback
    }

    /**
     * Store digital signature image
     */
    private function storeDigitalSignature(string $signatureData, User $user, Document $document): string
    {
        // Remove base64 prefix if present
        if (str_contains($signatureData, 'data:')) {
            $signatureData = explode(',', $signatureData)[1];
        }

        $imageData = base64_decode($signatureData);
        $filename = 'signature_' . $user->id . '_' . $document->id . '_' . time() . '.png';
        $path = 'signatures/' . $filename;

        Storage::disk('public')->put($path, $imageData);

        return $path;
    }

    /**
     * Get user document statistics
     */
    private function getUserDocumentStats(User $user, string $userRole): array
    {
        $accessibleDocs = Document::whereJsonContains('target_roles', $userRole)->active()->count();

        $userDocs = UserDocument::where('user_id', $user->id)->get();

        return [
            'total_accessible' => $accessibleDocs,
            'viewed' => $userDocs->where('status', 'viewed')->count(),
            'downloaded' => $userDocs->where('status', 'downloaded')->count(),
            'signed' => $userDocs->where('status', 'signed')->count(),
            'pending' => $userDocs->where('status', 'pending')->count(),
        ];
    }

    /**
     * Get document template data for seeding
     */
    public static function getDocumentTemplates(): array
    {
        return [
            // Codes of Conduct
            ['document_id' => 'player_code_of_conduct_v1', 'title' => 'Player Code of Conduct', 'category' => 'codes_of_conduct', 'file_name' => 'player_code_of_conduct.pdf', 'roles' => ['player'], 'mandatory' => true, 'expiry_days' => 365],
            ['document_id' => 'parent_guardian_code_of_conduct_v1', 'title' => 'Parent/Guardian Code of Conduct', 'category' => 'codes_of_conduct', 'file_name' => 'parent_guardian_code_of_conduct.pdf', 'roles' => ['parent'], 'mandatory' => true, 'expiry_days' => 365],
            ['document_id' => 'coach_code_of_conduct_v1', 'title' => 'Coach Code of Conduct', 'category' => 'codes_of_conduct', 'file_name' => 'coach_code_of_conduct.pdf', 'roles' => ['coach'], 'mandatory' => true, 'expiry_days' => 365],
            ['document_id' => 'staff_volunteers_code_of_conduct_v1', 'title' => 'Staff & Volunteers Code of Conduct', 'category' => 'codes_of_conduct', 'file_name' => 'staff_volunteers_code_of_conduct.pdf', 'roles' => ['admin'], 'mandatory' => true, 'expiry_days' => 365],
            ['document_id' => 'referee_umpire_conduct_v1', 'title' => 'Referee/Umpire Code of Conduct', 'category' => 'codes_of_conduct', 'file_name' => 'referee_umpire_conduct.pdf', 'roles' => ['coach', 'admin'], 'mandatory' => true, 'expiry_days' => 365],

            // Safety & Protection
            ['document_id' => 'child_safety_protection_policy_v1', 'title' => 'Child Safety & Protection Policy', 'category' => 'safety_protection', 'file_name' => 'child_safety_protection_policy.pdf', 'roles' => ['parent', 'coach', 'admin'], 'mandatory' => true, 'expiry_days' => 365],
            ['document_id' => 'anti_bullying_harassment_policy_v1', 'title' => 'Anti-Bullying & Harassment Policy', 'category' => 'safety_protection', 'file_name' => 'anti_bullying_harassment_policy.pdf', 'roles' => ['player', 'parent', 'coach', 'admin'], 'mandatory' => true, 'expiry_days' => 365],
            ['document_id' => 'safeguarding_reporting_procedure_v1', 'title' => 'Safeguarding Reporting Procedure', 'category' => 'safety_protection', 'file_name' => 'safeguarding_reporting_procedure.pdf', 'roles' => ['coach', 'admin'], 'mandatory' => true, 'expiry_days' => 365],
            ['document_id' => 'incident_accident_report_form_v1', 'title' => 'Incident & Accident Report Form', 'category' => 'safety_protection', 'file_name' => 'incident_accident_report.pdf', 'roles' => ['coach', 'admin'], 'mandatory' => false, 'expiry_days' => null],
            ['document_id' => 'emergency_action_plan_v1', 'title' => 'Emergency Action Plan', 'category' => 'safety_protection', 'file_name' => 'emergency_action_plan.pdf', 'roles' => ['player', 'parent', 'coach', 'admin'], 'mandatory' => true, 'expiry_days' => 365],
            ['document_id' => 'medical_clearance_requirements_v1', 'title' => 'Medical Clearance Requirements', 'category' => 'safety_protection', 'file_name' => 'medical_clearance_requirements.pdf', 'roles' => ['player', 'parent'], 'mandatory' => true, 'expiry_days' => 365],
            ['document_id' => 'concussion_protocol_v1', 'title' => 'Concussion Protocol', 'category' => 'safety_protection', 'file_name' => 'concussion_protocol.pdf', 'roles' => ['coach', 'admin'], 'mandatory' => true, 'expiry_days' => 365],
            ['document_id' => 'anti_drugs_substance_abuse_policy_v1', 'title' => 'Anti-Drugs & Substance Abuse Policy', 'category' => 'safety_protection', 'file_name' => 'anti_drugs_substance_abuse_policy.pdf', 'roles' => ['player', 'parent'], 'mandatory' => true, 'expiry_days' => 365],
        ];
    }
}
