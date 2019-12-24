<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class TblGroupRoleModel
 * @package App\Models
 */
class TblGroupRoleModel extends Model
{

    /** @var string */
    protected $table = 'tbl_group_role';
    /** @var bool */
    public $timestamps = false;

    /**
     * @return HasOne
     */
    public function group(): HasOne
    {
        return $this->hasOne(EnumGroupsModel::class, 'id', 'group_id');
    }

    /**
     * @return HasOne
     */
    public function role(): HasOne
    {
        return $this->hasOne(EnumRolesModel::class, 'id', 'role_id');
    }

}