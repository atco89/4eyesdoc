<?php
declare(strict_types=1);

namespace App\Repositories\ContactPerson;

use App\Models\TblContactPersonsModel;
use Illuminate\Database\Eloquent\Collection;
use Slim\Container;

/**
 * Class ContactPersonRepository
 * @package App\Repositories\ContactPerson
 */
class ContactPersonRepository
{

    /** @var Container */
    protected $container;

    /**
     * ContactPersonRepository constructor.
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
        return $this->loadModel(['dialNumber'])->where('active', '=', true)->toArray();
    }

    /**
     * @param array $relationships
     * @return Collection
     */
    protected function loadModel(array $relationships = []): Collection
    {
        return TblContactPersonsModel::with($relationships)->get();
    }

}