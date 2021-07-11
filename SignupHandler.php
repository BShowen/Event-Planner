<?php
// Get the document root.
$document_root = $_SERVER['DOCUMENT_ROOT'];

// Get the database file. 
require $document_root.'/Database.php';
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
$stmt->bind_result($exists);
$stmt->fetch();

// If the email is in use, tell the user to try another email. 
// If the email in NOT in use, sign the user up and log them in. 
if($exists){
  echo "That email is in use.<br/>Please try another email";
  exit();
}else{
  $query = "INSERT INTO users (first, last, email, password_digest)
  VALUES(?,?,?,?)";
  $stmt = $db->prepare($query);
  $stmt->bind_param("ssss", $first_name, $last_name, $email_address, $password_hash );
  $stmt->execute();
  if($stmt->affected_rows > 0){
    echo "signed up successfully";  
  }else{
    echo "Something went wrong.";
  }
}
?>