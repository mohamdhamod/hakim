# Hakim Clinics API Documentation

## Overview

Base URL: `{APP_URL}/api`

All API requests must include locale in the URL: `/{locale}/api/...`

Supported locales: `en`, `ar`

## Authentication

### API Token Authentication

Include the API token in the Authorization header:

```
Authorization: Bearer {token}
```

## Endpoints

### Examinations

#### List Examinations (DataTables)
```
GET /{locale}/clinic/examinations
X-Requested-With: XMLHttpRequest
Authorization: Bearer {token}
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "examination_number": "EX-2026-0001",
      "patient_name": "John Doe",
      "patient_file_number": "PAT-001",
      "examination_date_formatted": "2026-02-06 10:30",
      "status_badge": "<span class=\"badge bg-success\">Completed</span>",
      "actions": "..."
    }
  ]
}
```

#### Create Examination
```
POST /{locale}/clinic/examinations
Content-Type: application/json
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "patient_id": 1,
  "examination_date": "2026-02-06T10:30",
  "chief_complaint": "Headache",
  "present_illness_history": "Patient reports persistent headache for 3 days",
  "temperature": 37.2,
  "blood_pressure_systolic": 120,
  "blood_pressure_diastolic": 80,
  "pulse_rate": 72,
  "respiratory_rate": 16,
  "weight": 70.5,
  "height": 175,
  "oxygen_saturation": 98,
  "physical_examination": "Normal examination",
  "diagnosis": "Tension headache",
  "icd_code": "G44.2",
  "treatment_plan": "Rest and hydration",
  "prescriptions": "Paracetamol 500mg, 3 times daily",
  "lab_tests_ordered": "CBC",
  "imaging_ordered": "None",
  "follow_up_date": "2026-02-13",
  "follow_up_notes": "Follow up in one week",
  "doctor_notes": "Patient advised to reduce stress",
  "status": "scheduled"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Examination created successfully",
  "examination": {
    "id": 1,
    "examination_number": "EX-2026-0001",
    "patient_id": 1,
    "clinic_id": 1,
    "user_id": 1,
    "examination_date": "2026-02-06T10:30:00.000000Z",
    "chief_complaint": "Headache",
    "diagnosis": "Tension headache",
    "icd_code": "G44.2",
    "status": "scheduled"
  },
  "redirect": "/en/clinic/examinations/1"
}
```

#### Show Examination
```
GET /{locale}/clinic/examinations/{id}
Authorization: Bearer {token}
```

**Response:** HTML view with examination details

#### Update Examination
```
PUT /{locale}/clinic/examinations/{id}
Content-Type: application/json
Authorization: Bearer {token}
```

**Request Body:** Same as Create Examination (without patient_id)

**Response:**
```json
{
  "success": true,
  "message": "Examination updated successfully",
  "redirect": "/en/clinic/examinations/1"
}
```

#### Mark Examination as Completed
```
POST /{locale}/clinic/examinations/{id}/complete
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "message": "Examination marked as completed"
}
```

#### Print Examination
```
GET /{locale}/clinic/examinations/{id}/print
Authorization: Bearer {token}
```

**Response:** Printable HTML view

### Patients

#### List Patients (DataTables)
```
GET /{locale}/clinic/patients
X-Requested-With: XMLHttpRequest
Authorization: Bearer {token}
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "file_number": "PAT-001",
      "full_name": "John Doe",
      "age": 35,
      "gender_display": "Male",
      "phone": "1234567890",
      "last_visit": "2026-02-06",
      "examinations_count": 5
    }
  ]
}
```

#### Create Patient
```
POST /{locale}/clinic/patients
Content-Type: application/json
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "first_name": "John",
  "last_name": "Doe",
  "gender": "male",
  "date_of_birth": "1990-01-01",
  "phone": "1234567890",
  "email": "john.doe@example.com",
  "address": "123 Main St",
  "allergies": "Penicillin",
  "chronic_diseases": "Hypertension",
  "medical_history": "Previous surgery in 2020",
  "family_history": "Father had diabetes",
  "emergency_contact_name": "Jane Doe",
  "emergency_contact_phone": "0987654321",
  "emergency_contact_relation": "Wife",
  "notes": "Prefers morning appointments"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Patient created successfully",
  "patient": {
    "id": 1,
    "file_number": "PAT-001",
    "full_name": "John Doe",
    "clinic_id": 1
  },
  "redirect": "/en/clinic/patients/PAT-001"
}
```

#### Show Patient
```
GET /{locale}/clinic/patients/{file_number}
Authorization: Bearer {token}
```

**Response:** HTML view with patient details and examination history

#### Update Patient
```
PUT /{locale}/clinic/patients/{file_number}
Content-Type: application/json
Authorization: Bearer {token}
```

**Request Body:** Same as Create Patient

**Response:**
```json
{
  "success": true,
  "message": "Patient updated successfully"
}
```

#### Search Patients
```
GET /{locale}/clinic/patients/search?q={query}
Authorization: Bearer {token}
```

**Response:**
```json
{
  "results": [
    {
      "id": 1,
      "file_number": "PAT-001",
      "full_name": "John Doe",
      "phone": "1234567890"
    }
  ]
}
```

### Appointments

#### List Appointments (DataTables)
```
GET /{locale}/clinic/appointments
X-Requested-With: XMLHttpRequest
Authorization: Bearer {token}
```

**Query Parameters:**
- `status`: Filter by status (pending, confirmed, cancelled, completed)

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "patient_name": "John Doe",
      "patient_phone": "1234567890",
      "appointment_date": "2026-02-07T10:00:00",
      "appointment_time": "10:00 AM",
      "notes": "First visit",
      "status": "pending"
    }
  ]
}
```

#### Update Appointment Status
```
POST /{locale}/clinic/appointments/{id}/status
Content-Type: application/json
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "status": "confirmed"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Appointment status updated"
}
```

#### Cancel Appointment
```
POST /{locale}/clinic/appointments/{id}/cancel
Content-Type: application/json
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "cancellation_reason": "Patient cannot make it"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Appointment cancelled"
}
```

## Error Responses

### Validation Error (422)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field_name": [
      "Error message"
    ]
  }
}
```

### Unauthorized (401)
```json
{
  "message": "Unauthenticated."
}
```

### Forbidden (403)
```json
{
  "success": false,
  "message": "You do not have permission to access this resource."
}
```

### Not Found (404)
```json
{
  "message": "Resource not found."
}
```

### Server Error (500)
```json
{
  "success": false,
  "message": "An error occurred while processing your request."
}
```

## Rate Limiting

- API requests are rate limited to 60 requests per minute
- OTP requests are limited to 5 requests per minute per email

## Pagination

For paginated endpoints, use:
- `page`: Page number
- `per_page`: Items per page (default: 15, max: 100)

## Filtering & Sorting

For DataTables endpoints:
- `search[value]`: Search term
- `order[0][column]`: Column index to sort
- `order[0][dir]`: Sort direction (asc/desc)

## Status Codes

- `200 OK`: Successful request
- `201 Created`: Resource created successfully
- `204 No Content`: Successful request with no content
- `400 Bad Request`: Invalid request
- `401 Unauthorized`: Authentication required
- `403 Forbidden`: Permission denied
- `404 Not Found`: Resource not found
- `422 Unprocessable Entity`: Validation error
- `429 Too Many Requests`: Rate limit exceeded
- `500 Internal Server Error`: Server error
