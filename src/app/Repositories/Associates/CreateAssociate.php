<?php
declare(strict_types=1);

namespace App\Repositories\Associates;

use App\Models\TblAssociatesModel;
use Illuminate\Database\Connection;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class CreateAssociate
 * @package App\Repositories\Associates
 */
class CreateAssociate
{

    /** @var Container */
    protected $container;

    /**
     * CreateAssociate constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Connection $connection
     * @param Request $request
     */
    public function run(Connection &$connection, Request $request): void
    {
        $table = new TblAssociatesModel;
        $dataSet = [
            'name'           => $request->getParam('name'),
            'email'          => $request->getParam('email'),
            'dial_number_id' => $request->getParam('dial_number'),
            'phone_number'   => $request->getParam('phone_number'),
            'created_by'     => $_SESSION['id']
        ];
        $connection->table($table->getTable())->insert($dataSet);
    }

}