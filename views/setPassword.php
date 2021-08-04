<?php
// Get the document root.
$document_root = $_SERVER['DOCUMENT_ROOT'];
// Require in the website template. 
require_once $document_root.'/models/Page.php';


// Get the password_reset_token from the query parameters, if it exists. 
if(isset($_GET['token'])){
  $database = new Database();
  $page = new Page('Reset');
  $page->render_header();
  $reset_token = $_GET['token'];
  $userid = $database->get_user_by_password_reset_token($reset_token);
  if(isset($userid)){
    // Set the token in the session. When the user clicks the submit form. 
    // The /controller/password/password_reset_handler.php script will
    // look for this token and set the new password for the user and then redirect them to the login page. 
    $_SESSION['password_reset_token'] = $reset_token;
    $password_reset_handler = '/controllers/password/passwordResetHandler.php';
  }else{
    // Redirect the user to the login page because that is an invalid 
    // password reset link.
    header("Location: http://".$_SERVER['HTTP_HOST']."/views/login.php");  
  }
}else{
  // Redirect to login page because there is no token set in the query params
  // which means the user is not supposed to be here. 
  header("Location: http://".$_SERVER['HTTP_HOST']."/views/login.php");
}
?>

<div class='row mt-3 justify-content-center'>
  <!-- Left page column. -->
  <div class='col-sm-12 col-lg-7 d-flex justify-content-center'> 
    <div class='col-sm-12 col-md-7 col-lg-8 justify-content-center'>
      <div class='card bg-light border-0'>
        <div class='card-header bg-dark text-light'>
          <h3 class='card-title text-center'>Please enter your new password</h3>
        </div>
        <div class='card-body'>
          <form action='<?php echo $password_reset_handler ?>' method='POST'>
            <div class='row justify-content-center'>
              <div class='col-sm-8'>
                <label for='newPassword' class='form-label mt-2'><span class='bi bi-file-lock2'> New Password</span></label>
                <input type='password' class='form-control' name='new_password' id='newPassword' required>
                <button class='btn btn-primary mt-2'>Save <span class='bi bi-check2-circle'></span></button>
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