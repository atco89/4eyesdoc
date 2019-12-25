<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class TblExaminationReportsICDModel
 * @package App\Models
 */
class TblExaminationReportsICDModel extends Model
{

    /**
     * @var string
     */
    protected $table = 'tbl_examination_reports_icd';
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return HasOne
     */
    public function examinationReport(): HasOne
    {
        return $this->hasOne(EnumMedicalExaminationsModel::class, 'id', 'examination_report_id');
    }

    /**
     * @return HasOne
     */
    public function eyeDiseases(): HasOne
    {
        return $this->hasOne(EnumEyeDiseasesModel::class, 'id', 'eye_diseases_id');
    }
}
