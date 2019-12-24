<?php
declare(strict_types=1);

namespace App\Repositories\Profession;

use App\Models\EnumProfessionsModel;
use Illuminate\Database\Connection;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class CreateProfession
 * @package App\Repositories\Profession
 */
class CreateProfession
{

    /** @var int */
    protected $id;
    /** @var Container */
    protected $container;

    /**
     * CreateProfession constructor.
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
        $table = new EnumProfessionsModel;
        $dataSet = [
            'profession_name' => $request->getParam('profession')
        ];
        $connection->table($table->getTable())->insert($dataSet);
    }
}