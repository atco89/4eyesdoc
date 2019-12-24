<?php
declare(strict_types=1);

namespace App\XML;

use App\Repositories\Appointment\AppointmentsRepository;
use Slim\Container;

/**
 * Class AppointmentsRegisterXMLExport
 * @package App\XML
 */
class AppointmentsRegisterXMLExport extends XMLExport
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
        $data = [];
        $appointmentsRepository = new AppointmentsRepository($this->container);
        foreach ($appointmentsRepository->loadExport() as $row) {
            $data[] = [
                'Sifra'         => $row['id'],
                'Ime'           => $row['name'],
                'Prezime'       => $row['surname'],
                'DatumRodjenja' => $row['date_of_birth'],
                'Godina'        => $row['age'],
                'Doktor'        => $row['doctor'],
                'NazivPregleda' => $row['exam_name'],
                'CenaPregleda'  => $row['exam_price'],
                'Pocetak'       => $row['start_date_time'],
                'Izmenjeno'     => $row['updated_at']->format('Y-m-d H:i:s'),
                'Izmenio'       => $row['updated_by'],
            ];
        }
        $this->render('Pregledi', 'Pregled', $data);
    }
}