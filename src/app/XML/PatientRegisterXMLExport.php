<?php
declare(strict_types=1);

namespace App\XML;

use App\Repositories\Patient\PatientRepository;
use Slim\Container;

/**
 * Class PatientRegisterXMLExport
 * @package App\XML
 */
class PatientRegisterXMLExport extends XMLExport
{

    /**
     * PatientRegisterXMLExport constructor.
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
        $data = [];
        $patientRepository = new PatientRepository($this->container);
        foreach ($patientRepository->loadExcelExport() as $row) {
            $data[] = [
                'BrojKartona'        => $row['id'],
                'Ime'                => $row['name'],
                'Prezime'            => $row['surname'],
                'Pol'                => $row['sex'],
                'DatumRodjenja'      => $row['dateOfBirth'],
                'Godine'             => $row['age'],
                'EMail'              => $row['email'],
                'BrojTelefona'       => $row['phone'],
                'Profesija'          => $row['profession'],
                'KontaktOsoba'       => $row['contactPerson'],
                'TelefonKontakOsobe' => $row['contactPersonPhone'],
                'Izmenjeno'          => $row['updated_at']->format('Y-m-d H:i:s'),
                'Izmenio'            => $row['updated_by'],
            ];
        }
        $this->render('Pacijenti', 'Pacijent', $data);
    }
}