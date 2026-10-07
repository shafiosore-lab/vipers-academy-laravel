<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GameStatsFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'player_id' => 'required|integer|exists:website_players,id',
            'game_date' => 'required|date|before_or_equal:today',
            'opponent_team' => 'required|string|max:255',
            'minutes_played' => 'required|integer|min:0|max:120',
            'tournament' => 'nullable|string|max:255',
            'goals_scored' => 'nullable|integer|min:0',
            'assists' => 'nullable|integer|min:0',
            'shots_on_target' => 'nullable|integer|min:0',
            'passes_completed' => 'nullable|integer|min:0',
            'tackles' => 'nullable|integer|min:0',
            'interceptions' => 'nullable|integer|min:0',
            'saves' => 'nullable|integer|min:0',
            'rating' => 'nullable|numeric|min:0|max:10',
            'yellow_cards' => 'nullable|integer|min:0|max:2',
            'red_cards' => 'nullable|integer|min:0|max:1',
            'game_summary' => 'nullable|string|max:1000',
        ];
    }
}
