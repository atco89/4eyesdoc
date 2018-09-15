<?php
declare(strict_types=1);

namespace App\Excel;

use App\Repositories\Appointment\AppointmentsRepository;
use Slim\Container;

/**
 * Class AppointmentsRegisterExport
 * @package App\Excel
 */
class AppointmentsRegisterExport extends ExcelExport
{

    /**
     * AppointmentsRegisterExport constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $appointmentsRepository = new AppointmentsRepository($this->container);
        $header = [
            'šifra',
            'ime',
            'prezime',
            'datum_rođenja',
            'godina',
            'doktor',
            'naziv_pregleda',
            'cena_pregleda',
            'početak',
            'izmenjeno',
            'izmenio'
        ];
        $data = $appointmentsRepository->loadExport();
        $this->render($header, $data);
    }

}