<?php
declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\TblUserMayDoExaminations;
use Illuminate\Database\Eloquent\Builder;
use Slim\Container;

/**
 * Class UserMayDoExaminations
 * @package App\Repositories\User
 */
class UserMayDoExaminations
{

    /** @var Container */
    protected $container;

    /**
     * UserMayDoExaminations constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param int $id
     * @return array
     */
    public function findWhichExamsUserMayDo(int $id): array
    {
        return $this->loadModel()->get()
            ->where('active', '=', true)
            ->where('doctor_id', '=', $id)
            ->map(function ($row) {
                return $row->medical_examination_id;
            })->toArray();
    }

    /**
     * @param array $relations
     * @return Builder
     */
    protected function loadModel(array $relations = []): Builder
    {
        return TblUserMayDoExaminations::with($relations);
    }

}