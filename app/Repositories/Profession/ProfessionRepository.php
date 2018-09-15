<?php
declare(strict_types=1);

namespace App\Repositories\Profession;

use App\Models\EnumProfessionsModel;
use Illuminate\Database\Eloquent\Builder;
use Slim\Container;

/**
 * Class ProfessionRepository
 * @package App\Repositories\Profession
 */
class ProfessionRepository
{

    /** @var Container */
    protected $container;

    /**
     * ProfessionRepository constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function loadActive(): array
    {
        return $this->loadModel()->get()->where('active', '=', true)->toArray();
    }

    /**
     * @param array $relations
     * @return Builder
     */
    protected function loadModel(array $relations = []): Builder
    {
        return EnumProfessionsModel::with($relations);
    }

}