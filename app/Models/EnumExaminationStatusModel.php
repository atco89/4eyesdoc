<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EnumExaminationStatusModel
 * @package App\Models
 */
class EnumExaminationStatusModel extends Model
{

    /** @var string */
    protected $table = 'enum_examination_status';
    /** @var bool */
    public $timestamps = false;

}