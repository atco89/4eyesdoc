<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EnumProfessionsModel
 * @package App\Models
 */
class EnumProfessionsModel extends Model
{

    /** @var string */
    protected $table = 'enum_professions';
    /** @var bool */
    public $timestamps = false;

}