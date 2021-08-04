<?php

// Get the document root.
$document_root = $_SERVER['DOCUMENT_ROOT'];
// Require in the User object.
require_once $document_root.'/models/User.php';

// Check for upload errors. 
if($_FILES['uploaded_file']['error'] > 0){
  switch( $FILES['uploaded_file']['error'] ) {
    case 1: 
      echo "File exceeded upload_max_filesize.";
      break;
    case 2: 
      echo "File exceeded max_file_size.";
      break;
    case 3: 
      echo "File only partially uploaded";
      break;
    case 4: 
      echo "No file uploaded";
      break;
    case 6: 
      echo "Cannot upload file: No temp directory specified.";  
      break;
    case 7: 
      echo "Upload failed: Cannot write to disk.";
      break;
    case 8: 
      echo "A PHP extension blocked the file upload.";
      break;
  }
  exit;
}

// Check the MIME type.
if($_FILES['uploaded_file']['type'] != 'image/png' && $_FILES['uploaded_file']['type'] != 'image/jpeg'){
  echo "Your photo needs to be a PNG or JPEG photo.";
  exit;
}


// Get the value of the cookie and set the current_user.
$user_id = intval($_COOKIE['auth']);
$current_user = new User($user_id); 

// Rename the file
$_FILES['uploaded_file']['name'] = 'user_'.$current_user->user_id.'_photo.jpeg';

// Move the file
$photo_path = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$_FILES['uploaded_file']['name'];
if(!move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $photo_path)){
  echo "Could not move the file to the destination directory.";
  exit;
}

$source = '/uploads/'.$_FILES['uploaded_file']['name'];
// echo "<img src={$source} />";
$current_user->set_photo_url($source);

// Redirect to the user page.
header("Location: http://".$_SERVER['HTTP_HOST']."/views/profile.php");
?>