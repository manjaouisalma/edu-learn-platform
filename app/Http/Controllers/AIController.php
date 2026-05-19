<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIController extends Controller
{
    public function generateQuestions(Request $request)
    {
        $content = $request->input('content');
        $numQ    = $request->input('num_questions', 5);
        $level   = $request->input('level', 'intermediate');

        $prompt = "You are an expert professor. Generate exactly {$numQ} multiple choice questions at {$level} level based on this course content:\n\n---\n{$content}\n---\n\nReply ONLY with valid JSON (no text before or after, no markdown), exactly this format:\n[\n  {\n    \"question\": \"Question text here\",\n    \"options\": [\"Option A\", \"Option B\", \"Option C\", \"Option D\"],\n    \"correct\": 0\n  }\n]\n\"correct\" is the index (0-3) of the correct option. Generate exactly {$numQ} objects.";

        $response = Http::withHeaders([
            'x-api-key'         => env('ANTHROPIC_API_KEY'),
            'anthropic-version' => '2023-06-01',
            'Content-Type'      => 'application/json',
        ])->post('https://api.anthropic.com/v1/messages', [
            'model'      => 'claude-haiku-4-5-20251001',
            'max_tokens' => 1500,
            'messages'   => [
                ['role' => 'user', 'content' => $prompt]
            ],
        ]);

        $full = $response->json();
        Log::info('AI full response', ['full' => $full]);

        if (isset($full['error'])) {
            Log::error('Anthropic error', ['error' => $full['error']]);
            return response()->json(['error' => $full['error']['message']], 500);
        }

        $text  = $full['content'][0]['text'] ?? '';
        $clean = trim(preg_replace('/```json|```/i', '', $text));

        Log::info('AI text', ['text' => $text]);

        $decoded = json_decode($clean, true);

        if (!is_array($decoded)) {
            Log::error('JSON decode failed', ['clean' => $clean]);
            return response()->json(['error' => 'Failed to parse AI response'], 500);
        }

        return response()->json($decoded);
    }
}