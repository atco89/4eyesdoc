<?php
declare(strict_types=1);

namespace App\Repositories\ContactPerson;

use App\Models\TblContactPersonsModel;
use Illuminate\Database\Connection;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class CreateContactPerson
 * @package App\Repositories\ContactPerson
 */
class CreateContactPerson
{

    /** @var Container */
    protected $container;

    /**
     * CreateContactPerson constructor.
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
        $table = new TblContactPersonsModel;
        $dataSet = [
            'name'           => $request->getParam('name'),
            'surname'        => $request->getParam('surname'),
            'dial_number_id' => $request->getParam('dial_number'),
            'phone_number'   => $request->getParam('phone_number'),
            'updated_by'     => $_SESSION['id'],
            'created_by'     => $_SESSION['id']
        ];
        $connection->table($table->getTable())->insert($dataSet);
    }

}