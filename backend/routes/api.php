<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes for php-gpt-assistant
|--------------------------------------------------------------------------
| 所有接口均为 RESTful API，采用 JWT 认证。
| 注意所有敏感操作都必须在 auth:api 保护下！
*/

// 用户注册 & 登录（不需要 token）
Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login');



// 受保护路由组，必须携带 Bearer Token
Route::middleware(['auth:api'])->group(function () {

    // 设置/更新 OpenAI API Key
    Route::post('/user/api-key', 'AuthController@setApiKey');

    // 获取当前用户信息
    Route::get('/user', 'AuthController@me');

    // 聊天接口（可叠加限流中间件 'rate.limit'）
    Route::post('/chat', 'ChatController@send')->middleware('rate.limit');
    // 获取历史聊天记录
    Route::get('/chat/history', 'ChatController@history');

    // 获取当前用户 Token 消耗统计
    Route::get('/stat/token-usage', 'StatController@tokenUsage');

    Route::post('/user/avatar', 'AuthController@uploadAvatar');
});

// 你也可以单独对某个接口加限流，例如：
// Route::middleware(['auth:api', 'rate.limit'])->post('/chat', 'ChatController@send');
