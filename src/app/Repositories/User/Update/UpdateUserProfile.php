<?php
declare(strict_types=1);

namespace App\Repositories\User\Update;

use App\Repositories\User\Create\Followers;
use App\Repositories\User\Create\UserExaminations;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserValidator;
use Exception;
use Illuminate\Database\Capsule\Manager as DB;
use PDOException;
use Slim\Container;
use Slim\Flash\Messages;
use Slim\Http\Request;

/**
 * Class UpdateUserProfile
 * @package App\Repositories\User\Update
 */
class UpdateUserProfile
{

    /** @var Messages */
    protected $flash;
    /** @var Container */
    protected $container;

    /**
     * UpdateUserProfile constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->flash = $this->container->flash;
    }

    /**
     * @param int $id
     * @param Request $request
     * @return bool
     * @throws Exception
     */
    public function run(int $id, Request $request): bool
    {
        $validator = new UserValidator($this->container);
        if ($validator->updateValidation($request)->failed())
            return false;

        $userRepository = new UserRepository($this->container);

        if (!$userRepository->uniqueConstraint($id, $request)) {
            $this->flash->addMessageNow('error', 'Korisnik sa korisničkim imenom ili bojom postoji u bazi korisnika!');
            return false;
        }

        $connection = DB::connection();
        $followers = new Followers($this->container);
        $updateUser = new UpdateUser($id, $this->container);
        try {
            $connection->beginTransaction();

            $updateUser->run($connection, $request);
            $followers->following($updateUser->id, $connection, $request);
            $followers->followers($updateUser->id, $connection, $request);

            if ($updateUser->isDoctor) {
                $userExaminations = new UserExaminations($this->container);
                $userExaminations->run($id, $connection, $request);
            }

            $connection->commit();
        } catch (PDOException|Exception $e) {
            $this->container->logger->writeLog(__METHOD__ . '|' . $e->getMessage());
            $this->flash->addMessageNow('error', 'Greška pri kreiranju rekorda! Kod greške: ' . $e->getCode());
            $connection->rollBack();
            return false;
        }
        $this->flash->addMessageNow('success', 'Operacija izvršena, profil korisnika uspešno izmenjen.');
        return true;
    }

}