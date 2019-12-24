<?php
declare(strict_types=1);

namespace App\Excel;

use App\Repositories\Patient\PatientRepository;
use Slim\Container;

/**
 * Class PatientRegisterExport
 * @package App\Excel
 */
class PatientRegisterExport extends ExcelExport
{

    /**
     * PatientRegisterExport constructor.
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
        $patientRepository = new PatientRepository($this->container);
        $header = [
            'Br. Kartona',
            'Ime',
            'Prezime',
            'Pol',
            'Datum rođenja',
            'Godine',
            'E-adresa',
            'Broj telefona',
            'Profesija',
            'Telefon K. osobe',
            'Kontakt osoba',
            'Izmenjeno',
            'Izmenio'
        ];
        $data = $patientRepository->loadExcelExport();
        $this->render($header, $data);
    }
}
