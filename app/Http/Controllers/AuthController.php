<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Jobs\SignUpMailJob;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(LoginUserRequest $request)
    {
        $request->validated($request->all());

        $validated = $request->validated();

        if (!Auth::attempt($validated)) {
            return $this->error('', 'Invalid credentials', 401);
        }

        $user = User::where('email', $request->email)->first();

        $user->balance =  json_decode($user->balance);


        return $this->success([
            'token' => $user->createToken('API token of ' . $user->username, ['*'], now()->addMinutes(1440))->plainTextToken
        ], 'You have logged in successfully');
    }

    public function register(StoreUserRequest $request)
    {
        $request->validated($request->all());

        try {

            DB::beginTransaction();

            $user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'username' => $request->username,
                'email' => $request->email,
                'country_code' => $request->country_code,
                'mobile' => $request->mobile,
                'password' => Hash::make($request->password),
            ]);

            dispatch(new SignUpMailJob($user));

            DB::commit();

            return $this->success([
                'user' => $user,
            ], 'Registration successfull');
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->error('', throw $th, 401);
        }
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return $this->success([
            'message' => 'you have successfully logged out.'
        ]);
    }

    /* Email verification */

    public function notice()
    {
        return $this->success('', 'Please click the email verification link sent to your email.');
    }

    public function verify($id, $hash)
    {
        $user = User::find($id);

        if (!$user || !hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Invalid verification link'], 401);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified']);
        }

        $user->markEmailAsVerified();

        event(new Verified($user));

        return response()->json(['message' => 'Email successfully verified']);
    }

    public function resend(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified']);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification link resent']);
    }

    /* Password Reset */

    // Requesting Password Reset Link
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::broker('users')->sendResetLink($request->only('email'));

        return $this->success([
            'status' => $status,
        ],  __($status));
    }

    // Resetting Password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $this->success([
            'status' => $status,
        ],  __($status));
    }
}
