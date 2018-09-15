<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class TblExaminationPriceModel
 * @package App\Models
 */
class TblExaminationPriceModel extends Model
{

    /** @var string */
    protected $table = 'tbl_examination_price';
    /** @var bool */
    public $timestamps = false;

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