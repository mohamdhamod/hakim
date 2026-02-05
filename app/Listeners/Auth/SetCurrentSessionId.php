<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Login;

class SetCurrentSessionId
{
    public function handle(Login $event): void
    {
        $request = request();

        if (!$request->hasSession()) {
            return;
        }

        $sessionId = $request->session()->getId();

        if (!$sessionId) {
            return;
        }
        $request->session()->put('single_session_lock_pending', true);

        $event->user->forceFill([
            'current_session_id' => $sessionId,
        ])->save();
    }
}
