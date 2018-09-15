<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class TblPatientsModel
 * @package App\Models
 */
class TblPatientsModel extends Model
{

    /** @var string */
    protected $table = 'tbl_patients';

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
    public function profession(): HasOne
    {
        return $this->hasOne(EnumProfessionsModel::class, 'id', 'profession_id');
    }

    /**
     * @return HasOne
     */
    public function contactPerson(): HasOne
    {
        return $this->hasOne(TblContactPersonsModel::class, 'id', 'contact_person_id');
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
        return $this->hasOne(TblUserModel::class, 'id', 'created_by');
    }

}