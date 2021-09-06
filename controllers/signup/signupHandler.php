<?php
// Get the document root.
$document_root = $_SERVER['DOCUMENT_ROOT'].'/eventPlanner';
// Get the database file. 
require_once $document_root.'/models/Database.php';
// Require in the error class that this page can throw. 
require_once $document_root.'/errors/SignUpError.php';

$form_errors = form_errors();
if(empty($form_errors)){
  $db = (new Database())->get_handle();
  // get the email and password provided by the user. 
  $first_name = $_POST['first_name'];
  $last_name = $_POST['last_name'];
  $email_address = $_POST['email_address'];
  $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT); 
  
  // Query the database to determine if the email is already in use. 
  $query = "SELECT EXISTS (SELECT * FROM users WHERE email = ?)";
  $stmt = $db->prepare($query);
  $stmt->bind_param('s', $email_address);
  $stmt->execute();
  $stmt->store_result();
  $stmt->bind_result($email_exists);
  $stmt->fetch();
  
  // If the email is in use, tell the user to try another email. 
  // If the email is NOT in use, sign the user up and log them in. 
  if($email_exists){
    set_session_error_message(['That email is in use.<br/>Please try another email']);
    redirect_to('signup.php');
  }else{
    $query = "INSERT INTO users (first, last, email, password_digest) VALUES(?,?,?,?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ssss", $first_name, $last_name, $email_address, $password_hash );
    $stmt->execute();
    if($stmt->affected_rows > 0){
      setcookie("auth", strval($stmt->insert_id), 0, "/");
      redirect_to('index.php');
    }else{
      set_session_error_message(["Something went wrong."]);
      redirect_to('signup.php');
    }
  }
}else{
  set_session_error_message($form_errors);
  redirect_to('signup.php');
}


// This function retrieves all of the form_fields that were passed in from the form being submitted. 
// Any form field with a value that is blank is invalid. 
// An error message will be created for each invalid form field. 
function form_errors(){
  $form_fields = [
    'first name' => $_POST['first_name'], 
    'last name' => $_POST['last_name'], 
    'email address' => $_POST['email_address'], 
    'password ' => $_POST['password']
  ];
  $errors = [];
  foreach($form_fields as $field => $value){
    if(empty(trim($value))){
      array_push($errors, "{$field} cannot be blank.");
    }
  }
  return $errors;
}

// This function accepts an array of error messages as an array of strings. 
// Each message is converted into an object, serialized, and pushed into an array. 
// This new array is then set to the appropriate session variable. 
// This is done so that the following page can re-construct these error objects
// and simply echo them to the browser without having to call any methods. 
function set_session_error_message($error_message){
  $errors = [];
  foreach($error_message as $message){
    $serialized_error_object = serialize(new SignUpError($message));
    array_push($errors, $serialized_error_object);
  }
  $_SESSION['SIGNUP_ERRORS'] = $errors;
}


// This method redirects from the current page to a specified page. 
// The $page parameter is passed in as a string. 
// For example redirect_to("index.php");
function redirect_to($page){
  // Redirect link
  $indexPage = "Location: http://".$_SERVER['HTTP_HOST']."/index.php";
  $signupPage = "Location: http://".$_SERVER['HTTP_HOST']."/views/signup.php";
  switch($page){
    case 'index.php':
      header($indexPage, TRUE, 302);
      break;
    case 'signup.php':
      header($signupPage, TRUE, 302);
      break;
  }
}
?>