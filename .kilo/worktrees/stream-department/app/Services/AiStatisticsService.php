<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiStatisticsService
{
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
        $this->apiUrl = 'https://api.openai.com/v1/chat/completions';
    }

    /**
     * Extract statistics from game summary text using AI
     *
     * @param string $gameSummary
     * @return array
     */
    public function extractStatisticsFromSummary(string $gameSummary): array
    {
        try {
            // For now, return mock data. In production, this would call an AI API
            return $this->mockAiExtraction($gameSummary);

            // Uncomment below for actual AI integration
            /*
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a football statistics analyst. Extract player statistics from game summaries and return them in JSON format.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $this->buildPrompt($gameSummary)
                    ]
                ],
                'max_tokens' => 500,
                'temperature' => 0.3,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $content = $data['choices'][0]['message']['content'] ?? '';

                return $this->parseAiResponse($content);
            }

            Log::error('AI Statistics Service Error: ' . $response->body());
            return $this->getDefaultStats();
            */
        } catch (\Exception $e) {
            Log::error('AI Statistics Service Exception: ' . $e->getMessage());
            return $this->getDefaultStats();
        }
    }

    /**
     * Mock AI extraction for development/testing
     * In production, replace with actual AI API calls
     */
    private function mockAiExtraction(string $gameSummary): array
    {
        // Simple keyword-based extraction for demo purposes
        $summary = strtolower($gameSummary);

        $stats = $this->getDefaultStats();

        // Extract goals
        if (preg_match('/(\d+)\s*goals?/', $summary, $matches)) {
            $stats['goals_scored'] = (int) $matches[1];
        }

        // Extract assists
        if (preg_match('/(\d+)\s*assist/', $summary, $matches)) {
            $stats['assists'] = (int) $matches[1];
        }

        // Extract minutes
        if (preg_match('/(\d+)\s*minutes?/', $summary, $matches)) {
            $stats['minutes_played'] = min((int) $matches[1], 120);
        }

        // Extract shots on target
        if (preg_match('/(\d+)\s*shots?\s*(?:on\s*target)?/', $summary, $matches)) {
            $stats['shots_on_target'] = (int) $matches[1];
        }

        // Extract tackles
        if (preg_match('/(\d+)\s*tackles?/', $summary, $matches)) {
            $stats['tackles'] = (int) $matches[1];
        }

        // Extract interceptions
        if (preg_match('/(\d+)\s*interceptions?/', $summary, $matches)) {
            $stats['interceptions'] = (int) $matches[1];
        }

        // Extract saves (for goalkeepers)
        if (preg_match('/(\d+)\s*saves?/', $summary, $matches)) {
            $stats['saves'] = (int) $matches[1];
        }

        // Extract cards
        if (preg_match('/yellow\s*card/', $summary)) {
            $stats['yellow_cards'] = 1;
        }
        if (preg_match('/red\s*card/', $summary)) {
            $stats['red_cards'] = 1;
        }

        // Generate a random rating between 5.0 and 9.5
        $stats['rating'] = round(mt_rand(50, 95) / 10, 1);

        return $stats;
    }

    /**
     * Build the AI prompt for statistics extraction
     */
    private function buildPrompt(string $gameSummary): string
    {
        return "Extract football player statistics from the following game summary. Return ONLY a JSON object with these fields: goals_scored, assists, minutes_played, shots_on_target, passes_completed, tackles, interceptions, saves, yellow_cards, red_cards, rating (decimal between 0-10).

Game Summary: {$gameSummary}

Example output: {\"goals_scored\": 2, \"assists\": 1, \"minutes_played\": 90, \"shots_on_target\": 3, \"passes_completed\": 45, \"tackles\": 5, \"interceptions\": 2, \"saves\": 0, \"yellow_cards\": 0, \"red_cards\": 0, \"rating\": 8.5}";
    }

    /**
     * Parse AI response into statistics array
     */
    private function parseAiResponse(string $content): array
    {
        try {
            $data = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning('AI Response JSON decode error: ' . json_last_error_msg());
                return $this->getDefaultStats();
            }

            return array_merge($this->getDefaultStats(), array_filter($data, function($value) {
                return is_numeric($value) && $value >= 0;
            }));

        } catch (\Exception $e) {
            Log::error('AI Response parsing error: ' . $e->getMessage());
            return $this->getDefaultStats();
        }
    }

    /**
     * Get default statistics structure
     */
    private function getDefaultStats(): array
    {
        return [
            'goals_scored' => 0,
            'assists' => 0,
            'minutes_played' => 0,
            'shots_on_target' => 0,
            'passes_completed' => 0,
            'tackles' => 0,
            'interceptions' => 0,
            'saves' => 0,
            'yellow_cards' => 0,
            'red_cards' => 0,
            'rating' => null,
        ];
    }
}
