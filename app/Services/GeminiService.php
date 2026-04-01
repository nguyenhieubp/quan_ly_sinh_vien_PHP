<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $apiKey;
    protected $apiUrl = 'https://generativelanguage.googleapis.com/v1/models/gemini-2.0-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
    }

    /**
     * Generate a response from Gemini AI.
     *
     * @param string $prompt
     * @param string $systemInstruction
     * @return string|null
     */
    public function generateResponse(string $prompt, string $systemInstruction = ''): ?string
    {
        if (!$this->apiKey) {
            Log::error('Gemini API Key is missing in config.');
            return null;
        }

        Log::info('Gemini API Request', [
            'url' => $this->apiUrl,
            'key_prefix' => substr($this->apiKey, 0, 8) . '...'
        ]);

        $fullPrompt = "SYSTEM INSTRUCTION: " . $systemInstruction . "\n\nUSER PROMPT: " . $prompt;

        try {
            $response = Http::post("{$this->apiUrl}?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $fullPrompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 1024,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
            }

            Log::error('Gemini API Error: ' . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error('Gemini Service Exception: ' . $e->getMessage());
            return null;
        }
    }
}
