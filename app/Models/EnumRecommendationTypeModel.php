<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EnumRecommendationTypeModel
 * @package App\Models
 */
class EnumRecommendationTypeModel extends Model
{

    /** @var string */
    protected $table = 'enum_recommendation_type';
    /** @var bool */
    public $timestamps = false;

}