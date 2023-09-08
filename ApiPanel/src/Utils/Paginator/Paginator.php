<?php
declare(strict_types=1);

namespace App\Utils\Paginator;

class Paginator
{
    private int $currentPage;
    private int $prevPage;
    private int $nextPage;
    private int $firstPage;
    private int $lastPage;

    public function __construct(int $currentPage, int $prevPage, int $nextPage, int $firstPage, int $lastPage)
    {
        $this->currentPage = $currentPage;
        $this->prevPage = $prevPage;
        $this->nextPage = $nextPage;
        $this->firstPage = $firstPage;
        $this->lastPage = $lastPage;
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @return int
     */
    public function getPrevPage(): int
    {
        return $this->prevPage;
    }

    /**
     * @return int
     */
    public function getNextPage(): int
    {
        return $this->nextPage;
    }

    /**
     * @return int
     */
    public function getFirstPage(): int
    {
        return $this->firstPage;
    }

    /**
     * @return int
     */
    public function getLastPage(): int
    {
        return $this->lastPage;
    }
}