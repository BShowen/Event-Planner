<?php 
// Get the base file path.
$document_root = $_SERVER['DOCUMENT_ROOT'];

// Require in the template for the website
// and instantiate a page object. 
require $document_root.'/models/Page.php';
$page = new Page($title = "profile");
$page->render_header();

// Require in the User object. 
require $document_root.'/models/User.php';

// Retrieve the cookie form the users browser and set the current user
// to this user. 
$current_user = new User(intval($_COOKIE['auth']));
?>

<!-- container for page content. It is also the first (and only) row in the page. -->
<div class='row mt-3 justify-content-center'>
  <!-- This is the first (and only) column on this page. -->
  <div class='card col-sm-5 col-md-4 col-xl-3 col-xxl-3'>
    <div class='row '>
      <div class='col-sm-6 col-md-6 mt-2'>
        <img src='./Photos/img.png' alt='User photo' class='card-img-top img-thumbnail'>
      </div>
    </div>
    <div class='card-body'>
      <h1 class='card-title'>
        <?php
          echo $current_user->first_name.' '.$current_user->last_name;
        ?>
      </h1>
      <p class='card-text'>Email: <?php echo $current_user->email ?></p>
    </div>
  </div>
</div>

<?php
  $page->render_footer();
?>