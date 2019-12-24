<?php
declare(strict_types=1);

namespace App\Excel;

use App\Repositories\User\UserRepository;
use Slim\Container;

/**
 * Class UserRegisterExport
 * @package App\Excel
 */
class UserRegisterExport extends ExcelExport
{

    /**
     * UserRegisterExport constructor.
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
        $userRepository = new UserRepository($this->container);
        $header = [
            'Ime',
            'Prezime',
            'Kategorija',
            'Zvanje',
            'Korisničko ime',
            'Marker',
            'E-adresa',
            'Broj telefona',
            'Izmenjeno',
            'Izmenio',
            'Status'
        ];
        $this->render($header, $userRepository->loadExcelReport());
    }
}
