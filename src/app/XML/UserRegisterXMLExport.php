<?php
declare(strict_types=1);

namespace App\XML;

use App\Repositories\User\UserRepository;
use Slim\Container;

/**
 * Class UserRegisterXMLExport
 * @package App\XML
 */
class UserRegisterXMLExport extends XMLExport
{

    /**
     * UserRegisterXMLExport constructor.
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
        $userRepository = new UserRepository($this->container);
        foreach ($userRepository->loadExcelReport() as $row) {
            $data[] = [
                'Ime'           => $row['name'],
                'Prezime'       => $row['surname'],
                'Kategorija'    => $row['group'],
                'Zvanje'        => $row['role'],
                'KorisnickoIme' => $row['username'],
                'RGBMarker'     => $row['color'],
                'EMail'         => $row['email'],
                'BrojTelefona'  => $row['phoneNumber'],
                'Izmenjeno'     => $row['updatedAt'],
                'Izmenio'       => $row['updatedBy'],
                'Status'        => $row['active'],
            ];
        }
        $this->render('Zaposleni', 'Profil', $data);
    }
}
