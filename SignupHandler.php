<?php
// Get the document root.
$document_root = $_SERVER['DOCUMENT_ROOT'];
// Get the database file. 
require $document_root.'/Database.php';

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
      setcookie("auth", strval($stmt->insert_id), 0, "/");
      Header("Location: http://".$_SERVER['HTTP_HOST']."/index.php",TRUE,302);
    }else{
      echo "Something went wrong.";
    }
  }
}else{
  $_SESSION['SIGNUP_ERRORS'] = $form_errors;
  Header("Location: http://".$_SERVER['HTTP_HOST']."/Signup.php",TRUE,302);
}

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
      array_push($errors, "$field cannot be blank.");
    }
  }
  return $errors;
}
?>