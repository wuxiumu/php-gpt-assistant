<?php


namespace App\Models;
// Laravel 8+ 使用此命名空间
// namespace App; // Laravel 7- 使用此命名空间

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

// 添加导入

class User extends Authenticatable implements JWTSubject
{
    // 实现 JWTSubject 接口的方法
    public function getJWTIdentifier()
    {
        return $this->getKey(); // 返回用户主键
    }

    public function getJWTCustomClaims()
    {
        return []; // 返回自定义声明（可选）
    }
}
