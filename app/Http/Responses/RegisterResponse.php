<?php

namespace App\Http\Responses;

use App\Enums\RoleEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function toResponse($request)
    {
        $locale = app()->getLocale();
        $user = auth()->user();

        // Determine redirect based on role
        if ($user->hasRole(RoleEnum::ADMIN)) {
            $redirectUrl = route('dashboard', ['locale' => $locale]);
        } elseif ($user->hasRole(RoleEnum::DOCTOR)) {
            $redirectUrl = route('clinic.workspace', ['locale' => $locale]);
        } elseif ($user->hasRole(RoleEnum::PATIENT)) {
            $redirectUrl = route('patient.dashboard', ['locale' => $locale]);
        } else {
            $redirectUrl = route('home', ['locale' => $locale]);
        }

        // If AJAX / expects JSON, return a JSON payload with redirect
        if ($request->wantsJson() || $request->ajax() || $request->header('Accept') === 'application/json') {
            return new JsonResponse(['message' => __('translation.messages.registered'), 'redirect' => $redirectUrl], 200);
        }

        return redirect($redirectUrl);
    }
}
