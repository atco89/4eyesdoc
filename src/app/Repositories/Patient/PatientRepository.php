<?php
declare(strict_types=1);

namespace App\Repositories\Patient;

use App\Models\TblPatientsModel;
use App\Repositories\Recommendation\RecommendationRepository;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Slim\Container;
use Slim\Http\Request;

/**
 * Class PatientRepository
 * @package App\Repositories\Patient
 */
class PatientRepository
{

    /**
     * @var Container
     */
    protected $container;

    /**
     * PatientRepository constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function loadActiveRegister(): array
    {
        return $this->loadModel(['dialNumber', 'updatedBy'])->get()
            ->where('active', '=', true)
            ->toArray();
    }

    /**
     * @param int $id
     * @return TblPatientsModel|null
     */
    public function loadById(int $id): ?TblPatientsModel
    {
        $recommendationRepository = new RecommendationRepository($this->container);
        return $this->loadModel()->get()
            ->where('active', '=', true)
            ->where('id', '=', $id)
            ->map(function ($row) use ($id, $recommendationRepository) {
                $row['recommendation'] = $recommendationRepository->loadById($id);
                return $row;
            })->first();
    }

    /**
     * @param int|null $id
     * @param Request $request
     * @return bool
     */
    public function uniqueConstraint(?int $id, Request $request): bool
    {
        $numberOfRows = $this->loadModel()->where('active', '=', true)
            ->where('id', '!=', $id)
            ->where('name', '=', $request->getParam('name'))
            ->where('surname', '=', $request->getParam('surname'))
            ->where('sex_id', '=', $request->getParam('sex'))
            ->where('dial_number_id', '=', $request->getParam('dialNumber'))
            ->where('phone_number', '=', $request->getParam('phoneNumber'))
            ->count();
        return $numberOfRows === 0;
    }

    /**
     * @return array
     */
    public function loadExcelExport(): array
    {
        return $this->loadModel(['dialNumber', 'profession', 'contactPerson', 'updatedBy'])->get()->map(function ($row) {
            $dateOfBirth = $row->date_of_birth;
            $age = empty($dateOfBirth) ? null : (new DateTime($dateOfBirth))->diff(new DateTime)->y;
            return [
                'id'                 => $row->id,
                'name'               => $row->name,
                'surname'            => $row->surname,
                'sex'                => $row->sex_id === 0 ? 'M' : 'Ž',
                'dateOfBirth'        => $dateOfBirth,
                'age'                => $age !== null && $age > 0 ? $age : null,
                'email'              => $row->email,
                'phone'              => "({$row->dialNumber->dial_number}{$row->phone_number})",
                'profession'         => $row->profession->profession_name,
                'contactPersonPhone' => "({$row->contactPerson->dialNumber->dial_number}{$row->contactPerson->phone_number})",
                'contactPerson'      => $row->contactPerson->name . ' ' . $row->contactPerson->surname,
                'updated_at'         => $row->updated_at,
                'updated_by'         => $row->updatedBy->username
            ];
        })->toArray();
    }

    /**
     * @param array $relations
     * @return Builder
     */
    protected function loadModel(array $relations = []): Builder
    {
        return TblPatientsModel::with($relations);
    }
}
