<?php 
// Get the base file path.
$document_root = $_SERVER['DOCUMENT_ROOT'].'/eventPlanner';

// Require in the template for the website
// and instantiate a page object. 
require_once $document_root.'/models/Page.php';
$page = new Page($title = "events");
$page->render_header();

// Require in the User and Event object. 
require_once $document_root.'/models/User.php';
// require_once $document_root.'/models/Event.php';

// Retrieve the cookie from the users browser and set the current user 
// to this user. If the user doesn't have a cookie in the browser then 
// ./Page.php will on line 8 will have caught this and the user will be 
// required to log in. 
$current_user = new User(intval($_COOKIE['auth']));

$selection = isset($_POST['selection']) ? intval($_POST['selection']) : intval($current_user->user_id);
$db = new Database();
if($selection == -1){ // Retrieve all the events from the database. 
  $events = $db->get_all_events();
}else{ // Find events for a particular user
  $events = $db->get_events_for_user($selection);
}

// Create a blank string. I append characters to this string which makeup the 
// HTML content which will be rendered. 
$table_rows = '';

// Iterate through each of the events
foreach($events as $event){
  list($first, $last, $title, $event_date, $description) = $event;
  $event_date = format_date($event_date);
  $table_rows.="<tr>
    <td> $first $last </td>
    <td> $title </td>
    <td> $event_date </td>
    <td> $description </td>
    <td> 0 </td>
  </tr>";
}

// This function takes in a date that is formatted yyyy-mm-dd by MySQL and returns a date formatted
// as mm-dd-yyyy. 
function format_date($event_date){
  $date_array = explode('-', $event_date);
  $year = intval($date_array[0]);
  $month = intval($date_array[1]);
  $day = intval($date_array[2]);
  $hour = $minute = $second = 0;
  $unix_time = mktime($hour, $minute, $second, $month, $day, $year);
  return date('F jS Y', $unix_time);
}

$options = "<option value='-1'>All</option>";
$query = 'SELECT userid, first, last FROM users';
$stmt = ($db->get_handle())->prepare($query);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($userid, $first, $last);
while($stmt->fetch()){
  $first = ucfirst($first);
  $last = ucfirst($last);
  if($userid == $selection){
    $options.="<option selected value='$userid'>$first $last[0]</option>";
  }else{
    $options.="<option value='$userid'>$first $last[0]</option>";
  }
}
?>

<div class='row mt-3 justify-content-center'>
  <!-- Single page column. -->
  <div class='col-sm-9'>
    <div class='card'>
      <div class='card-header bg-dark text-light'>
        <h3 class='card-title text-center pt-2'>Events</h3>
      </div>
      <div class='card-body'>
        <div class='card'>
          <div class='card-body'>
            <form action='./events.php' method='POST'>
              <div class='row'>
                <label class='col-sm-2 col-lg-2 text-end'>See all events from:</label>
                <div class='col-sm-9 col-lg-3'>
                  <select name='selection' class='form-select form-select-sm' aria-label='.form-select-sm'>
                    <?php  
                    echo $options;
                    ?>
                  </select>
                </div>
                <div class='col-sm-1'>
                  <button class='btn btn-primary btn-sm'><span class='bi bi-search'></span></button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <table class='table table-striped table-hover border-start border-end border-top mt-3'>
          <thead>
            <th scope='col'>Host</th>
            <th scope='col'>Event Title</th>
            <th scope='col'>Event Date</th>
            <th scope='col'>Event Description</th>
            <th scope='col'>Guests</th>
          </thead>
          <tbody>
            <?php
            echo $table_rows;
            ?>
          </tbody>
        </table>
      </div>
    </div>  
  </div>
</div>

<?php
$page->render_footer();
?>