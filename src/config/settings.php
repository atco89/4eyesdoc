<?php
declare(strict_types=1);

return [
    'settings' => [
        'owner'                             => '4eyesdoc',
        'examinationEditPeriod'             => 90,
        'examinationDuration'               => 15,
        'baseUrl'                           => 'https://localhost/public/',
        'loggerPath'                        => __DIR__ . '/4eyesdoc/',
        'pdfHeaderImage'                    => __DIR__ . '/../public/media/images/report-header.jpg',
        'pdfFooterImage'                    => __DIR__ . '/../public/media/images/report-footer.jpg',
        'templatesPath'                     => __DIR__ . '/../app/Views/',
        'displayFooterImage'                => true,
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails'               => true,
        'db'                                => [
            'driver'    => 'mysql',
            'host'      => 'database',
            'database'  => '4eyesdoc_rs_db_1',
            'username'  => 'root',
            'password'  => 'Admin123$%^&',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => ''
        ]
    ]
];