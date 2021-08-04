<?php
// Get the base file path. 
$document_root = $_SERVER['DOCUMENT_ROOT'];
// Require in the website template. 
require_once $document_root.'/models/Page.php';

// Require the website template and render the header. 
$page = new Page("Login");
$page->render_header();

// Login handler link
$login_handler_link = '/controllers/login/loginHandler.php';

// If this page is being re-rendered with errors, each error will de displayed. 
if(isset($_SESSION['LOGIN_ERRORS'])){
  echo "<div class='alert alert-danger mt-4 w-25 text-center' role='alert' style='margin:0 auto;'>";
  foreach($_SESSION['LOGIN_ERRORS'] as $error_message){
    echo "<i class='bi bi-x-octagon'> ".ucfirst($error_message)."</i><br/>";
  }
  echo "</div>";
  unset($_SESSION['LOGIN_ERRORS']);
}

?>
<div class='row mt-3 justify-content-center'>
  <!-- Left page column. -->
  <div class='col-sm-12 col-lg-7 d-flex justify-content-center'> 
    <div class='col-sm-12 col-md-7 col-lg-8 justify-content-center'>
      <div class='card bg-light border-0'>
        <div class='card-header bg-dark text-light'>
          <h3 class='card-title text-center'>Login to Event Planner</h3>
        </div>
        <div class='card-body'>
          <form action='<?php echo $login_handler_link; ?>' method='POST'>
            <div class='row justify-content-center'>
              <div class='col-sm-8'>
                <label for='emailAddress' class='form-label mt-2'><span class='bi bi-envelope'> Email address</span></label>
                <input type='text' class='form-control' name='email_address' id='emailAddress'>
                
                <label for='password' class='form-label mt-2'>Password</label>
                <input type='password' class='form-control' name='password' id='password'>
                <p><a href='./passwordReset.php'>Forgot password</a></p>
                
                
                <button class='btn btn-primary'>Login<span class='bi bi-box-arrow-in-right'></span></button>
                <p class='mt-4'>Not a member? <a href='./signup.php'>Sign up now!</a></p>
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