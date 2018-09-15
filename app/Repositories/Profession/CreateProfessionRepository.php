<?php
declare(strict_types=1);

namespace App\Repositories\Profession;

use Exception;
use Illuminate\Database\Capsule\Manager as DB;
use PDOException;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class CreateProfessionRepository
 * @package App\Repositories\Profession
 */
class CreateProfessionRepository extends ProfessionRepository
{

    /**
     * CreateProfessionRepository constructor.
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
        $validation = new ProfessionValidation($this->container);
        if ($validation->run($request)->failed())
            return false;

        if ($this->numberOfIdenticalRecords($request->getParam('profession')) > 0) {
            $this->container->flash->addMessageNow('error', 'Profesija postoji u bazi podataka. Pokušajte sa drugim nazivom.');
            return false;
        }

        $createProfession = new CreateProfession($this->container);
        $connection = DB::connection();
        try {
            $createProfession->run($connection, $request);
        } catch (PDOException|Exception $e) {
            $this->container->logger->writeLog(__METHOD__ . '|' . $e->getMessage());
            $this->container->flash->addMessageNow('error', 'Greška pri kreiranju rekorda! Kod greške: ' . $e->getCode());
            return false;
        }
        $this->container->flash->addMessageNow('success', 'Operacija izvršena. Profesija uspešno kreirana.');
        return true;
    }

    /**
     * @param string $professionName
     * @return int
     */
    private function numberOfIdenticalRecords(string $professionName): int
    {
        return $this->loadModel()->where('profession_name', '=', $professionName)->count();
    }

}