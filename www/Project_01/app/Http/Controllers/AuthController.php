<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // 使用 Eloquent 模型
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showAuthForm()
    {
        return view('auth');
    }

    public function register(Request $request)
    {
        Log::info('Register request received!');

        $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required|min:6'
        ]);

        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/')->with('success', '註冊成功！請登入');
    }

    public function login(Request $request)
    {
        Log::info('Login request received!');

        $user = User::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Session::put('user', $user->username);
            return redirect('/dashboard');
        } else {
            return redirect('/')->with('error', '登入失敗，查無此帳號');
        }
    }

    public function dashboard()
    {
        if (!Session::has('user')) {
            return redirect('/');
        }

        return view('dashboard', ['user' => Session::get('user')]);
    }

    public function logout()
    {
        Session::forget('user');
        return redirect('/');
    }

    public function deleteAccount()
    {
        if (!Session::has('user')) {
            return redirect('/')->with('error', '請先登入');
        }

        $username = Session::get('user');
        User::where('username', $username)->delete();

        Session::forget('user');

        return redirect('/')->with('success', '帳號已刪除');
    }

    public function getLogs()
    {
        $logPath = storage_path('logs/system.log');

        if (!file_exists($logPath)) {
            return response()->json(['logs' => []]);
        }

        $logs = file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        return response()->json(['logs' => $logs]);
    }
}

