<?php
declare(strict_types=1);

namespace App\Repositories\DialNumbers;

use App\Models\EnumDialNumbersModel;
use Illuminate\Database\Eloquent\Collection;
use Slim\Container;

/**
 * Class DialNumberRepository
 * @package App\Repositories\DialNumbers
 */
class DialNumberRepository
{

    /** @var Container */
    protected $container;

    /**
     * DialNumberRepository constructor.
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
        return $this->loadModel()->where('active', '=', true)->toArray();
    }

    /**
     * @param array $relationships
     * @return Collection
     */
    protected function loadModel(array $relationships = []): Collection
    {
        return EnumDialNumbersModel::with($relationships)->get();
    }

}