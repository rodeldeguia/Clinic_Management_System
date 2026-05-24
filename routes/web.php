<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;



use App\Http\Controllers\Admin\MedicalStoreController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\DoctorManagementController;
use App\Http\Controllers\Admin\ReceptionistManagementController;
use App\Http\Controllers\Admin\PatientOversightController;
use App\Http\Controllers\Admin\AppointmentOversightController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\SecurityController;
use App\Http\Controllers\Doctor\DoctorDashboardController;
use App\Http\Controllers\Doctor\AppointmentHandlingController;
use App\Http\Controllers\Doctor\PatientCareController;
use App\Http\Controllers\Doctor\ScheduleManagementController;
use App\Http\Controllers\Doctor\DoctorFeedbackController;
use App\Http\Controllers\Receptionist\ReceptionistDashboardController;
use App\Http\Controllers\Receptionist\PatientRegistrationController;
use App\Http\Controllers\Receptionist\AppointmentSchedulingController;
use App\Http\Controllers\Receptionist\BillingController;
use App\Http\Controllers\Receptionist\RecordsController;
use App\Http\Controllers\Patient\PatientDashboardController;
use App\Http\Controllers\Patient\PatientAccountController;
use App\Http\Controllers\Patient\PatientAppointmentController;
use App\Http\Controllers\Patient\MedicalRecordsController;
use App\Http\Controllers\Patient\PatientBillingController;
use App\Http\Controllers\Patient\PatientFeedbackController;
use App\Http\Controllers\MedicalStore\MedicalStoreDashboardController;
use App\Http\Controllers\MedicalStore\PrescriptionHandlingController;
use App\Http\Controllers\MedicalStore\StockManagementController;
use App\Http\Controllers\MedicalStore\MedicalStoreBillingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Public Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/

// Home/Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Patient Registration (Public)
Route::get('/register/patient', [RegisterController::class, 'showPatientRegistrationForm'])->name('register.patient.form');
Route::post('/register/patient', [RegisterController::class, 'registerPatient'])->name('register.patient');

