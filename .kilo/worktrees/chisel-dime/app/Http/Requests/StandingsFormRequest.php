<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StandingsFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $type = $this->get('type', 'league');

        switch ($type) {
            case 'league':
                return [
                    'season' => 'required|string|max:20',
                    'league_name' => 'required|string|max:255',
                    'team_name' => 'required|string|max:255',
                    'team_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'position' => 'required|integer|min:1',
                    'played' => 'required|integer|min:0',
                    'won' => 'required|integer|min:0',
                    'drawn' => 'required|integer|min:0',
                    'lost' => 'required|integer|min:0',
                    'goals_for' => 'required|integer|min:0',
                    'goals_against' => 'required|integer|min:0',
                    'points' => 'required|integer|min:0',
                    'clean_sheets' => 'nullable|integer|min:0',
                    'failed_to_score' => 'nullable|integer|min:0',
                    'form' => 'nullable|string|max:10',
                    'status' => 'nullable|string|max:50',
                    'notes' => 'nullable|string',
                    'is_vipers_team' => 'boolean',
                ];
            case 'scorers':
                return [
                    'season' => 'required|string|max:20',
                    'league_name' => 'required|string|max:255',
                    'player_name' => 'required|string|max:255',
                    'player_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'team_name' => 'required|string|max:255',
                    'team_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'ranking_position' => 'required|integer|min:1',
                    'goals' => 'required|integer|min:0',
                    'assists' => 'required|integer|min:0',
                    'appearances' => 'required|integer|min:0',
                    'minutes_played' => 'required|integer|min:0',
                    'shots_on_target' => 'nullable|integer|min:0',
                    'shots_total' => 'nullable|integer|min:0',
                    'penalties_scored' => 'nullable|integer|min:0',
                    'penalties_missed' => 'nullable|integer|min:0',
                    'free_kicks' => 'nullable|integer|min:0',
                    'headers' => 'nullable|integer|min:0',
                    'left_foot' => 'nullable|integer|min:0',
                    'right_foot' => 'nullable|integer|min:0',
                    'inside_box' => 'nullable|integer|min:0',
                    'outside_box' => 'nullable|integer|min:0',
                    'nationality' => 'nullable|string|max:100',
                    'age' => 'nullable|integer|min:15|max:50',
                    'player_position' => 'required|string|max:50',
                    'notes' => 'nullable|string',
                    'is_vipers_player' => 'boolean',
                ];
            case 'cleansheets':
                return [
                    'season' => 'required|string|max:20',
                    'league_name' => 'required|string|max:255',
                    'goalkeeper_name' => 'required|string|max:255',
                    'goalkeeper_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'team_name' => 'required|string|max:255',
                    'team_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'position' => 'required|integer|min:1',
                    'clean_sheets' => 'required|integer|min:0',
                    'appearances' => 'required|integer|min:0',
                    'saves' => 'required|integer|min:0',
                    'goals_conceded' => 'required|integer|min:0',
                    'penalties_saved' => 'nullable|integer|min:0',
                    'penalties_faced' => 'nullable|integer|min:0',
                    'minutes_played' => 'required|integer|min:0',
                    'shots_faced' => 'nullable|integer|min:0',
                    'shots_on_target_faced' => 'nullable|integer|min:0',
                    'nationality' => 'nullable|string|max:100',
                    'age' => 'nullable|integer|min:15|max:50',
                    'height_cm' => 'nullable|decimal:0,2',
                    'dominant_hand' => 'required|string|max:20',
                    'notes' => 'nullable|string',
                    'is_vipers_player' => 'boolean',
                ];
            case 'goalkeepers':
                return [
                    'season' => 'required|string|max:20',
                    'league_name' => 'required|string|max:255',
                    'goalkeeper_name' => 'required|string|max:255',
                    'goalkeeper_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'team_name' => 'required|string|max:255',
                    'team_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'position' => 'required|integer|min:1',
                    'appearances' => 'required|integer|min:0',
                    'minutes_played' => 'required|integer|min:0',
                    'saves' => 'required|integer|min:0',
                    'goals_conceded' => 'required|integer|min:0',
                    'clean_sheets' => 'required|integer|min:0',
                    'shots_faced' => 'nullable|integer|min:0',
                    'shots_on_target_faced' => 'nullable|integer|min:0',
                    'penalties_saved' => 'nullable|integer|min:0',
                    'penalties_faced' => 'nullable|integer|min:0',
                    'catches' => 'nullable|integer|min:0',
                    'punches' => 'nullable|integer|min:0',
                    'distribution_completed' => 'nullable|integer|min:0',
                    'distribution_attempted' => 'nullable|integer|min:0',
                    'crosses_claimed' => 'nullable|integer|min:0',
                    'errors_leading_to_goal' => 'nullable|integer|min:0',
                    'overall_rating' => 'nullable|decimal:0,2',
                    'nationality' => 'nullable|string|max:100',
                    'age' => 'nullable|integer|min:15|max:50',
                    'height_cm' => 'nullable|decimal:0,2',
                    'dominant_hand' => 'required|string|max:20',
                    'notes' => 'nullable|string',
                    'is_vipers_player' => 'boolean',
                ];
        }

        return [];
    }

    public function prepareData($validated)
    {
        $type = $this->get('type', 'league');

        switch ($type) {
            case 'league':
                $validated['goal_difference'] = $validated['goals_for'] - $validated['goals_against'];
                $validated['points_per_game'] = $validated['played'] > 0 ? $validated['points'] / $validated['played'] : 0;
                break;
            case 'scorers':
                $validated['goals_per_game'] = $validated['appearances'] > 0 ? $validated['goals'] / $validated['appearances'] : 0;
                $validated['shot_accuracy'] = $validated['shots_total'] > 0 ? ($validated['shots_on_target'] / $validated['shots_total']) * 100 : null;
                break;
            case 'cleansheets':
                $validated['goals_conceded_per_game'] = $validated['appearances'] > 0 ? $validated['goals_conceded'] / $validated['appearances'] : 0;
                $validated['save_percentage'] = $validated['shots_on_target_faced'] > 0 ? ($validated['saves'] / $validated['shots_on_target_faced']) * 100 : null;
                $validated['clean_sheet_percentage'] = $validated['appearances'] > 0 ? ($validated['clean_sheets'] / $validated['appearances']) * 100 : null;
                $validated['shots_faced_per_game'] = $validated['appearances'] > 0 ? $validated['shots_faced'] / $validated['appearances'] : 0;
                break;
            case 'goalkeepers':
                $validated['goals_conceded_per_game'] = $validated['appearances'] > 0 ? $validated['goals_conceded'] / $validated['appearances'] : 0;
                $validated['save_percentage'] = $validated['shots_on_target_faced'] > 0 ? ($validated['saves'] / $validated['shots_on_target_faced']) * 100 : null;
                $validated['clean_sheet_percentage'] = $validated['appearances'] > 0 ? ($validated['clean_sheets'] / $validated['appearances']) * 100 : null;
                $validated['shots_faced_per_game'] = $validated['appearances'] > 0 ? $validated['shots_faced'] / $validated['appearances'] : 0;
                $validated['penalty_save_percentage'] = $validated['penalties_faced'] > 0 ? ($validated['penalties_saved'] / $validated['penalties_faced']) * 100 : null;
                $validated['distribution_accuracy'] = $validated['distribution_attempted'] > 0 ? ($validated['distribution_completed'] / $validated['distribution_attempted']) * 100 : null;
                break;
        }

        return $validated;
    }
}
