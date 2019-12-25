<?php
declare(strict_types=1);

namespace App;

use App\Models\EnumEyeDiseasesModel;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ICD10
 * @package App
 */
class ICD10 extends Pagination
{

    /**
     * ICD10 constructor.
     * @param int $currentPageNumber
     */
    public function __construct(int $currentPageNumber)
    {
        parent::__construct($currentPageNumber);
    }

    /**
     * @return array
     */
    public function pagination(): array
    {
        return $this->generatePagination();
    }

    /**
     * @return Builder
     */
    protected function queryBase(): Builder
    {
        return EnumEyeDiseasesModel::with([])->where('active', '=', true);
    }

    /**
     * @return array
     */
    public function queryData(): array
    {
        return $this->queryBase()->limit($this->limit)->offset($this->offset)->get()->toArray();
    }
}