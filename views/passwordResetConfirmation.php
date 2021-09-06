<?php

// Get the document root.
$document_root = $_SERVER['DOCUMENT_ROOT'].'/eventPlanner';

// Require in the website template.
require_once $document_root.'/models/Page.php';

// Render the header. 
$page = new Page("Reset");
$page->render_header();
?>

<div class='row mt-3 justify-content-center'>
  <!-- Left page column. -->
  <div class='col-sm-12 col-lg-7 d-flex justify-content-center'> 
    <div class='col-sm-12 col-md-7 col-lg-8 justify-content-center'>
      <div class='card bg-light border-0'>
        <div class='card-body'>
          <h3 class="text-center">Please check your email for a password reset link.</h3>
        </div>
      </div>
    </div>
  </div>
</div>


<?php
$page->render_footer();
?>