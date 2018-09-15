<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class EnumMedicalExaminationsModel
 * @package App\Models
 */
class EnumMedicalExaminationsModel extends Model
{

    /** @var string */
    protected $table = 'enum_medical_examinations';
    /** @var bool */
    public $timestamps = false;

    /**
     * @return HasMany
     */
    public function price(): HasMany
    {
        return $this->hasMany(TblExaminationPriceModel::class, 'medical_examination_id', 'id');
    }

}