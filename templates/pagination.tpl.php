<?php
      declare(strict_types = 1); 
      require_once(__DIR__ . '/../class/pagination.class.php')

?>
<?php
function drawPagination(Pagination $pagination){
    echo '<ul class="pagination">';

    if ($pagination->getCurrentPage() > 1) {
        echo '<li><a href="?page=' . ($pagination->getCurrentPage() - 1) . '">Previous</a></li>';
    }

    $totalPages = $pagination->getTotalPages();
    $currentPage = $pagination->getCurrentPage();
    $numPagesToShow = 7; 
    $numPagesBeforeAfter = floor(($numPagesToShow - 3) / 2); 
    if ($totalPages <= $numPagesToShow) {
        for ($i = 1; $i <= $totalPages; $i++) {
            echoPageLink($i, $currentPage);
        }
    } else {
        echoPageLink(1, $currentPage);

        if ($currentPage > ($numPagesBeforeAfter + 2)) {
            echo '<li><span>...</span></li>';
        }

        $start = max(2, $currentPage - $numPagesBeforeAfter);
        $end = min($totalPages - 1, $currentPage + $numPagesBeforeAfter);
        for ($i = $start; $i <= $end; $i++) {
            echoPageLink($i, $currentPage);
        }

        if ($currentPage < ($totalPages - $numPagesBeforeAfter - 1)) {
            echo '<li><span>...</span></li>';
        }

        echoPageLink($totalPages, $currentPage);
    }

    if ($currentPage < $totalPages) {
        echo '<li><a href="?page=' . ($currentPage + 1) . '">Next</a></li>';
    } else {
        echo '<li class="disabled"><span>Next</span></li>';
    }

    echo '</ul>';
}

function echoPageLink($pageNumber, $currentPage) {
    echo '<li';
    if ($pageNumber === $currentPage) {
        echo ' class="active"';
    }
    echo '><a href="?page=' . $pageNumber . '">' . $pageNumber . '</a></li>';
}
?>
