<?php
declare(strict_types=1);

// ======================================== AUTH ROUTES =============================================

$app->get('/', 'AuthController:index')->setName('login.index');
$app->post('/auth/sign-in', 'AuthController:signIn')->setName('auth.signIn');
$app->get('/auth/sign-out', 'AuthController:signOut')->setName('auth.signOut');

// ======================================= AUTHENTICATED ============================================

$app->group(null, function () use ($app) {

    // -------------------- appointments --------------------
    $app->get('/appointments/schedule', 'AppointmentsController:index')->setName('appointment.index');
    $app->any('/appointments/schedule/update/{id}', 'AppointmentsController:updateSchedule');
    $app->any('/appointments/schedule/delete/{id}', 'AppointmentsController:delete')->setName('appointment.delete');
    $app->get('/appointments/register', 'AppointmentsController:register')->setName('appointment.register');
    $app->any('/appointments/register/search', 'AppointmentsController:search')->setName('appointment.search');

    // -------------------- examination --------------------
    $app->group(null, function () use ($app) {
        $app->any('/examinations/examination/{templateId}/{id}', 'ExaminationController:index');
    })->add('ExaminationPreviewIDMiddleware')->add('DownloadExaminationParams');
    $app->any('/examinations/edit-examination/{id}', 'ExaminationController:edit')->setName('examination.edit');

    // -------------------- patient --------------------
    $app->get('/patients/cardboard', 'PatientController:index')->setName('patient.index');
    $app->any('/patients/cardboard/search', 'PatientController:search')->setName('patient.search');
    $app->any('/patients/cardboard/create', 'PatientController:create')->setName('patient.create');
    $app->any('/patients/cardboard/view/{id}', 'PatientController:edit')->setName('patient.edit');

    // -------------------- user --------------------
    $app->any('/administration/user/register', 'UserController:index')->setName('user.index');
    $app->any('/administration/user/create', 'UserController:create')->setName('user.create');
    $app->any('/administration/user/edit/{id}', 'UserController:edit')->setName('user.edit');
    $app->any('/administration/user/active/{id}/{status}', 'UserController:manageStatus')->setName('user.delete');
    $app->any('/administration/user/password', 'UserController:managePassword')->setName('user.password');

    // -------------------- work schedule --------------------
    $app->any('/administration/user/schedule', 'WorkScheduleController:index')->setName('schedule.index');
    $app->any('/administration/user/schedule/search', 'WorkScheduleController:search')->setName('schedule.search');
    $app->any('/administration/user/schedule/delete/{id}', 'WorkScheduleController:delete')->setName('schedule.remove');
    $app->any('/administration/user/schedule/modal', 'WorkScheduleController:deleteScheduleConfirmation');

    // -------------------- partial --------------------
    $app->any('/partial/appointments/schedule', 'AppointmentsController:schedule');
    $app->any('/partial/appointments/expired', 'AppointmentsController:expired');
    $app->any('/partial/appointments/available-doctors', 'WorkScheduleController:availableDoctors');
    $app->any('/partial/contact-person/modal', 'ContactPersonController:index');
    $app->any('/partial/profession/modal', 'ProfessionController:index');
    $app->any('/partial/associate/modal', 'AssociatesController:index');
    $app->get('/partial/roles/{id}', 'RoleController:roleByGroup');
    $app->get('/partial/profession/select', 'ProfessionController:select');
    $app->get('/partial/contact-person/select', 'ContactPersonController:select');
    $app->get('/partial/associates/select', 'AssociatesController:select');

    // -------------------- api --------------------
    $app->get('/api/examinations/all', 'AppointmentsController:all');
    $app->post('/api/examinations/updateOnEvent', 'AppointmentsController:updateOnEvent');
    $app->post('/api/examination/preview/save', 'ExaminationController:saveExaminationForPreview');
    $app->get('/api/examination/preview/download', 'ExaminationController:downloadExamination')->setName('preview.download');

    // -------------------- export to pdf --------------------
    $app->get('/export/pdf/examination-report/{id}', 'ExaminationReportPDF:read')->setName('examination.pdf');

    // -------------------- export to excel --------------------
    $app->group(null, function () use ($app) {
        $app->get('/export/excel/users', 'UserRegisterExport:run')->setName('excel.users');
        $app->get('/export/excel/schedule', 'UserWorkScheduleExport:run')->setName('excel.schedule');
        $app->get('/export/excel/patients', 'PatientRegisterExport:run')->setName('excel.patients');
        $app->get('/export/excel/appointments', 'AppointmentsRegisterExport:run')->setName('excel.appointments');
    })->add('ExcelMiddleware');

})->add('AuthMiddleware')->add('RequestMiddleware');

$app->any('/error/404', 'ErrorPagesController:page404')->setName('error.404');
$app->any('/error/405', 'ErrorPagesController:page405')->setName('error.405');