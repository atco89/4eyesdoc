<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EnumGroupsModel
 * @package App\Models
 */
class EnumGroupsModel extends Model
{

    /** @var string */
    protected $table = 'enum_groups';
    /** @var bool */
    public $timestamps = false;

}