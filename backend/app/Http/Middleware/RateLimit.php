<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class RateLimit
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $key = 'rate_limit:user:' . $user->id;
        $limit = 5;  // 每60秒最多请求5次
        $expire = 60; // 秒

        $current = Redis::incr($key);
        if ($current == 1) {
            Redis::expire($key, $expire);
        }

        if ($current > $limit) {
            return response()->json(['error' => 'Too many requests, please try later.'], 429);
        }

        return $next($request);
    }
}
