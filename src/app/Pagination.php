<?php
declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class Pagination
 * @package App
 */
abstract class Pagination
{

    /**
     * @var int
     */
    protected $numberOfPages;
    /**
     * @var int
     */
    protected $currentPageNumber;
    /**
     * @var int
     */
    protected $offset;
    /**
     * @var int
     */
    protected $limit = 10;
    /**
     * @var int
     */
    protected $numberOfRecords;

    /**
     * Pagination constructor.
     * @param int $currentPageNumber
     */
    public function __construct(int $currentPageNumber)
    {
        $this->currentPageNumber = $currentPageNumber;
        $this->offset = $this->loadOffset();
        $this->numberOfRecords = $this->queryBase()->count();
        $this->numberOfPages = $this->loadNumberOfPages();
    }

    /**
     * @return int
     */
    private function loadOffset(): int
    {
        return $this->limit * $this->currentPageNumber;
    }

    /**
     * @return Builder
     */
    abstract protected function queryBase(): Builder;

    /**
     * @return int
     */
    private function loadNumberOfPages(): int
    {
        return intval(ceil($this->numberOfRecords / $this->limit));
    }

    /**
     * @return array
     */
    abstract public function queryData(): array;

    /**
     * @return int
     */
    public function getNumberOfPages(): int
    {
        return $this->numberOfPages === 0 ? 0 : $this->numberOfPages - 1;
    }

    /**
     * @return array
     */
    protected function generatePagination(): array
    {
        if (in_array($this->currentPageNumber, range(0, $this->numberOfPages))) {
            switch (true) {
                case $this->currentPageNumber - 2 <= 0:
                    return range(0, 4);
                    break;
                case $this->currentPageNumber + 2 > $this->numberOfPages:
                    return range($this->currentPageNumber - 2, $this->numberOfPages - 1);
                    break;
                default:
                    return range($this->currentPageNumber - 2, $this->currentPageNumber + 2);
                    break;
            }
        }
        return range(0, 4);
    }
}
