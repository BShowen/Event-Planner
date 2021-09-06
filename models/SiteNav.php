<?php
class SiteNav{
  private $dashboard = "";
  private $events = "";
  private $profile = "";
  private $friends = "";
  private $confirmation = "";
  private $logout_button;
  private $logged_in;
  private $document_root;

  function __construct(string $active){
    $this->document_root = $_SERVER['DOCUMENT_ROOT'].'/eventPlanner';
    $this->logged_in = isset($_COOKIE['auth']);
    $this->logout_button = isset($_COOKIE['auth']) ? '' : 'none';
    switch($active) {
      case "dashboard":
        $this->dashboard = "active";
        break;
      case "events":
        $this->events = "active";
        break;
      case "profile":
        $this->profile = "active";
        break;
      case "friends":
        $this->friends = "active";
        break;
      case "confirmation":
        $this->confirmation = "active";
        break;
      default:
        $this->dashboard = "active";
        break;
    }
  }


  function render(){
    if($this->logged_in){
      echo "<div class='row'>
      <nav class='navbar navbar-expand-sm navbar-dark bg-dark pt-3 pb-3'>
        <div class='container-fluid p-0'>
          <a class='navbar-brand fs-4' href='./index.php' >Event Manager</a>
          <button
            class='navbar-toggler'
            type='button'
            data-bs-toggle='collapse'
            data-bs-target='#collapseMe'
            aria-controls='collapseMe'
            aria-expanded='false'
            aria-label='Toggle Navigation'
          >
            <span class='navbar-toggler-icon'></span>
          </button>
          <div class='collapse navbar-collapse' id='collapseMe'>
            <ul class='navbar-nav me-auto'>
              <li class='nav-item'>
                <a class='nav-link ".$this->dashboard." fs-5' href=/views/index.php>Dashboard</a>
              </li>
              <li class='nav-item'>
                <a class='nav-link ".$this->events." fs-5' href=/views/events.php>Events</a> 
              </li>
              <li class='nav-item'>
                <a class='nav-link ".$this->profile." fs-5' href='/views/profile.php'>Profile</a> 
              </li>
              <li class='nav-item'>
                <a class='nav-link ".$this->friends." fs-5' href='/views/friends.php'>Friends</a> 
              </li>
            </ul>
            <form action='/controllers/logout/logout.php' method='POST' style='display:".$this->logout_button." '>
              <button class='btn btn-primary fs-6'>Log out <i class='bi bi-box-arrow-right'></i></button>
            </form>
          </div>
        </div>
      </nav> 
    </div>";
    }else{
      echo "<div class='row'>
      <nav class='navbar navbar-expand-sm navbar-dark bg-dark pt-3 pb-3'>
        <div class='container-fluid p-0'>
          <a class='navbar-brand fs-4' href='./index.php' >Event Manager</a>
        </div>
      </nav> 
    </div>";
    }

  }
}
?>