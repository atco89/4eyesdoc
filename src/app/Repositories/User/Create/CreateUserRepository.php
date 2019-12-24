<?php
declare(strict_types=1);

namespace App\Repositories\User\Create;

use App\Repositories\User\UserRepository;
use App\Repositories\User\UserValidator;
use Exception;
use Illuminate\Database\Capsule\Manager as DB;
use PDOException;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class CreateUserRepository
 * @package App\Repositories\User\Create
 */
class CreateUserRepository
{

    /** @var Container */
    protected $container;

    /**
     * CreateUserRepository constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @return bool
     * @throws Exception
     */
    public function run(Request $request): bool
    {
        $validator = new UserValidator($this->container);
        if ($validator->createValidation($request)->failed())
            return false;

        $userRepository = new UserRepository($this->container);
        if (!$userRepository->uniqueConstraint(null, $request)) {
            $this->container->flash->addMessageNow('error', 'Korisnik sa korisničkim imenom ili bojom postoji u bazi korisnika!');
            return false;
        }

        $connection = DB::connection();
        $followers = new Followers($this->container);
        $createUser = new CreateUser($this->container);
        try {
            $connection->beginTransaction();

            $createUser->run($connection, $request);

            $followers->following($createUser->id, $connection, $request);
            $followers->followers($createUser->id, $connection, $request);

            if ($createUser->isDoctor) {
                $userExaminations = new UserExaminations($this->container);
                $userExaminations->run($createUser->id, $connection, $request);
            }

            $connection->commit();
        } catch (PDOException|Exception $e) {
            $this->container->logger->writeLog(__METHOD__ . '|' . $e->getMessage());
            $this->container->flash->addMessageNow('error', 'Greška pri kreiranju rekorda! Kod greške: ' . $e->getCode());
            $connection->rollBack();
            return false;
        }
        $this->container->flash->addMessageNow('success', 'Operacija izvršena. Profil korisnika uspešno kreiran.');
        return true;
    }

}