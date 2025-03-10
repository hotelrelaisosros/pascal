<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }
    public function callbackGoogle()
    {
        $google = Socialite::driver('google')->stateless()->user();

        $user = User::where('google_id', '=', $google->getId())->first();

        if (!$user) {
            $new_user = User::create([
                'name' => $google->getName(),
                'email' => $google->getEmail(),
                'google_id' => $google->getId(),
            ]);
            Auth::login($new_user);
            return view('dashboard');
        } else {
            Auth::login($user);
            return view('dashboard');
        }
    }
    public function postCallBack(Request $request)
    {
        try {
            $user = User::where('google_id', $request['google_id'])
                ->select(['id', 'email', 'google_id', 'name'])  // Select only required fields
                ->first();

            if (!$user) {
                $new_user = User::create([
                    'name' => $request["name"],
                    'email' => $request["email"],
                    'google_id' => $request["google_id"],
                ]);
                $token = $new_user->createToken('google-auth-token')->plainTextToken;

                $getUser = [
                    'id' => $new_user->id,
                    'name' => $new_user->name,
                    'email' => $new_user->email,
                    'google_id' => $new_user->google_id,

                ];

                if ($new_user) {
                    return response()->json([
                        "success" => "true",
                        "status" => 200,
                        "user" => $getUser,
                        "token" => $token,
                    ]);
                }
            } else {
                $token = $user->createToken('google-auth-token')->plainTextToken;
                return response()->json([
                    "success" => "true",
                    "status" => 200,
                    "user" => $user,
                    "token" => $token,
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                "success" => "false",
                "status" => 404,

                "user" => $e->getMessage(),
            ]);
        }
    }
}
