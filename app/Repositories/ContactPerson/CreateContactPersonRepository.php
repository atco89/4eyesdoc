<?php
declare(strict_types=1);

namespace App\Repositories\ContactPerson;

use Exception;
use Illuminate\Database\Capsule\Manager as DB;
use PDOException;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class CreateContactPersonRepository
 * @package App\Repositories\ContactPerson
 */
class CreateContactPersonRepository extends ContactPersonRepository
{

    /**
     * CreateContactPersonRepository constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function run(Request $request): bool
    {
        $validation = new ContactPersonValidation($this->container);
        if ($validation->run($request)->failed())
            return false;

        if ($this->numberOfIdenticalRecords($request) > 0) {
            $this->container->flash->addMessageNow('error', 'Kontakt osoba postoji u bazi podataka. Možete je pronaći preko broja telefona.');
            return false;
        }

        $createContactPerson = new CreateContactPerson($this->container);
        $connection = DB::connection();
        try {
            $createContactPerson->run($connection, $request);
            $this->container->flash->addMessageNow('success', 'Operacija izvršena. Kontakt osoba uspešno kreirana.');
        } catch (PDOException|Exception $e) {
            $this->logger->writeLog(__METHOD__ . '|' . $e->getMessage());
            $this->container->flash->addMessageNow('error', 'Greška pri kreiranju rekorda! Kod greške: ' . $e->getCode());
            return false;
        }
        return true;
    }

    /**
     * @param Request $request
     * @return int
     */
    private function numberOfIdenticalRecords(Request $request): int
    {
        $name = $request->getParam('name');
        $surname = $request->getParam('surname');
        $dialNumberId = intval($request->getParam('dial_number'));
        $phoneNumber = $request->getParam('phone_number');

        return $this->loadModel()->where('name', '=', $name)
            ->where('surname', '=', $surname)
            ->where('dial_number', '=', $dialNumberId)
            ->where('phone_number', '=', $phoneNumber)
            ->count();
    }

}