<?php
$document_root = $_SERVER['DOCUMENT_ROOT'];

// Require in the website template. 
require_once $document_root.'/models/Page.php';

$db = (new Database())->get_handle();

if(strlen(trim($_POST['email_address'])) > 0){
  $email_address = trim($_POST['email_address']);
  $database = new Database();
  if( $database->email_exists($email_address) ){
    // I create a hash of Unix time to user as the password reset token. 
    $reset_token = md5(time());
    // Insert the reset token in the DB for the appropriate email address. 
    $successful_insertion = $database->insert_password_reset_token($email_address, $reset_token);
    if($successful_insertion){
      send_email($email_address, $reset_token);
    }
  }
}

function send_email($email_address, $reset_token){
  $to = $email_address;
  $subject = "Event Planner password reset.";
  $message = "Use this link to reset your password. ".$_SERVER['HTTP_HOST']."/views/setPassword.php?token={$reset_token}";
  $headers = 'From: EventPlanner@gmail.com';
  mail($to, $subject, $message, $headers);
}

header("Location: http://".$_SERVER['HTTP_HOST']."/views/passwordResetConfirmation.php");
?>

