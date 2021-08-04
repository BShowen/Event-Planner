<?php
// Get the base file path. 
$document_root = $_SERVER['DOCUMENT_ROOT'];
// Require in the template for the website
// and instantiate a page object. 
require_once $document_root.'/models/Page.php';
$page = new Page($title = "Upload");

// Require in the User file for user validation.
require_once $document_root.'/models/User.php';
// Get the value of the cookie. 
$user_id = intval($_COOKIE['auth']);
$current_user = new User($user_id);  
// Retrieve 3 events from the database.
$events = $current_user->events(3);

$photo_upload_handler = '/controllers/photos/photoUploadHandler.php';

$page->render_header();
?>


<div class='row mt-3 justify-content-center'>
  <!-- This is the first (and only) column on this page. -->
  <div class='card col-sm-5 col-md-4 col-xl-3 col-xxl-3'>
    <div class='card-body'>

      <form action="<?php echo $photo_upload_handler; ?>" method='POST' enctype="multipart/form-data">
        <label for='uploaded_file'>Upload a file:</label>
        <input type="file" name='uploaded_file' id='uploaded_file'>
        <button class='btn btn-primary btn-sm mt-1' type="submit">
          Upload Photo
          <span class='bi bi-pen'></span>
        </button>
      </form>
    </div>
  </div>
</div>

<?php
  $page->render_footer();
?>