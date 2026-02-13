<?php

namespace App\Http\Controllers\Clinic;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Mail\ClinicStaffInvitationMail;
use App\Models\ClinicUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ClinicTeamController extends Controller
{
    /**
     * Invite a new team member.
     */
    public function invite(Request $request)
    {
        $user = auth()->user();
        $clinic = $user->clinic;

        if (!$clinic) {
            return back()->with('error', __('translation.clinic.not_found'));
        }

        $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('clinic_users', 'user_id')->where(function ($query) use ($clinic) {
                    // This will be handled differently
                }),
            ],
            'name' => 'required|string|max:255',
        ], [
            'email.required' => __('translation.validation.email_required'),
            'email.email' => __('translation.validation.email_invalid'),
            'name.required' => __('translation.validation.name_required'),
        ]);

        $email = $request->input('email');
        $name = $request->input('name');

        // Check if user already exists
        $existingUser = User::where('email', $email)->first();

        // Check if already a team member
        if ($existingUser) {
            $alreadyMember = ClinicUser::where('clinic_id', $clinic->id)
                ->where('user_id', $existingUser->id)
                ->exists();

            if ($alreadyMember) {
                return back()->with('error', __('translation.clinic.already_team_member'));
            }

            // Check if user is a doctor (can't be an editor)
            if ($existingUser->isDoctor()) {
                return back()->with('error', __('translation.clinic.doctor_cannot_be_editor'));
            }
        }

        // Generate random password
        $password = Str::random(10);

        if ($existingUser) {
            // User exists, just add to clinic
            $invitee = $existingUser;
            
            // Assign role if not already assigned
            if (!$invitee->hasRole(RoleEnum::CLINIC_PATIENT_EDITOR)) {
                $invitee->assignRole(RoleEnum::CLINIC_PATIENT_EDITOR);
            }
        } else {
            // Create new user
            $invitee = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'email_verified_at' => now(), // Auto verify
            ]);

            // Assign role
            $invitee->assignRole(RoleEnum::CLINIC_PATIENT_EDITOR);
        }

        // Create clinic_user record
        ClinicUser::create([
            'clinic_id' => $clinic->id,
            'user_id' => $invitee->id,
            'is_active' => true,
            'invited_at' => now(),
            'accepted_at' => $existingUser ? now() : null,
        ]);

        // Send invitation email
        Mail::to($email)->send(new ClinicStaffInvitationMail(
            $clinic,
            $user,
            $email,
            $password,
            !$existingUser,
            $invitee
        ));

        return back()->with('success', __('translation.clinic.invitation_sent', ['email' => $email]));
    }

    /**
     * Toggle team member active status.
     */
    public function toggleStatus(Request $request,$lang,  ClinicUser $clinicUser)
    {
        $user = auth()->user();
        $clinic = $user->clinic;

        if (!$clinic || $clinicUser->clinic_id !== $clinic->id) {
            return back()->with('error', __('translation.clinic.unauthorized'));
        }

        $clinicUser->update([
            'is_active' => !$clinicUser->is_active,
        ]);

        $status = $clinicUser->is_active 
            ? __('translation.clinic.member_activated') 
            : __('translation.clinic.member_deactivated');

        return back()->with('success', $status);
    }

    /**
     * Remove a team member.
     */
    public function remove($lang, ClinicUser $clinicUser)
    {
        $user = auth()->user();
        $clinic = $user->clinic;

        if (!$clinic || $clinicUser->clinic_id !== $clinic->id) {
            return back()->with('error', __('translation.clinic.unauthorized'));
        }

        $memberUser = $clinicUser->user;
        $clinicUser->delete();

        // Check if user has other clinic associations
        $otherClinics = ClinicUser::where('user_id', $memberUser->id)->count();
        
        // If no other clinics, remove the role
        if ($otherClinics === 0) {
            $memberUser->removeRole(RoleEnum::CLINIC_PATIENT_EDITOR);
        }

        return back()->with('success', __('translation.clinic.member_removed'));
    }

    /**
     * Resend invitation email.
     */
    public function resendInvitation($lang, ClinicUser $clinicUser)
    {
        $user = auth()->user();
        $clinic = $user->clinic;

        if (!$clinic || $clinicUser->clinic_id !== $clinic->id) {
            return back()->with('error', __('translation.clinic.unauthorized'));
        }

        $memberUser = $clinicUser->user;
        
        // Generate new password
        $password = Str::random(10);
        $memberUser->update([
            'password' => Hash::make($password),
        ]);

        // Send invitation email
        Mail::to($memberUser->email)->send(new ClinicStaffInvitationMail(
            $clinic,
            $user,
            $memberUser->email,
            $password,
            false,
            $memberUser
        ));

        return back()->with('success', __('translation.clinic.invitation_resent', ['email' => $memberUser->email]));
    }
}
