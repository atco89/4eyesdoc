<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EnumDialNumbersModel
 * @package App\Models
 */
class EnumDialNumbersModel extends Model
{

    /** @var string */
    protected $table = 'enum_dial_numbers';
    /** @var bool */
    public $timestamps = false;

}