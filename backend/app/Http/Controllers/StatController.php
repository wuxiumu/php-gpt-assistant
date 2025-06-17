<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;
use Carbon\Carbon;

class StatController extends Controller
{
    // 按天统计当前用户的 token 消耗
    public function tokenUsage(Request $request)
    {
        $user = auth()->user();

        // 获取最近30天的每日消耗
        $days = 30;
        $startDate = Carbon::now()->subDays($days)->startOfDay();

        $stats = ChatMessage::where('user_id', $user->id)
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as day, SUM(tokens) as total_tokens')
            ->groupBy('day')
            ->orderBy('day', 'desc')
            ->limit($days)
            ->get();

        // 总消耗
        $total = ChatMessage::where('user_id', $user->id)->sum('tokens');

        return response()->json([
            'days' => $stats,
            'total' => $total,
        ]);
    }
}
