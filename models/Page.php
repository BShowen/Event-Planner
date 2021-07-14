<?php
// Used for debugging. 
// print_r($_SERVER);
// exit;

// Get the base file path. 
$document_root = $_SERVER['DOCUMENT_ROOT'];
// Require in the sites' navigation. 
require $document_root.'/models/SiteNav.php';
// Require in the header for the page.
require $document_root.'/partials/Header.php';
// Require in the Footer for the page. 
require $document_root.'/partials/Footer.php'; 
// Require in the Database
require $document_root.'/models/Database.php';

class Page {
  const DEFAULT_TITLE = "Event Planner | ";
  private $title;
  private $content;

  function __construct($title){
    $this->set_title($title);
    // If the user is not logged in and they are trying to access a page other than Login.php or Signup.php
    // then this means they are not authorized to access the page until they log in. 
    if($title != 'Login' && $title != 'Sign Up'  && !isset($_COOKIE['auth'])){
      // Redirect the user to the login page. 
      header("Location: http://".$_SERVER['HTTP_HOST']."/controllers/login/login.php");
    }
  }

  public function render_header(){
    Header::render(self::DEFAULT_TITLE.$this->title);
    echo "<div class='container-fluid'>";
    $this->display_nav();
  }

  public function render_footer(){
    echo "</div>";
    Footer::render();
  }

  public function set_content($page_content){
    $this->content = $page_content;
  }

  private function set_title($page_title){
    $this->title = $page_title;
  }

  private function display_nav(){
    (new SiteNav($this->title))->render();
  }

}
?>