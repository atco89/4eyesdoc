<?php
declare(strict_types=1);

namespace App\Excel;

use App\Repositories\User\WorkScheduleRepository;
use Slim\Container;

/**
 * Class UserWorkScheduleExport
 * @package App\Excel
 */
class UserWorkScheduleExport extends ExcelExport
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
        $workScheduleRepository = new WorkScheduleRepository($this->container);
        $header = ['Ime', 'Prezime', 'Marker', 'Početak', 'Kraj', 'Sati', 'Izmenjeno', 'Izmenio'];
        $this->render($header, $workScheduleRepository->loadExcelReport());
    }
}