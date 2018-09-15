<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class TblUserModel
 * @package App\Models
 */
class TblUserModel extends Model
{

    /** @var string */
    protected $table = 'tbl_user';

    /**
     * @return HasOne
     */
    public function groupRole(): HasOne
    {
        return $this->hasOne(TblGroupRoleModel::class, 'id', 'group_role_id');
    }

    /**
     * @return HasOne
     */
    public function dialNumber(): HasOne
    {
        return $this->hasOne(EnumDialNumbersModel::class, 'id', 'dial_number_id');
    }

    /**
     * @return HasOne
     */
    public function createdBy(): HasOne
    {
        return $this->hasOne(TblUserModel::class, 'id', 'created_by');
    }

    /**
     * @return HasOne
     */
    public function updatedBy(): HasOne
    {
        return $this->hasOne(TblUserModel::class, 'id', 'updated_by');
    }

}