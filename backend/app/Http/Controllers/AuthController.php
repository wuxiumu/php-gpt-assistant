<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use OSS\OssClient;
use OSS\Core\OssException;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Register success',
            'token'   => $token,
            'user'    => $user,
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        return response()->json([
            'message' => 'Login success',
            'token'   => $token,
            'user'    => auth()->user(),
        ]);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function setApiKey(Request $request)
    {

        $request->validate([
            'api_key' => 'required|string|max:300',
        ]);

        $user = auth()->user();
        if (!$user) {
            // 未认证，直接返回401
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        $user->api_key = $request->api_key;
        $user->save();
        return response()->json(['message' => 'API key updated successfully']);
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|file|image|max:10240',
        ]);
        $user = auth()->user();
        $file = $request->file('avatar');
        $ext = $file->getClientOriginalExtension();
        $object = 'avatars/' . $user->id . '_' . uniqid() . '.' . $ext;

        try {
            $ossClient = new OssClient(
                env('OSS_ACCESS_KEY_ID'),
                env('OSS_ACCESS_KEY_SECRET'),
                env('OSS_ENDPOINT') // 形如 oss-cn-hangzhou.aliyuncs.com
            );
            $ossClient->uploadFile(env('OSS_BUCKET'), $object, $file->getRealPath());
            $url = env('OSS_HOST') . '/' . $object;
            $user->avatar = $url;
            $user->save();
            return response()->json(['avatar' => $url]);
        } catch (OssException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
