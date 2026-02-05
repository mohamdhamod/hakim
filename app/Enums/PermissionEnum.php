<?php

namespace App\Enums;

/**
 * Permission definitions for Clinic Management System
 */
class PermissionEnum
{
    // User Management
    const USERS = "Users";
    const USERS_ADD = "Users Add";
    const USERS_DELETE = "Users Delete";
    const USERS_VIEW = "Users View";
    const USERS_UPDATE = "Users Update";

    // Settings Management
    const SETTING = "Setting";
    const SETTING_ADD = "Setting Add";
    const SETTING_DELETE = "Setting Delete";
    const SETTING_VIEW = "Setting View";
    const SETTING_UPDATE = "Setting Update";

    // Role Management
    const MANAGE_ROLES = "Manage roles";

    // Medical Specialties Management
    const MANAGE_SPECIALTIES = "Manage specialties";
    const MANAGE_SPECIALTIES_ADD = "Manage specialties Add";
    const MANAGE_SPECIALTIES_UPDATE = "Manage specialties Update";
    const MANAGE_SPECIALTIES_DELETE = "Manage specialties Delete";
    const MANAGE_SPECIALTIES_VIEW = "Manage specialties View";

    // Clinic Management (Admin)
    const MANAGE_CLINICS = "Manage clinics";
    const MANAGE_CLINICS_VIEW = "Manage clinics View";
    const MANAGE_CLINICS_APPROVE = "Manage clinics Approve";
    const MANAGE_CLINICS_REJECT = "Manage clinics Reject";

    // Patient Management (Doctor)
    const MANAGE_PATIENTS = "Manage patients";
    const MANAGE_PATIENTS_ADD = "Manage patients Add";
    const MANAGE_PATIENTS_VIEW = "Manage patients View";
    const MANAGE_PATIENTS_UPDATE = "Manage patients Update";
    const MANAGE_PATIENTS_DELETE = "Manage patients Delete";

    // Examination Management (Doctor)
    const MANAGE_EXAMINATIONS = "Manage examinations";
    const MANAGE_EXAMINATIONS_ADD = "Manage examinations Add";
    const MANAGE_EXAMINATIONS_VIEW = "Manage examinations View";
    const MANAGE_EXAMINATIONS_UPDATE = "Manage examinations Update";
    const MANAGE_EXAMINATIONS_DELETE = "Manage examinations Delete";
}
