<?php
declare(strict_types=1);

namespace App\Repositories\Examination;

use App\Models\EnumTemplates;
use Illuminate\Database\Eloquent\Builder;
use Slim\Container;

/**
 * Class ExaminationTemplateRepository
 * @package App\Repositories\Examination
 */
class ExaminationTemplateRepository
{

    /** @var Container */
    protected $container;

    /**
     * ExaminationTemplateRepository constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function loadActiveTemplates(): array
    {
        return $this->loadModel()->get()->where('active', '=', true)->toArray();
    }

    /**
     * @return Builder
     */
    protected function loadModel(): Builder
    {
        return EnumTemplates::with([]);
    }

}