<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class TblUserMayDoExaminations
 * @package App\Models
 */
class TblUserMayDoExaminations extends Model
{

    /** @var string */
    protected $table = 'tbl_user_may_do_examinations';
    /** @var bool */
    public $timestamps = false;

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
    public function medicalExamination(): HasOne
    {
        return $this->hasOne(EnumMedicalExaminationsModel::class, 'id', 'medical_examination_id');
    }

    /**
     * @return HasOne
     */
    public function createdBy(): HasOne
    {
        return $this->hasOne(TblUserModel::class, 'id', 'created_by');
    }

}