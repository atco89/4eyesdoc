<?php
declare(strict_types=1);

namespace App\Repositories\Examination;

use App\Models\TblExaminationReports;
use Illuminate\Database\Eloquent\Builder;
use Slim\Container;

/**
 * Class ExaminationReport
 * @package App\Repositories\Examination
 */
class ExaminationReport
{

    /** @var Container */
    protected $container;

    /**
     * ExaminationReport constructor.
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
    public function loadCardboardById(int $id): array
    {
        return $this->loadModel(['appointment', 'appointment.medicalExamination', 'appointment.doctor.groupRole.role'])->get()
            ->where('active', '=', true)
            ->where('appointment.patient.id', '=', $id)
            ->toArray();
    }

    /**
     * @param int $id
     * @return TblExaminationReports|null
     */
    public function loadReportById(int $id): ?TblExaminationReports
    {
        $examinationICDRepository = new ExaminationICDRepository($this->container);
        return $this->loadModel(['appointment.patient', 'appointment.doctor.groupRole.role', 'createdBy'])->get()
            ->where('active', '=', true)
            ->where('appointment_id', '=', $id)
            ->map(function ($row) use ($examinationICDRepository) {
                $row->anamnesis = empty($row->anamnesis) ? null : htmlspecialchars_decode($row->anamnesis);
                $row->icd10 = $examinationICDRepository->loadDeasesByID($row->id);
                $row->icd10IDs = $examinationICDRepository->loadDeasesIDsByID($row->id);
                return $row;
            })->first();
    }

    /**
     * @param array $relations
     * @return Builder
     */
    protected function loadModel(array $relations = []): Builder
    {
        return TblExaminationReports::with($relations);
    }

}