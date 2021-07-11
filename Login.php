<?php 
// Get the base file path. 
$document_root = $_SERVER['DOCUMENT_ROOT'];
// Require in the website template. 
require $document_root.'/Page.php';

$page = new Page("Login");
if($_SESSION['LOGIN_ERRORS']){
  $errors = "<div class='alert alert-danger mt-4 w-25 text-center' role='alert' style='margin:0 auto;'>";
  foreach($_SESSION['LOGIN_ERRORS'] as $error_message){
    $errors .= "<i class='bi bi-x-octagon'> ".ucfirst($error_message)."</i><br/>";
  }
  $errors.="</div>";
  $_SESSION['LOGIN_ERRORS'] = array();
}else{
  $errors = '';
}

$login_form = "<div class='row mt-3 justify-content-center'>
<!-- Left page column. -->
<div class='col-sm-12 col-lg-7 d-flex justify-content-center'> 
  <div class='col-sm-12 col-md-7 col-lg-8 justify-content-center'>
    <div class='card bg-light border-0'>
      <div class='card-header bg-dark text-light'>
        <h3 class='card-title text-center'>Login to Event Planner</h3>
      </div>
      <div class='card-body'>
        <form action='./LoginHandler.php' method='POST'>
          <div class='row justify-content-center'>
            <div class='col-sm-8'>
              <label for='emailAddress' class='form-label mt-2'><span class='bi bi-envelope'> Email address</span></label>
              <input type='text' class='form-control' name='email_address' id='emailAddress'>
              
              <label for='password' class='form-label mt-2'>Password</label>
              <input type='password' class='form-control' name='password' id='password'>
              <p><a href='./Reset.php'>Forgot password</a></p>
              
              
              <button class='btn btn-primary'>Login <i class='bi bi-box-arrow-in-right'></i></button>
              <p class='mt-4'>Not a member? <a href='./Signup.php'>Sign up now!</a></p>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>";

$content = $errors ? $errors.$login_form : $login_form ;

$page->set_content($content);
$page->render();

?>