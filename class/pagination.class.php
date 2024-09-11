<?php

class Pagination {
    private int $currentPage = 1;
    private int $totalPages;
    private int $itemsPerPage = 10;
    private int $totalItems;

    public function __construct(int $totalItems) {
        $this->totalItems = $totalItems;
        $this->calculateTotalPages();
    }

    private function calculateTotalPages() {
        $this->totalPages = ceil($this->totalItems / $this->itemsPerPage);
    }

    public function setCurrentPage(int $currentPage) {
        if ($currentPage > 0 && $currentPage <= $this->totalPages) {
            $this->currentPage = $currentPage;
        }
    }

    public function getCurrentPage(): int {
        if (!isset ($_GET['page']) ) {  
            $page = 1;  
        } else {  
            $page = $_GET['page'];  
        } 
        return $page;
    }

    public function getOffset(): int {
        return ($this->getCurrentPage() - 1) * $this->itemsPerPage;
    }

    public function getLimit(): int {
        return $this->itemsPerPage;
    }

    public function getTotalPages(): int {
        return $this->totalPages;
    }
}

?>
