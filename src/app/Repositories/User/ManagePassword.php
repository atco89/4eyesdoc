<?php
declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\TblUserModel;
use Exception;
use Illuminate\Database\Capsule\Manager as DB;
use PDOException;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class ManagePassword
 * @package App\Repositories\User
 */
class ManagePassword
{

    /** @var Container */
    protected $container;

    /**
     * ManagePassword constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function run(Request $request): bool
    {
        $table = new TblUserModel;
        $validator = new UserValidator($this->container);
        if ($validator->passwordValidation($request)->failed())
            return false;

        $connection = DB::connection();
        try {
            $oldPassword = $request->getParam("oldPassword");
            $newPassword = $request->getParam("newPassword");

            if ($oldPassword === $newPassword) {
                $this->container->flash->addMessageNow('error', 'Nova lozinka mora biti različita od stare!');
                return false;
            }

            $userExists = $connection->table($table->getTable())->get()->filter(function ($row) use ($oldPassword) {
                return $row->id === $_SESSION['id'] && $row->password === sha1($oldPassword);
            })->count();

            if ($userExists === 0) {
                $this->container->flash->addMessageNow('error', 'Uneli ste pogrešnu lozinku!');
                return false;
            }

            $connection->table($table->getTable())->where('id', $_SESSION['id'])->update([
                'password' => sha1($newPassword)
            ]);
        } catch (PDOException|Exception $e) {
            $this->container->logger->writeLog(__METHOD__ . '|' . $e->getMessage());
            $this->container->flash->addMessageNow('error', 'Greška pri kreiranju rekorda! Kod greške: ' . $e->getCode());
            return false;
        }
        $this->container->flash->addMessageNow('success', 'Operacija izvršena. Uspešno ste promenili lozinku.');
        return true;
    }

}