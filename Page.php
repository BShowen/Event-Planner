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
require $document_root.'/login_form.php';
require $document_root.'/signup_form.php';
// Require in the Database
require $document_root.'/Database.php';

class Page {
  const DEFAULT_TITLE = "Event Planner | ";
  private $title;
  private $content;

  function __construct($title, $form_type = 'login'){
    $this->set_title($title);
    // If there is no cookie then the user is not logged in. 
    // Display the default template with the login form 
    // and terminate the program.
    if(!isset($_COOKIE['auth']) && $form_type == 'login'){
      Header::render(self::DEFAULT_TITLE.$this->title);
      echo "<div class='container-fluid'>";
      $this->display_nav();
      // I figured out how to method chain in PHP. You wrap the 
      // object in () and then proceed to call the next method. 
      (new LoginForm())->render();
      echo "</div>";
      Footer::render();
      exit;
    }elseif($form_type == 'sign up'){
      Header::render(self::DEFAULT_TITLE.$this->title);
      echo "<div class='container-fluid'>";
      $this->display_nav();
      // I figured out how to method chain in PHP. You wrap the 
      // object in () and then proceed to call the next method. 
      (new SignupForm())->render();
      echo "</div>";
      Footer::render();
      exit;
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