<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class TblUserWorkSchedule
 * @package App\Models
 */
class TblUserWorkSchedule extends Model
{

    /** @var string */
    protected $table = 'tbl_user_work_schedule';

    /**
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(TblUserModel::class, 'id', 'user_id');
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