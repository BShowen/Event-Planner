<?php
  class Header {
    public static function render($title){
      $title = ucwords($title);
      echo "<!DOCTYPE html>
      <html lang='en'>
        <head>
          <meta charset='UTF-8'>
          <meta http-equiv='X-UA-Compatible' content='IE=edge'>
          <meta name='viewport' content='width=device-width, initial-scale=1.0'>
          <!-- This is part 1 of 2 for the Bootstrap CDN. -->
          <link 
            href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css' 
            rel='stylesheet' integrity='sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x' 
            crossorigin='anonymous'
          >
          <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css'>
          <title>$title</title>
          <style>
            html, body{
              height: 100%;
            }
            body>.container-fluid {
              min-height: 100%;
              padding-bottom: 150px;
            } 
            footer {
              height: 100px;
              margin-top: -100px; 
            }
          </style>
        </head>
        <body>";
    }
  }
?>