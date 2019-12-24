<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class TblExaminationReports
 * @package App\Models
 */
class TblExaminationReports extends Model
{

    /** @var string */
    protected $table = 'tbl_examination_reports';
    /** @var bool */
    public $timestamps = false;

    /**
     * @return HasOne
     */
    public function template(): HasOne
    {
        return $this->hasOne(EnumTemplates::class, 'id', 'template_id');
    }

    /**
     * @return HasOne
     */
    public function appointment(): HasOne
    {
        return $this->hasOne(TblAppointments::class, 'id', 'appointment_id');
    }

    /**
     * @return HasOne
     */
    public function createdBy(): HasOne
    {
        return $this->hasOne(TblUserModel::class, 'id', 'created_by');
    }

}