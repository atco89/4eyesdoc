<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class TblAssociatesModel
 * @package App\Models
 */
class TblAssociatesModel extends Model
{

    /** @var string */
    protected $table = 'tbl_associates';
    /** @var bool */
    public $timestamps = false;

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

}