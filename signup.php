<?php 
// Get the base file path. 
$document_root = $_SERVER['DOCUMENT_ROOT'];
// Require in the website template. 
require $document_root.'/Page.php';

// Require in the website template and render the header. 
$page = new Page("Sign Up");
$page->render_header();

// If this page is being re-rendered with errors, each error will be displayed. 
if($_SESSION['SIGNUP_ERRORS']){
  echo "<div class='alert alert-danger mt-4 w-25 text-center' role='alert' style='margin:0 auto;'>";
  foreach($_SESSION['SIGNUP_ERRORS'] as $error_message){
    echo "<i class='bi bi-x-octagon'>".ucfirst($error_message)."</i><br/>";
  }
  echo "</div>";
  $_SESSION['SIGNUP_ERRORS'] = array();
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
          <form action='./signupHandler.php' method='POST'>
            <div class='row justify-content-center'>
              <div class='col-sm-8'>
                <label for='firstName' class='form-label mt-2'>First name</label>
                <input type='text' class='form-control' name='first_name' id='firstName'>
                
                <label for='lastName' class='form-label mt-2'>Last name</label>
                <input type='text' class='form-control' name='last_name' id='lastName'>
                
                <label for='emailAddress' class='form-label mt-2'>Email address</label>
                <input type='text' class='form-control' name='email_address' id='emailAddress'>
                
                <label for='password' class='form-label mt-2'>Password</label>
                <input type='password' class='form-control' name='password' id='password'>
                
                <input class='btn btn-primary mt-2' type='submit' value='Submit'>
                <p class='mt-1'>Already a member? <a href='./login.php'>Login</a></p>
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