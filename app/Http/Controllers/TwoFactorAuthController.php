<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TwoFactorCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorCodeMail;

class TwoFactorAuthController extends Controller
{
    public function requestCode(Request $request)
    {
        $user = Auth::user();
        $delay = $this->getDelay($user);

        if ($delay) {
            return response()->json(['message' => 'Please wait for ' . $delay . ' seconds before requesting a new code.'], 429);
        }

        $code = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(env('TWO_FACTOR_CODE_EXPIRATION', 10));

        TwoFactorCode::updateOrCreate(
            ['user_id' => $user->id],
            ['code' => $code, 'expires_at' => $expiresAt]
        );

        Mail::to($user->email)->send(new TwoFactorCodeMail($code));

        return response()->json(['message' => 'Two-factor code sent.']);
    }

    public function verifyCode(Request $request)
    {
        $request->validate(['code' => 'required|numeric|digits:6']);
        $user = Auth::user();
        $twoFactorCode = TwoFactorCode::where('user_id', $user->id)->first();

        if (!$twoFactorCode || $twoFactorCode->isExpired()) {
            return response()->json(['message' => 'The two-factor code is invalid or expired.'], 401);
        }

        if ($twoFactorCode->code !== $request->code) {
            return response()->json(['message' => 'The two-factor code is incorrect.'], 401);
        }

        $twoFactorCode->delete();

        return response()->json(['message' => 'Two-factor authentication successful.']);
    }

    private function getDelay($user)
    {
        $codes = TwoFactorCode::where('user_id', $user->id)->latest()->take(3)->get();

        if ($codes->count() < 3) {
            return null;
        }

        $lastCode = $codes->first();
        $firstCode = $codes->last();

        return $lastCode->created_at->diffInSeconds($firstCode->created_at) < 90 ? 30 : null;
    }
}
