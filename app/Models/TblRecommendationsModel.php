<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class TblRecommendationsModel
 * @package App\Models
 */
class TblRecommendationsModel extends Model
{

    /** @var string */
    protected $table = 'tbl_recommendations';
    /** @var bool */
    public $timestamps = false;

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
    public function recommendationType(): HasOne
    {
        return $this->hasOne(EnumRecommendationTypeModel::class, 'id', 'recommendation_type_id');
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