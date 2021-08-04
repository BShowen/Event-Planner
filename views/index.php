<?php
// Get the base file path. 
$document_root = $_SERVER['DOCUMENT_ROOT'];
// Require in the template for the website
// and instantiate a page object. 
require_once $document_root.'/models/Page.php';
$page = new Page($title = "dashboard");

// Require in the User file for user validation.
require_once $document_root.'/models/User.php';
// Get the value of the cookie. 
$user_id = intval($_COOKIE['auth']);
$current_user = new User($user_id);  
// Retrieve 3 events from the database.
$events = $current_user->events(3);

$page->render_header();

function get_user_image_url($user){
  if($user->photo_url){
    return './../'.$user->photo_url;
  }else{
    return '/Photos/img.png';
  }
}

?>
<div class='row mt-3'>
  <!-- Left page column. -->
  <div class='col-sm-12 col-lg-6'>
    <!-- Row in the left page column -->
    <div class='col'>
      <!-- Row content -->
      <div class='card border-0'>
        <!-- Image container -->
        <div class='container'>
          <div class='row justify-content-center'>
            <div class='col-lg-4'>
            <img src='.<?php echo get_user_image_url($current_user); ?>' alt='User photo' class='card-img-top img-thumbnail'>
            </div>
          </div>
        </div>            
        <div class='card-body'>
          <h1 class='card-title text-center'>
            <?php
            echo $current_user->first_name." ".$current_user->last_name;
            ?>
          </h1>
        </div>
      </div>
    </div>

    <!-- This is the form for creating a new event. -->
    <div class='col justify-content-center'>
      <div class='card bg-light border-0'>
        <div class='card-header bg-dark text-light'>
          <h3 class='card-title text-center'>Create a new event</h3>
        </div>
        <div class='card-body'>
          <form action='./../controllers/events/newEventHandler.php' method='POST'>
            <div class='row justify-content-center'>
              <div class='col-sm-8'>
                <label for='eventTitle' class='form-label mt-2'>Event Title</label>
                <input type='text' class='form-control' name='event_title' id='eventTitle'>
                
                <label for='eventDate' class='form-label mt-2'>Event Date</label>
                <input type='date' class='form-control' name='event_date' id='eventDate'>

                <label for='eventDescription' class='form-label mt-2'>Event Description</label>
                <textarea name='event_description' id='eventDescription' class='form-control'></textarea>

                <input class='btn btn-primary btn-sm mt-2' type='submit' value='Submit'>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Right page column -->
  <div class='col mt-3 justify-content-center col-lg-6'>
    <div class='card bg-light border-0'>
      <div class='card-header bg-dark text-light'>
        <h3 class='card-title text-center pt-2'>My Upcoming events</h3>
      </div>
      <div class='card-body'>
        <?php 
          if(!empty($events)){
            foreach($events as $event){
              echo "<div class='card mt-3'>
                <div class='card-header'>
                  <h3 class='card-title'> $event->title </h3>
                  <h5 class='card-subtitle'> $event->date </h5>
                </div>
                <div class='card-body'>  
                  <p class='card-text'> $event->description </p>
                  <button type='button' class='btn btn-primary btn-sm'>Details <i class='bi bi-arrow-right-square'></i></button>
                </div>
              </div>";
            }
          }else{
            echo "<h3 class='card-title text-center pt-2'>You don't have any events</h3>";
          }
        ?>
      </div>
    </div>
  </div>
</div>

<?php
  $page->render_footer();
?>