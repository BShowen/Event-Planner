<?php
// Used for debugging. 
// print_r($_SERVER);
// exit;
/*
This is my template for my entire site. You import this class and then instantiate 
it with a title for the browser page title. There are only two public functions called
render(), and set_content(). You need to instantiate this object, set the content of 
the page (as a string of html) and then call the render() method. For example. 

$home_page = new Page("Home");
$home_page->set_content("<p>This is the content that will be displayed in the body of the page</p>");
$home_page->render();

Its as simple as that! I now have one class that controls every single page of my application. 
*/

// Get the base file path. 
$document_root = $_SERVER['DOCUMENT_ROOT'];
// Require in the sites' navigation. 
require $document_root.'/partials/SiteNav.php';
// Require in the header for the page.
require $document_root.'/partials/Header.php';
// Require in the Footer for the page. 
require $document_root.'/partials/Footer.php'; 
// Require in the Database
require $document_root.'/models/Database.php';
$dbb = "cool";

class Page {
  const DEFAULT_TITLE = "Event Planner | ";
  private $title;
  private $content;

  function __construct($title){
    // Set the page title. 
    $this->set_title($title);
    // If the user is not logged in and they are trying to access a page other than Login.php or Signup.php
    // then this means they are not authorized to access the page until they log in. 
    if($title != 'Login' && $title != 'Sign Up'  && !isset($_COOKIE['auth'])){
      // Redirect the user to the login page. 
      Header("Location: http://".$_SERVER['HTTP_HOST']."/login.php");
    }
  }

  public function render(){
    Header::render(self::DEFAULT_TITLE.$this->title);
    echo "<div class='container-fluid'>";
    $this->display_nav();
    echo $this->content;
    echo "</div>";
    Footer::render();
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