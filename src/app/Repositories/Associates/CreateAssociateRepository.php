<?php
declare(strict_types=1);

namespace App\Repositories\Associates;

use Exception;
use Illuminate\Database\Capsule\Manager as DB;
use PDOException;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class CreateAssociateRepository
 * @package App\Repositories\Associates
 */
class CreateAssociateRepository extends AssociatesRepository
{

    /**
     * CreateAssociateRepository constructor.
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
        $validation = new AssociateValidation($this->container);
        if ($validation->run($request)->failed())
            return false;

        if ($this->numberOfIdenticalRecords($request) > 0) {
            $this->container->flash->addMessageNow('error', 'Kontakt osoba postoji u bazi podataka. Možete je pronaći preko broja telefona.');
            return false;
        }

        $createAssociate = new CreateAssociate($this->container);
        $connection = DB::connection();
        try {
            $createAssociate->run($connection, $request);
        } catch (PDOException|Exception $e) {
            $this->container->logger->writeLog(__METHOD__ . '|' . $e->getMessage());
            $this->container->flash->addMessageNow('error', 'Greška pri kreiranju rekorda! Kod greške: ' . $e->getCode());
            return false;
        }
        $this->container->flash->addMessageNow('success', 'Operacija izvršena. Saradnik uspešno kreiran.');
        return true;
    }

    /**
     * @param Request $request
     * @return int
     */
    private function numberOfIdenticalRecords(Request $request): int
    {
        $name = $request->getParam('name');
        $dialNumberId = intval($request->getParam('dial_number'));
        $phoneNumber = $request->getParam('phone_number');

        return $this->loadModel()->where('name', '=', $name)
            ->where('dial_number_id', '=', $dialNumberId)
            ->where('phone_number', '=', $phoneNumber)
            ->count();
    }

}