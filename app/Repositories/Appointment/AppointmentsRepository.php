<?php
declare(strict_types=1);

namespace App\Repositories\Appointment;

use App\Models\TblAppointments;
use App\Repositories\User\Create\Followers;
use App\Repositories\User\FollowersRepository;
use DateInterval;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Slim\Container;

/**
 * Class AppointmentsRepository
 * @package App\Repositories\Appointment
 */
class AppointmentsRepository
{

    /** @var Container */
    protected $container;
    /** @var array */
    protected $following;

    /**
     * AppointmentsRepository constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->following = (new FollowersRepository($this->container))->findWhatUserFollows($_SESSION['id']);
    }

    /**
     * @param int $id
     * @return TblAppointments|null
     */
    public function loadById(int $id): ?TblAppointments
    {
        return $this->loadModel()->get()
            ->where('active', '=', true)
            ->where('id', '=', $id)
            ->first();
    }

    /**
     * @return array
     */
    public function fullCalendarAppointments(): array
    {
        return $this->loadModel(['patient', 'examinationStatus'])->get()
            ->where('active', '=', true)
            ->where('examinationStatus.treatment', '!=', 0)
            ->whereIn('doctor_id', $this->following)
            ->map(function ($row) {
                return [
                    'id'        => $row->id,
                    'title'     => $row->patient->name . ' ' . $row->patient->surname,
                    'start'     => $row->start_date_time,
                    'end'       => $row->end_date_time,
                    'editable'  => new DateTime($row->start_date_time) >= new DateTime,
                    'className' => $row->examinationStatus->style
                ];
            })->toArray();
    }

    /**
     * @param int $id
     * @return TblAppointments|null
     */
    public function findPatientId(int $id): ?TblAppointments
    {
        return $this->loadModel()->get()
            ->where('active', '=', true)
            ->where('id', '=', $id)
            ->first();
    }

    /**
     * @param int $editPeriod
     * @return array
     */
    public function loadAppointments(int $editPeriod): array
    {
        return $this->loadModel(['doctor', 'patient', 'examinationReport', 'medicalExamination', 'examinationStatus', 'updatedBy'])->get()
            ->where('active', '=', true)
            ->where('examinationStatus.treatment', '!=', 0)
            ->whereIn('doctor_id', $this->following)
            ->map(function ($row) use ($editPeriod) {
                return [
                    'id'                        => $row->id,
                    'examination_status_style'  => $row->examinationStatus->style,
                    'name'                      => $row->patient->name,
                    'surname'                   => $row->patient->surname,
                    'date_of_birth'             => $row->patient->date_of_birth,
                    'doctor_title'              => $row->doctor->groupRole->role->title,
                    'doctor_name'               => $row->doctor->name,
                    'doctor_surname'            => $row->doctor->surname,
                    'medical_examination_id'    => $row->medicalExamination->id,
                    'medical_examination_name'  => $row->medicalExamination->name,
                    'medical_examination_price' => $row->medicalExamination->price->where('active', '=', true)->first()->price,
                    'start_date_time'           => $row->start_date_time,
                    'report_type'               => $this->loadReportType($row->examinationReport),
                    'is_editable'               => $this->isEditable($row->examinationReport, $editPeriod)
                ];
            })->toArray();
    }

    /**
     * @param array $appointments
     * @param int $editPeriod
     * @return array
     */
    public function searchAppointments(array $appointments, int $editPeriod): array
    {
        return $this->loadModel(['doctor', 'patient', 'examinationReport', 'medicalExamination', 'examinationStatus', 'updatedBy'])->get()
            ->where('active', '=', true)
            ->whereIn('doctor_id', $this->following)
            ->whereIn('id', $appointments)
            ->map(function ($row) use ($editPeriod) {
                return [
                    'id'                        => $row->id,
                    'examination_status_style'  => $row->examinationStatus->style,
                    'name'                      => $row->patient->name,
                    'surname'                   => $row->patient->surname,
                    'date_of_birth'             => $row->patient->date_of_birth,
                    'doctor_title'              => $row->doctor->groupRole->role->title,
                    'doctor_name'               => $row->doctor->name,
                    'doctor_surname'            => $row->doctor->surname,
                    'medical_examination_id'    => $row->medicalExamination->id,
                    'medical_examination_name'  => $row->medicalExamination->name,
                    'medical_examination_price' => $row->medicalExamination->price->where('active', '=', true)->first()->price,
                    'start_date_time'           => $row->start_date_time,
                    'report_type'               => $this->loadReportType($row->examinationReport),
                    'is_editable'               => $this->isEditable($row->examinationReport, $editPeriod)
                ];
            })->toArray();
    }

    /**
     * @return array
     */
    public function loadExport(): array
    {
        return $this->loadModel(['doctor', 'patient', 'medicalExamination', 'updatedBy'])->get()
            ->where('active', '=', true)
            ->whereIn('doctor_id', $this->following)
            ->map(function ($row) {
                $dateOfBirth = empty($row->patient->date_of_birth) ? null : $row->patient->date_of_birth;
                $examinationPrice = $row->medicalExamination->price->where('active', '=', true)->first();
                return [
                    'id'              => $row->id,
                    'name'            => $row->patient->name,
                    'surname'         => $row->patient->surname,
                    'date_of_birth'   => $dateOfBirth,
                    'age'             => $dateOfBirth !== null ? (new DateTime)->diff(new DateTime($dateOfBirth))->y : null,
                    'doctor'          => $row->doctor->groupRole->role->title . ' ' . $row->doctor->name . ' ' . $row->doctor->surname,
                    'exam_name'       => $row->medicalExamination->name,
                    'exam_price'      => $examinationPrice['price'],
                    'start_date_time' => $row->start_date_time,
                    'updated_at'      => $row->updated_at,
                    'updated_by'      => $row->updatedBy->username
                ];
            })->toArray();
    }

    /**
     * @param Collection|null $examinationReport
     * @param int $editPeriod
     * @return bool|null
     */
    private function isEditable(?Collection $examinationReport, int $editPeriod): ?bool
    {
        return $examinationReport->where('active', '=', true)
            ->map(function ($row) use ($editPeriod) {
                if ($row->created_at === null)
                    return null;

                $createdAt = $row->created_at;
                $expiresAt = (new DateTime($row->created_at))->add(new DateInterval("P{$editPeriod}D"));

                return $expiresAt > $createdAt;
            })->first();
    }

    /**
     * @param Collection|null $examinationReport
     * @return null|string
     */
    private function loadReportType(?Collection $examinationReport): ?string
    {
        return $examinationReport->where('active', '=', true)
            ->map(function ($row) {
                if ($row->template_id === null)
                    return null;

                return $row->template->template_name;
            })->first();
    }

    /**
     * @param array $relations
     * @return Builder
     */
    protected function loadModel(array $relations = []): Builder
    {
        return TblAppointments::with($relations);
    }

}