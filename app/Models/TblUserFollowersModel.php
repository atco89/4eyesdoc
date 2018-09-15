<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class TblUserFollowersModel
 * @package App\Models
 */
class TblUserFollowersModel extends Model
{

    /** @var string */
    protected $table = 'tbl_user_followers';
    /** @var bool */
    public $timestamps = false;

    /**
     * @return HasOne
     */
    public function follower(): HasOne
    {
        return $this->hasOne(TblUserModel::class, 'id', 'follower_id');
    }

    /**
     * @return HasOne
     */
    public function following(): HasOne
    {
        return $this->hasOne(TblUserModel::class, 'id', 'following_id');
    }

    /**
     * @return HasOne
     */
    public function createdBy(): HasOne
    {
        return $this->hasOne(TblUserModel::class, 'id', 'created_by');
    }

}