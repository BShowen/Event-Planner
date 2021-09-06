<?php
/*
• TO DO
• Show some type of confirmation that the password reset was successful. 
• use $_SESSION['ERRORS'] and $_SESSION['SUCCESS'] to display appropriate messages throughout  my application.
• Remove the password_reset_token from the DB upon successful password reset. 
*/

// Get the document reoot.
$document_root = $_SERVER['DOCUMENT_ROOT'].'/eventPlanner';
// Require in the database. 
require_once $document_root.'/models/Database.php';
// Get an instance of the Database.
$database = new Database();
// Retrieve the password reset token from the session.
$reset_token = $_SESSION['password_reset_token'];
// Find the user based on the token.
$userid = $database->get_user_by_password_reset_token($reset_token);
$userid = intval($userid);
// Has the new password.
$password_hash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
// Update the password in the database. 
$query = "UPDATE users SET password_digest = ? WHERE userid = ?";
$stmt = ($database->get_handle())->prepare($query);
$stmt->bind_param('si', $password_hash, $userid);
$stmt->execute();
// Redirect to the login page. 
header("Location: http://".$_SERVER['HTTP_HOST']."/index.php", True, 302);
?>