<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlayerFormRequest;
use App\Models\Player;
use App\Models\WebsitePlayer;
use Illuminate\Http\Request;

class AdminPlayerController extends Controller
{
    /**
     * Validation rules for store (used by both store and update)
     * Now consolidated in PlayerFormRequest
     */
    protected function getValidationRules(bool $isUpdate = false, ?int $playerId = null): array
    {
        $request = new PlayerFormRequest();
        return $request->rules($isUpdate, $playerId);
    }

    /**
     * Process file uploads for player
     */
    protected function processFileUploads(Request $request): array
    {
        $formRequest = new PlayerFormRequest();
        $fileFields = $formRequest->getFileFields();
        $data = [];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store('uploads/players', 'public');
            }
        }

        return $data;
    }

    /**
     * Prepare player data from request
     */
    protected function preparePlayerData(Request $request, bool $isUpdate = false): array
    {
        $data = $request->all();

        // Merge file uploads
        $data = array_merge($data, $this->processFileUploads($request));

        // Create full name from first and last name
        $data['full_name'] = $data['first_name'] . ' ' . $data['last_name'];

        // Calculate age from date of birth
        if (isset($data['date_of_birth'])) {
            $data['age'] = \Carbon\Carbon::parse($data['date_of_birth'])->age;
        }

        return $data;
    }

    public function index()
    {
        $players = Player::all();
        return view('admin.players.index', compact('players'));
    }

    public function show($id)
    {
        $player = Player::findOrFail($id);
        return view('admin.players.show', compact('player'));
    }

    public function create()
    {
        return view('admin.players.create');
    }

    public function store(Request $request)
    {
        // Use consolidated validation from PlayerFormRequest
        $validationRules = $this->getValidationRules(false);
        $request->validate($validationRules);

        $data = $this->preparePlayerData($request, false);

        $player = Player::create($data);

        // Sync to website if fully approved
        if ($player->isFullyApproved()) {
            $this->syncToWebsite($player);
        }

        return redirect()->route('admin.players.index')->with('success', 'Player registered successfully with FIFA compliance.');
    }

    public function edit($id)
    {
        $player = Player::findOrFail($id);
        return view('admin.players.edit', compact('player'));
    }

    public function update(Request $request, $id)
    {
        $player = Player::findOrFail($id);

        // Use consolidated validation from PlayerFormRequest
        $validationRules = $this->getValidationRules(true, $player->id);
        $request->validate($validationRules);

        $data = $this->preparePlayerData($request, true);

        $player->update($data);

        // Sync to website if fully approved
        if ($player->isFullyApproved()) {
            $this->syncToWebsite($player);
        }

        return redirect()->route('admin.players.index')->with('success', 'Player updated successfully with FIFA compliance.');
    }

    public function destroy($id)
    {
        $player = Player::findOrFail($id);
        $player->delete();

        return redirect()->route('admin.players.index')->with('success', 'Player deleted successfully.');
    }

    /**
     * Approve player with full approval
     */
    public function approve(Player $player)
    {
        $player->grantFullApproval();

        // Sync to website since player is now approved
        $this->syncToWebsite($player);

        return redirect()->back()->with('success', 'Player approved with full access successfully.');
    }

    /**
     * Grant temporary approval for 5 working days
     */
    public function approveTemporary(Request $request, Player $player)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500'
        ]);

        $notes = $request->input('notes', 'Temporary approval granted for 5 working days to allow document submission.');

        $player->grantTemporaryApproval($notes);

        return redirect()->back()->with('success', 'Player granted temporary approval for 5 working days. Access will be revoked if documents are not completed by expiry date.');
    }

    /**
     * Reject player application
     */
    public function reject(Player $player)
    {
        $player->revokeApproval();

        return redirect()->back()->with('success', 'Player application rejected and returned to pending status.');
    }

    /**
     * Check and update expired temporary approvals
     */
    public function checkExpiredApprovals()
    {
        $expiredPlayers = Player::where('approval_type', 'temporary')
            ->where('temporary_approval_expires_at', '<', now())
            ->where('documents_completed', false)
            ->get();

        foreach ($expiredPlayers as $player) {
            $player->revokeApproval();
        }

        return redirect()->back()->with('success', 'Checked and updated ' . $expiredPlayers->count() . ' expired temporary approvals.');
    }

    /**
     * Sync approved player to website
     * Syncs both full and temporary approved players
     */
    private function syncToWebsite(Player $player)
    {
        \Log::info('AdminPlayerController@syncToWebsite: Starting sync', [
            'player_id' => $player->id,
            'name' => $player->full_name,
            'approval_type' => $player->approval_type,
            'is_fully_approved' => $player->isFullyApproved(),
            'is_approved' => $player->isApproved()
        ]);

        // Only sync if player is approved (both full and temporary)
        if (!$player->isApproved()) {
            \Log::info('AdminPlayerController@syncToWebsite: Player not approved, skipping sync');
            return;
        }

        // Check if website player already exists via player_id
        $websitePlayer = $player->websitePlayer;

        if ($websitePlayer) {
            // Update existing website player
            $websitePlayer->update([
                'first_name' => $player->first_name,
                'last_name' => $player->last_name,
                'category' => $player->category,
                'position' => $player->position,
                'age' => $player->age,
                'jersey_number' => $player->jersey_number,
                'image_path' => $player->image_path,
                'bio' => $player->bio,
            ]);
            \Log::info('AdminPlayerController@syncToWebsite: Updated existing website player', [
                'website_player_id' => $websitePlayer->id
            ]);
        } else {
            // Check for duplicate by name (handle orphaned records with same name)
            $nameMatch = WebsitePlayer::where('first_name', $player->first_name)
                ->where('last_name', $player->last_name)
                ->whereNull('player_id')
                ->first();

            if ($nameMatch) {
                // Link orphaned record to this player
                $nameMatch->update(['player_id' => $player->id]);
                \Log::info('AdminPlayerController@syncToWebsite: Linked orphaned record', [
                    'website_player_id' => $nameMatch->id,
                    'player_id' => $player->id
                ]);
            } else {
                // Create new website player
                $websitePlayer = WebsitePlayer::create([
                    'first_name' => $player->first_name,
                    'last_name' => $player->last_name,
                    'category' => $player->category,
                    'position' => $player->position,
                    'age' => $player->age,
                    'jersey_number' => $player->jersey_number,
                    'image_path' => $player->image_path,
                    'bio' => $player->bio,
                    'player_id' => $player->id,
                ]);
                \Log::info('AdminPlayerController@syncToWebsite: Created new website player', [
                    'website_player_id' => $websitePlayer->id
                ]);
            }
        }

        \Log::info('AdminPlayerController@syncToWebsite: Sync completed');
    }
}
