<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ChatMessage;

class ChatController extends Controller
{

    public function send(Request $request)
    {
        // 验证输入
        $request->validate([
            'message' => 'required|string|max:1000',
            'system_prompt' => 'nullable|string|max:1000',
            'model' => 'nullable|string|max:32'
        ]);
        $user = auth()->user();

        // 用户必须设置自己的 OpenAI API Key
        if (!$user->api_key) {
            return response()->json(['error' => 'No API key bound to account.'], 400);
        }

        // 允许自定义系统 prompt，默认可留空
        $systemPrompt = $request->input('system_prompt', 'You are ChatGPT, a large language model.');
        $model = $request->input('model', 'gpt-3.5-turbo');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $user->api_key,
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model'    => $model,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $request->message],
            ],
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'API request failed', 'detail' => $response->json()], 500);
        }

        $data = $response->json();

        // 保存聊天记录
        ChatMessage::create([
            'user_id'   => $user->id,
            'prompt'    => $request->message,
            'response'  => $data['choices'][0]['message']['content'] ?? '',
            'model'     => $data['model'] ?? 'unknown',
            'tokens'    => $data['usage']['total_tokens'] ?? 0,
        ]);

        return response()->json([
            'reply' => $data['choices'][0]['message']['content'],
            'usage' => $data['usage'] ?? null,
        ]);
    }

    public function history()
    {
        $user = auth()->user();
        return ChatMessage::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();
    }
}
