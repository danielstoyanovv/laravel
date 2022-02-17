<?php

namespace App\Http\Controllers\FaceBook;

use App\Http\Controllers\Controller;
use App\Models\User;
use Socialite;
use Exception;
use Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\AdminController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class CallbackController extends Controller
{
    /**
     * redirect
     * @return RedirectResponse
     */
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * login
     * @return RedirectResponse
     */
    public function login()
    {
        try {
            $user = Socialite::driver('facebook')->user();
            $isUser = User::where('fb_id', $user->id)->first();
            if ($isUser) {
                Auth::login($isUser);
                return redirect()->action([AdminController::class, 'index']);
            }
            $createUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'fb_id' => $user->id,
                'password' => Hash::make('admin@123')
            ]);
            Auth::login($createUser);
            return redirect()->action([AdminController::class, 'index']);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
