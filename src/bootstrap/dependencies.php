<?php
declare(strict_types=1);

use App\Controllers\AppointmentsController;
use App\Controllers\AssociatesController;
use App\Controllers\AuthController;
use App\Controllers\ContactPersonController;
use App\Controllers\ErrorPagesController;
use App\Controllers\ExaminationController;
use App\Controllers\PatientController;
use App\Controllers\ProfessionController;
use App\Controllers\ReportsController;
use App\Controllers\RoleController;
use App\Controllers\UserController;
use App\Controllers\WorkScheduleController;
use App\Excel\AppointmentsRegisterExport;
use App\Excel\PatientRegisterExport;
use App\Excel\UserRegisterExport;
use App\Excel\UserWorkScheduleExport;
use App\Middleware\AuthMiddleware;
use App\Middleware\DownloadExaminationParams;
use App\Middleware\ExaminationPreviewIDMiddleware;
use App\Middleware\ExcelMiddleware;
use App\Middleware\RequestMiddleware;
use App\Middleware\XMLMiddleware;
use App\Repositories\Examination\ExaminationReportPDF;
use App\Repositories\Logger\Logger;
use App\Validation\Validator;
use App\XML\AppointmentsRegisterXMLExport;
use App\XML\PatientRegisterXMLExport;
use App\XML\UserRegisterXMLExport;
use App\XML\UserWorkScheduleXMLExport;
use Illuminate\Database\Capsule\Manager;
use Slim\Container;
use Slim\Flash\Messages;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use Twig\TwigFilter;

/* @var Container $container */
$container = $app->getContainer();

$capsule = new Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

/* ========== application ========= */

$container['db'] = function () use ($capsule): Manager {
    return $capsule;
};

$container['flash'] = function () {
    return new Messages;
};

$container['view'] = function (Container $container): Twig {
    /** @var Twig $view */
    $view = new Twig($container->settings['templatesPath'], [
        'cache' => false
    ]);

    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new TwigExtension($container['router'], $basePath));

    $view->getEnvironment()->addGlobal('base_url', $container['settings']['baseUrl']);
    $view->getEnvironment()->addGlobal('current_url', $container['request']->getUri());
    $view->getEnvironment()->addGlobal('flash', $container->flash);
    $view->getEnvironment()->addGlobal('owner', $container->settings['owner']);
    $view->getEnvironment()->addGlobal('name', isset($_SESSION['name']) ? $_SESSION['name'] : null);
    $view->getEnvironment()->addGlobal('surname', isset($_SESSION['surname']) ? $_SESSION['surname'] : null);
    $view->getEnvironment()->addGlobal('username', isset($_SESSION['username']) ? $_SESSION['username'] : null);
    $view->getEnvironment()->addGlobal('roleId', isset($_SESSION['role_id']) ? $_SESSION['role_id'] : null);
    $view->getEnvironment()->addGlobal('role', isset($_SESSION['role_title']) ? $_SESSION['role_title'] : null);

    $view->getEnvironment()->addFilter(new TwigFilter('age', function (?string $dateOfBirth) {
        if (empty($dateOfBirth))
            return null;

        $age = (new DateTime)->diff(new DateTime($dateOfBirth))->y;
        return $age > 0 ? $age : null;
    }));

    $view->getEnvironment()->addFilter(new TwigFilter('number_format', function (?string $number) {
        if (empty($number))
            return null;

        return empty($number) ? null : number_format(floatval($number), 2);
    }));

    $view->getEnvironment()->addFilter(new TwigFilter('br2nl', function (?string $comment) {
        return empty($comment) ? null : preg_replace('/<br\\s*?\/??>/i', '', $comment);
    }));

    return $view;
};

$container['validator'] = function (Container $container): Validator {
    return new Validator($container);
};

$container['logger'] = function (Container $container): Logger {
    return new Logger($container);
};

/* ========== middleware ========== */

$container['AuthMiddleware'] = function (Container $container): AuthMiddleware {
    return new AuthMiddleware($container);
};

$container['RequestMiddleware'] = function (Container $container): RequestMiddleware {
    return new RequestMiddleware($container);
};

$container['ExcelMiddleware'] = function (Container $container): ExcelMiddleware {
    return new ExcelMiddleware($container);
};

$container['XMLMiddleware'] = function (Container $container): XMLMiddleware {
    return new XMLMiddleware($container);
};

$container['ExaminationPreviewIDMiddleware'] = function (Container $container): ExaminationPreviewIDMiddleware {
    return new ExaminationPreviewIDMiddleware($container);
};

$container['DownloadExaminationParams'] = function (Container $container): DownloadExaminationParams {
    return new DownloadExaminationParams($container);
};

/* ========== controllers ========== */

$container['AuthController'] = function (Container $container): AuthController {
    return new AuthController($container);
};

$container['AppointmentsController'] = function (Container $container): AppointmentsController {
    return new AppointmentsController($container);
};

$container['ExaminationController'] = function (Container $container): ExaminationController {
    return new ExaminationController($container);
};

$container['PatientController'] = function (Container $container): PatientController {
    return new PatientController($container);
};

$container['UserController'] = function (Container $container): UserController {
    return new UserController($container);
};

$container['WorkScheduleController'] = function (Container $container): WorkScheduleController {
    return new WorkScheduleController($container);
};

$container['AssociatesController'] = function (Container $container): AssociatesController {
    return new AssociatesController($container);
};

$container['ContactPersonController'] = function (Container $container): ContactPersonController {
    return new ContactPersonController($container);
};

$container['ProfessionController'] = function (Container $container): ProfessionController {
    return new ProfessionController($container);
};

$container['RoleController'] = function (Container $container): RoleController {
    return new RoleController($container);
};

$container['ErrorPagesController'] = function (Container $container): ErrorPagesController {
    return new ErrorPagesController($container);
};

$container['ExaminationReportPDF'] = function (Container $container): ExaminationReportPDF {
    return new ExaminationReportPDF($container);
};

/* ========== excel ========== */

$container['UserRegisterExport'] = function (Container $container): UserRegisterExport {
    return new UserRegisterExport($container);
};

$container['PatientRegisterExport'] = function (Container $container): PatientRegisterExport {
    return new PatientRegisterExport($container);
};

$container['UserWorkScheduleExport'] = function (Container $container): UserWorkScheduleExport {
    return new UserWorkScheduleExport($container);
};

$container['AppointmentsRegisterExport'] = function (Container $container): AppointmentsRegisterExport {
    return new AppointmentsRegisterExport($container);
};

/* ========== xml ========== */

$container['UserRegisterXMLExport'] = function (Container $container): UserRegisterXMLExport {
    return new UserRegisterXMLExport($container);
};

$container['PatientRegisterXMLExport'] = function (Container $container): PatientRegisterXMLExport {
    return new PatientRegisterXMLExport($container);
};

$container['UserWorkScheduleXMLExport'] = function (Container $container): UserWorkScheduleXMLExport {
    return new UserWorkScheduleXMLExport($container);
};

$container['AppointmentsRegisterXMLExport'] = function (Container $container): AppointmentsRegisterXMLExport {
    return new AppointmentsRegisterXMLExport($container);
};

/* ========== reports ========== */
$container['ReportsController'] = function (Container $container): ReportsController {
    return new ReportsController($container);
};