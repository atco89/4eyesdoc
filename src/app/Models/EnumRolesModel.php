<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EnumRolesModel
 * @package App\Models
 */
class EnumRolesModel extends Model
{

    /** @var string */
    protected $table = 'enum_roles';
    /** @var bool */
    public $timestamps = false;

}