<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Logout;

class ClearCurrentSessionId
{
    public function handle(Logout $event): void
    {
        $request = request();

        if (!$request->hasSession()) {
            return;
        }

        $sessionId = $request->session()->getId();

        if (!$sessionId) {
            return;
        }

        if (($event->user?->current_session_id ?? null) !== $sessionId) {
            return;
        }

        $event->user->forceFill([
            'current_session_id' => null,
        ])->save();
    }
}
