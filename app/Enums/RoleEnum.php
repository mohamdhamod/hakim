<?php

namespace App\Enums;

/**
 * Role definitions for Clinic Management System
 */
class RoleEnum
{
    const ADMIN = "Admin";
    const DOCTOR = "Doctor";
    const PATIENT = "Patient";

    const ALL = [
        self::ADMIN,
        self::DOCTOR,
        self::PATIENT,
    ];
}
