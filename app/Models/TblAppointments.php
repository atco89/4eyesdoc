<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class TblAppointments
 * @package App\Models
 */
class TblAppointments extends Model
{

    /** @var string */
    protected $table = 'tbl_appointments';

    /**
     * @return HasOne
     */
    public function patient(): HasOne
    {
        return $this->hasOne(TblPatientsModel::class, 'id', 'patient_id');
    }

    /**
     * @return HasOne
     */
    public function medicalExamination(): HasOne
    {
        return $this->hasOne(EnumMedicalExaminationsModel::class, 'id', 'medical_examination_id');
    }

    /**
     * @return HasOne
     */
    public function doctor(): HasOne
    {
        return $this->hasOne(TblUserModel::class, 'id', 'doctor_id');
    }

    /**
     * @return HasOne
     */
    public function examinationStatus(): HasOne
    {
        return $this->hasOne(EnumExaminationStatusModel::class, 'id', 'examination_status_id');
    }

    /**
     * @return HasMany
     */
    public function examinationReport(): HasMany
    {
        return $this->hasMany(TblExaminationReports::class, 'appointment_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function updatedBy(): HasOne
    {
        return $this->hasOne(TblUserModel::class, 'id', 'updated_by');
    }

    /**
     * @return HasOne
     */
    public function createdBy(): HasOne
    {
        return $this->hasOne(TblUserModel::class, 'id', 'created_by');
    }

}