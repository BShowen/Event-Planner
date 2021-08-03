
<?php
// Get the base file path. 
$document_root = $_SERVER['DOCUMENT_ROOT'];
// Require in the website template. 
require $document_root.'/models/Page.php';

// Require the website template and render the header. 
$page = new Page("Reset");
$page->render_header();

// Login handler link
$password_reset_handler_link = '/controllers/password/passwordResetEmailer.php';

?>
<div class='row mt-3 justify-content-center'>
  <!-- Left page column. -->
  <div class='col-sm-12 col-lg-7 d-flex justify-content-center'> 
    <div class='col-sm-12 col-md-7 col-lg-8 justify-content-center'>
      <div class='card bg-light border-0'>
        <div class='card-header bg-dark text-light'>
          <h3 class='card-title text-center'>Please enter your email</h3>
        </div>
        <div class='card-body'>
          <form action='<?php echo $password_reset_handler_link; ?>' method='POST'>
            <div class='row justify-content-center'>
              <div class='col-sm-8'>
                <label for='emailAddress' class='form-label mt-2'><span class='bi bi-envelope'> Email address</span></label>
                <input type='email' class='form-control' name='email_address' id='emailAddress' required>
                <button class='btn btn-primary mt-2'>Forgot Password<span class='bi bi-box-arrow-in-right'></span></button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php 
$page->render_footer();
?>