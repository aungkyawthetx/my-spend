<?php

  include __DIR__ . '/../src/helpers/url.php';
  require_once __DIR__ . '/../src/helpers/isLoggedIn.php';
  $title = "Categories - BudgetBoard";

  ob_start();

  include __DIR__ . '/../views/categories/header.php';
  include __DIR__ . '/../views/categories/table.php';
  include __DIR__ . '/../views/components/pagination.php';
  include __DIR__ . '/../views/components/modals/add-category-modal.php';
  include __DIR__ . '/../views/components/modals/delete-category-modal.php';
 $content = ob_get_clean();
 include __DIR__ . '/../views/components/layout.php';

?>