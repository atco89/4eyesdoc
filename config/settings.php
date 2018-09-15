<?php
declare(strict_types=1);

return [
    'settings' => [
        'owner'                             => '4eyesdoc',
        'examinationEditPeriod'             => 90,
        'examinationDuration'               => 15,
        'baseUrl'                           => 'http://localhost/4eyesdoc/public/',
        'loggerPath'                        => __DIR__ . '/../',
        'pdfHeaderImage'                    => __DIR__ . '/../public/media/images/report-header.jpg',
        'pdfFooterImage'                    => __DIR__ . '/../public/media/images/report-footer.jpg',
        'templatesPath'                     => __DIR__ . '/../app/Views/',
        'displayFooterImage'                => true,
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails'               => true,
        'db'                                => [
            'driver'    => 'mysql',
            'host'      => '127.0.0.1',
            'database'  => '4eyesdoc_rs',
            'username'  => 'root',
            'password'  => '',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => ''
        ]
    ]
];