<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EnumEyeDiseasesModel
 * @package App\Models
 */
class EnumEyeDiseasesModel extends Model
{

    /** @var string */
    protected $table = 'enum_eye_diseases';
    /** @var bool */
    public $timestamps = false;

}