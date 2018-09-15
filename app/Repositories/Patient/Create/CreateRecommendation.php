<?php
declare(strict_types=1);

namespace App\Repositories\Patient\Create;

use App\Models\TblRecommendationsModel;
use Illuminate\Database\Connection;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class CreateRecommendation
 * @package App\Repositories\Patient\Create
 */
class CreateRecommendation
{

    /** @var int */
    public $id;
    /** @var Container */
    protected $container;

    /**
     * CreateRecommendation constructor.
     * @param int $id
     * @param Container $container
     */
    public function __construct(int $id, Container $container)
    {
        $this->id = $id;
        $this->container = $container;
    }

    /**
     * @param Connection $connection
     * @param Request $request
     */
    public function run(Connection &$connection, Request $request): void
    {
        $connection->table((new TblRecommendationsModel)->getTable())
            ->where('id', '=', $this->id)
            ->update([
                'active' => false
            ]);

        $recommendationType = $request->getParam('recommendationType');
        $dataSet = [
            'patient_id'             => $this->id,
            'recommendation_type_id' => empty($recommendationType) ? 4 : $recommendationType,
            'associate_id'           => $request->getParam('associate'),
            'updated_by'             => $_SESSION['id'],
            'created_by'             => $_SESSION['id']
        ];
        $connection->table((new TblRecommendationsModel)->getTable())->insert($dataSet);
    }

}