// Forgot Password Routes
Route::get('/forgot-password', [LoginController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [LoginController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [LoginController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [LoginController::class, 'resetPassword'])->name('password.update');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (All Roles)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Change Password (Common for all roles)
    Route::get('/change-password', [App\Http\Controllers\ProfileController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/change-password', [App\Http\Controllers\ProfileController::class, 'changePassword'])->name('password.update');

    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTES (Middleware: role:admin)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Doctor Management
        Route::prefix('doctors')->name('doctors.')->group(function () {
            Route::get('/', [DoctorManagementController::class, 'index'])->name('index');
            Route::get('/create', [DoctorManagementController::class, 'create'])->name('create');
            Route::post('/', [DoctorManagementController::class, 'store'])->name('store');
            Route::get('/{id}', [DoctorManagementController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [DoctorManagementController::class, 'edit'])->name('edit');
            Route::put('/{id}', [DoctorManagementController::class, 'update'])->name('update');
            Route::delete('/{id}', [DoctorManagementController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/deactivate', [DoctorManagementController::class, 'deactivate'])->name('deactivate');
            Route::post('/{id}/reactivate', [DoctorManagementController::class, 'reactivate'])->name('reactivate');
            Route::get('/performance/{id}', [DoctorManagementController::class, 'performance'])->name('performance');
            Route::get('/filter', [DoctorManagementController::class, 'filter'])->name('filter');
        });
        
        // Receptionist Management
        Route::prefix('receptionists')->name('receptionists.')->group(function () {
            Route::get('/', [ReceptionistManagementController::class, 'index'])->name('index');
            Route::get('/create', [ReceptionistManagementController::class, 'create'])->name('create');
            Route::post('/', [ReceptionistManagementController::class, 'store'])->name('store');
            Route::get('/{id}', [ReceptionistManagementController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [ReceptionistManagementController::class, 'edit'])->name('edit');
            Route::put('/{id}', [ReceptionistManagementController::class, 'update'])->name('update');
            Route::delete('/{id}', [ReceptionistManagementController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/suspend', [ReceptionistManagementController::class, 'suspend'])->name('suspend');
            Route::get('/activity-logs/{id}', [ReceptionistManagementController::class, 'activityLogs'])->name('activity-logs');
            Route::post('/{id}/assign-doctor', [ReceptionistManagementController::class, 'assignToDoctor'])->name('assign-doctor');
        });
        
        // Patient Oversight
        Route::prefix('patients')->name('patients.')->group(function () {
            Route::get('/', [PatientOversightController::class, 'index'])->name('index');
            Route::get('/{id}', [PatientOversightController::class, 'show'])->name('show');
            Route::get('/search', [PatientOversightController::class, 'search'])->name('search');
            Route::post('/{id1}/merge/{id2}', [PatientOversightController::class, 'merge'])->name('merge');
            Route::post('/{id}/flag', [PatientOversightController::class, 'flagSpecialCondition'])->name('flag');
            Route::get('/medical-history/{id}', [PatientOversightController::class, 'medicalHistory'])->name('medical-history');
            Route::get('/billing-history/{id}', [PatientOversightController::class, 'billingHistory'])->name('billing-history');
        });
        
        // Appointment Oversight
        Route::prefix('appointments')->name('appointments.')->group(function () {
            Route::get('/', [AppointmentOversightController::class, 'index'])->name('index');
            Route::get('/daily', [AppointmentOversightController::class, 'dailyView'])->name('daily');
            Route::get('/weekly', [AppointmentOversightController::class, 'weeklyView'])->name('weekly');
            Route::get('/monthly', [AppointmentOversightController::class, 'monthlyView'])->name('monthly');
            Route::get('/{id}', [AppointmentOversightController::class, 'show'])->name('show');
            Route::put('/{id}/status', [AppointmentOversightController::class, 'updateStatus'])->name('update-status');
            Route::post('/{id}/reassign', [AppointmentOversightController::class, 'reassignDoctor'])->name('reassign');
            Route::get('/statistics', [AppointmentOversightController::class, 'statistics'])->name('statistics');
        });
        
        // System Reports & Analytics
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportsController::class, 'index'])->name('index');
            Route::get('/patients', [ReportsController::class, 'patientReports'])->name('patients');
            Route::get('/patients/export', [ReportsController::class, 'exportPatientReport'])->name('patients.export');
            Route::get('/doctors', [ReportsController::class, 'doctorActivityReports'])->name('doctors');
            Route::get('/doctors/export', [ReportsController::class, 'exportDoctorReport'])->name('doctors.export');
            Route::get('/receptionists', [ReportsController::class, 'receptionistActivityReports'])->name('receptionists');
            Route::get('/receptionists/export', [ReportsController::class, 'exportReceptionistReport'])->name('receptionists.export');
            Route::get('/medicines', [ReportsController::class, 'medicineStockReports'])->name('medicines');
            Route::get('/medicines/export', [ReportsController::class, 'exportMedicineReport'])->name('medicines.export');
            Route::get('/financial', [ReportsController::class, 'financialReports'])->name('financial');
            Route::get('/financial/export', [ReportsController::class, 'exportFinancialReport'])->name('financial.export');
        });
        
        // Feedback & Communication
        Route::prefix('feedback')->name('feedback.')->group(function () {
            Route::get('/', [FeedbackController::class, 'index'])->name('index');
            Route::get('/{id}', [FeedbackController::class, 'show'])->name('show');
            Route::post('/{id}/respond', [FeedbackController::class, 'respond'])->name('respond');
            Route::post('/announcements', [FeedbackController::class, 'sendAnnouncement'])->name('announcements');
            Route::post('/notifications', [FeedbackController::class, 'sendNotification'])->name('notifications');
        });
        
        // Security & Access Control
        Route::prefix('security')->name('security.')->group(function () {
            Route::get('/roles', [SecurityController::class, 'roles'])->name('roles');
            Route::post('/roles/{role}/permissions', [SecurityController::class, 'updatePermissions'])->name('permissions.update');
            Route::get('/login-activity', [SecurityController::class, 'loginActivity'])->name('login-activity');
            Route::get('/audit-logs', [SecurityController::class, 'auditLogs'])->name('audit-logs');
            Route::post('/reset-password/{id}', [SecurityController::class, 'resetUserPassword'])->name('reset-password');
            Route::post('/unlock-account/{id}', [SecurityController::class, 'unlockAccount'])->name('unlock-account');
        });

        Route::prefix('medical-store')->name('medical-store.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\MedicalStoreController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\MedicalStoreController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\MedicalStoreController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [App\Http\Controllers\Admin\MedicalStoreController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\Admin\MedicalStoreController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\Admin\MedicalStoreController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle-status', [App\Http\Controllers\Admin\MedicalStoreController::class, 'toggleStatus'])->name('toggle-status');
    });

    });

    /*
    |--------------------------------------------------------------------------
    | DOCTOR ROUTES (Middleware: role:doctor)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');
        
        // Appointment Handling
        Route::prefix('appointments')->name('appointments.')->group(function () {
            Route::get('/', [AppointmentHandlingController::class, 'index'])->name('index');
            Route::get('/{id}', [AppointmentHandlingController::class, 'show'])->name('show');
            Route::post('/{id}/start', [AppointmentHandlingController::class, 'startConsultation'])->name('start');
            Route::post('/{id}/complete', [AppointmentHandlingController::class, 'completeConsultation'])->name('complete');
            Route::post('/{id}/confirm', [AppointmentHandlingController::class, 'confirm'])->name('confirm');
            Route::post('/{id}/cancel', [AppointmentHandlingController::class, 'cancel'])->name('cancel');
        });
        
        // Patient Care
        Route::prefix('patient-care')->name('patient-care.')->group(function () {
            Route::get('/medical-history/{patient_id}', [PatientCareController::class, 'medicalHistory'])->name('medical-history');
            Route::get('/treat/{appointment_id}', [PatientCareController::class, 'treatPatient'])->name('treat');
            Route::post('/treatment-record/{appointment_id}', [PatientCareController::class, 'updateTreatmentRecord'])->name('update-treatment');
            Route::post('/prescribe/{appointment_id}', [PatientCareController::class, 'prescribeMedicine'])->name('prescribe.store'); 
            Route::get('/prescribe/{appointment_id}', [PatientCareController::class, 'prescribeMedicine'])->name('prescribe');
            Route::get('/prescriptions', [PatientCareController::class, 'myPrescriptions'])->name('prescriptions');
        });
        
        // Schedule Management
        Route::prefix('schedule')->name('schedule.')->group(function () {
            Route::get('/', [ScheduleManagementController::class, 'index'])->name('index');
            Route::get('/view', [ScheduleManagementController::class, 'viewSchedule'])->name('view');
            Route::post('/availability', [ScheduleManagementController::class, 'updateAvailability'])->name('update-availability');
            Route::get('/slots', [ScheduleManagementController::class, 'availableSlots'])->name('slots');
        });
        
        // Feedback (View only)
        Route::get('/feedback', [DoctorFeedbackController::class, 'index'])->name('feedback');
        Route::get('/feedback/{id}', [DoctorFeedbackController::class, 'show'])->name('feedback.show');
    });

    /*
    |--------------------------------------------------------------------------
    | RECEPTIONIST ROUTES (Middleware: role:receptionist)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:receptionist'])->prefix('receptionist')->name('receptionist.')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [ReceptionistDashboardController::class, 'index'])->name('dashboard');
        
        // Patient Registration
        Route::prefix('patients')->name('patients.')->group(function () {
            Route::get('/register', [PatientRegistrationController::class, 'create'])->name('register');
            Route::post('/', [PatientRegistrationController::class, 'store'])->name('store');
            Route::get('/verify', [PatientRegistrationController::class, 'verifyForm'])->name('verify.form');
            Route::post('/verify', [PatientRegistrationController::class, 'verify'])->name('verify');
            Route::get('/{id}', [PatientRegistrationController::class, 'show'])->name('show');
        });
        
        // Appointment Scheduling
        Route::prefix('appointments')->name('appointments.')->group(function () {
            Route::get('/', [AppointmentSchedulingController::class, 'index'])->name('index');
            Route::get('/create', [AppointmentSchedulingController::class, 'create'])->name('create');
            Route::post('/', [AppointmentSchedulingController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [AppointmentSchedulingController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AppointmentSchedulingController::class, 'update'])->name('update');
            Route::delete('/{id}', [AppointmentSchedulingController::class, 'destroy'])->name('destroy');
            Route::get('/available-slots', [AppointmentSchedulingController::class, 'availableSlots'])->name('available-slots');
        });
        
       
        // Billing
Route::prefix('billing')->name('billing.')->group(function () {
        Route::get('/', [MedicalStoreBillingController::class, 'index'])->name('index');
        Route::post('/store', [MedicalStoreBillingController::class, 'store'])->name('store');
        Route::get('/{id}', [MedicalStoreBillingController::class, 'show'])->name('show');
        Route::post('/{id}/mark-paid', [MedicalStoreBillingController::class, 'markAsPaid'])->name('mark-paid');
        Route::get('/patient/{patient_id}/history', [MedicalStoreBillingController::class, 'patientHistory'])->name('patient-history');
    });
        
        // Records
        Route::prefix('records')->name('records.')->group(function () {
            Route::get('/patients', [RecordsController::class, 'patientRecords'])->name('patients');
            Route::get('/patients/{id}', [RecordsController::class, 'showPatientRecord'])->name('patients.show');
            Route::put('/patients/{id}', [RecordsController::class, 'updatePatientRecord'])->name('patients.update');
            Route::post('/patients/{id}/after-treatment', [RecordsController::class, 'updateAfterTreatment'])->name('after-treatment');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | PATIENT ROUTES (Middleware: role:patient)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:patient'])->prefix('patient')->name('patient.')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('dashboard');
        
        // Account Management
        Route::prefix('account')->name('account.')->group(function () {
            Route::get('/profile', [PatientAccountController::class, 'profile'])->name('profile');
            Route::get('/edit', [PatientAccountController::class, 'edit'])->name('edit');
            Route::put('/update', [PatientAccountController::class, 'update'])->name('update');
            Route::post('/upload-photo', [PatientAccountController::class, 'uploadPhoto'])->name('upload-photo');
        });
        
        // Appointment Management
        Route::prefix('appointments')->name('appointments.')->group(function () {
            Route::get('/', [PatientAppointmentController::class, 'index'])->name('index');
            Route::get('/create', [PatientAppointmentController::class, 'create'])->name('create');
            Route::post('/', [PatientAppointmentController::class, 'store'])->name('store');
            Route::get('/{id}', [PatientAppointmentController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [PatientAppointmentController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PatientAppointmentController::class, 'update'])->name('update');
            Route::delete('/{id}', [PatientAppointmentController::class, 'destroy'])->name('destroy');
            Route::get('/available-doctors', [PatientAppointmentController::class, 'availableDoctors'])->name('available-doctors');
            Route::get('/available-slots/{doctor_id}', [PatientAppointmentController::class, 'availableSlots'])->name('available-slots');
        });
        
        // Medical Records
        Route::prefix('medical-records')->name('medical-records.')->group(function () {
            Route::get('/', [MedicalRecordsController::class, 'index'])->name('index');
            Route::get('/{id}', [MedicalRecordsController::class, 'show'])->name('show');
            Route::get('/prescriptions', [MedicalRecordsController::class, 'prescriptions'])->name('prescriptions');
            Route::get('/prescriptions/{id}', [MedicalRecordsController::class, 'prescriptionDetails'])->name('prescription-details');
        });
        
        // Billing (View only)
        Route::prefix('billing')->name('billing.')->group(function () {
            Route::get('/', [PatientBillingController::class, 'index'])->name('index');
            Route::get('/{id}', [PatientBillingController::class, 'show'])->name('show');
            Route::post('/{id}/download', [PatientBillingController::class, 'downloadInvoice'])->name('download');
        });
        
        // Feedback
        Route::prefix('feedback')->name('feedback.')->group(function () {
            Route::get('/create/{appointment_id}', [PatientFeedbackController::class, 'create'])->name('create');
            Route::post('/', [PatientFeedbackController::class, 'store'])->name('store');
            Route::get('/my-feedback', [PatientFeedbackController::class, 'myFeedback'])->name('my-feedback');
            Route::get('/{id}/edit', [PatientFeedbackController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PatientFeedbackController::class, 'update'])->name('update');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | MEDICAL STORE ROUTES (Middleware: role:medical_store)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'role:medical_store'])->prefix('medical-store')->name('medical-store.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [MedicalStoreDashboardController::class, 'index'])->name('dashboard');
    
    // Prescriptions
    Route::prefix('prescriptions')->name('prescriptions.')->group(function () {
        Route::get('/', [PrescriptionHandlingController::class, 'index'])->name('index');
        Route::get('/pending', [PrescriptionHandlingController::class, 'pending'])->name('pending');
        Route::get('/dispensed', [PrescriptionHandlingController::class, 'dispensed'])->name('dispensed');
        Route::post('/{id}/dispense', [PrescriptionHandlingController::class, 'dispense'])->name('dispense');
        Route::get('/{id}', [PrescriptionHandlingController::class, 'show'])->name('show');
    });
    
    // Stock Management
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/', [StockManagementController::class, 'index'])->name('index');
        Route::get('/create', [StockManagementController::class, 'create'])->name('create');
        Route::post('/', [StockManagementController::class, 'store'])->name('store');
        Route::get('/low-stock', [StockManagementController::class, 'lowStock'])->name('low-stock');
        Route::get('/expired', [StockManagementController::class, 'expiredStock'])->name('expired');
        Route::get('/{id}/edit', [StockManagementController::class, 'edit'])->name('edit');
        Route::put('/{id}', [StockManagementController::class, 'update'])->name('update');
        Route::delete('/{id}', [StockManagementController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/add-stock', [StockManagementController::class, 'addStock'])->name('add-stock');
        Route::post('/{id}/remove-expired', [StockManagementController::class, 'removeExpired'])->name('remove-expired');
        Route::post('/bulk-remove-expired', [StockManagementController::class, 'bulkRemoveExpired'])->name('bulk-remove-expired');
        Route::get('/export', [StockManagementController::class, 'export'])->name('export');
    });
    
    // Billing - FIXED: No {prescription_id} in store route
    Route::prefix('billing')->name('billing.')->group(function () {
        Route::get('/', [MedicalStoreBillingController::class, 'index'])->name('index');
        Route::post('/store', [MedicalStoreBillingController::class, 'store'])->name('store');
        Route::get('/{id}', [MedicalStoreBillingController::class, 'show'])->name('show');
        Route::post('/{id}/mark-paid', [MedicalStoreBillingController::class, 'markAsPaid'])->name('mark-paid');
        Route::get('/patient/{patient_id}/history', [MedicalStoreBillingController::class, 'patientHistory'])->name('patient-history');
    });
});



});