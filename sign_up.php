<?php
// Get the base file path. 
$document_root = $_SERVER['DOCUMENT_ROOT'];

require $document_root.'/Page.php';

(new Page('Sign up', 'sign up'));
?>