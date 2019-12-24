<?php
declare(strict_types=1);

namespace App\XML;

use App\Repositories\User\WorkScheduleRepository;
use Slim\Container;

/**
 * Class UserWorkScheduleXMLExport
 * @package App\XML
 */
class UserWorkScheduleXMLExport extends XMLExport
{

    /**
     * UserWorkScheduleExport constructor.
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
        $workScheduleRepository = new WorkScheduleRepository($this->container);
        foreach ($workScheduleRepository->loadExcelReport() as $row) {
            $data[] = [
                'Ime'            => $row['name'],
                'Prezime'        => $row['surname'],
                'RGBMarker'      => $row['color'],
                'PocetakSmene'   => $row['startDateTime'],
                'KrajSmene'      => $row['endDateTime'],
                'BrojRadnihSati' => $row['hours'],
                'Izmenjeno'      => $row['updatedAt']->format('Y-m-d H:i:s'),
                'Izmenio'        => $row['updatedBy'],
            ];
        }
        $this->render('RasporedRada', 'Raspored', $data);
    }
}