<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function reply(Request $request): JsonResponse
    {
        $message = mb_strtolower($request->validate([
            'message' => 'required|string|max:300',
        ])['message']);

        if (str_contains($message, 'market')) {
            return response()->json([
                'reply' => 'You can browse all markets by location and day.',
                'links' => [
                    ['label' => 'Explore markets', 'url' => '/markets'],
                ],
            ]);
        }

        return response()->json([
            'reply' => 'I am not sure about that yet. You can contact the team.',
            'links' => [
                ['label' => 'Contact us', 'url' => '/contact'],
            ],
        ]);
    }
}