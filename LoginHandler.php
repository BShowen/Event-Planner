<?php
// Get the document root.
$document_root = $_SERVER['DOCUMENT_ROOT'];

// Get the database file. 
require $document_root.'/Database.php';
$db = (new Database())->get_handle();

// get the email and password provided by the user. 
$email_address = $_POST['email_address'];
$password = $_POST['password'];

// Query the database for the userid and the password_digest associated with the provided email. 
$query = "SELECT userid, password_digest FROM users WHERE email = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('s', $email_address);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($user_id, $password_digest);
$stmt->fetch();

// Determine if we have found a user in the database. 
if($stmt->num_rows > 0 && $user_id && $password_digest){
  if(password_verify($password, $password_digest)){
    // Set a cookie in the users browser and redirect to the index page.
    setcookie("auth", strval($user_id), 0, "/");
    // Redirect to the home page. 
    header("Location: http://".$_SERVER['HTTP_HOST']."/index.php");
  }else{
    echo "Incorrect password.";
  }
}else{
  echo "Those credentials are incorrect. Please try again.";
}

?>