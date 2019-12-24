<?php
declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\TblUserModel;
use Exception;
use Illuminate\Database\Capsule\Manager as DB;
use PDOException;
use Slim\Container;

/**
 * Class ManageStatus
 * @package App\Repositories\User
 */
class ManageStatus
{

    /** @var Container */
    protected $container;

    /**
     * ManageStatus constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param int $id
     * @param bool $status
     * @return bool
     */
    public function run(int $id, bool $status): bool
    {
        $tblUserModel = (new TblUserModel)->getTable();
        try {
            DB::connection()->table($tblUserModel)->where('id', '=', $id)->update([
                'active' => $status
            ]);
        } catch (PDOException|Exception $exception) {
            $this->container->logger->writeLog(__METHOD__ . '|' . $exception->getMessage());
            return false;
        }
        return true;
    }

}