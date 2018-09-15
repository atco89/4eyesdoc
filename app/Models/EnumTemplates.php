<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EnumTemplates
 * @package App\Models
 */
class EnumTemplates extends Model
{

    /** @var string */
    protected $table = 'enum_templates';
    /** @var bool */
    public $timestamps = false;

}