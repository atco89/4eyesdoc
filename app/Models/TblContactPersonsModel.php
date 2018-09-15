<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class TblContactPersonsModel
 * @package App\Models
 */
class TblContactPersonsModel extends Model
{

    /** @var string */
    protected $table = 'tbl_contact_persons';

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
    public function updatedBy(): HasOne
    {
        return $this->hasOne(TblUserModel::class, 'id', 'updated_by');
    }

    /**
     * @return HasOne
     */
    public function createdBy(): HasOne
    {
        return $this->hasOne(TblUserModel::class, 'id', 'updated_by');
    }

}