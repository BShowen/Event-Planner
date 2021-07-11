<?php
  // Get the base file path.
  $document_root = $_SERVER['DOCUMENT_ROOT'];

  // Require in the template for the website
  // and instantiate a page object. 
  require $document_root.'/Page.php';
  $page = new Page($title = "events");

  // Require in the database object then
  // get a handle on the database.
  require $document_root.'/Database.php';
  $db = (new Database())->get_handle();

  // Require in the User and Event objects. 
  require $document_root.'/models/User.php';
  require $document_root.'/models/Event.php';
  
  // Retrieve the cookie from the users browser and set the current user 
  // to this user. If the user doesn't have a cookie in the browser then 
  // ./Page.php will on line 8 will have caught this and the user will be 
  // required to log in. 
  $current_user = new User(intval($_COOKIE['auth']));

  // Retrieve the events that are associated with the current user. 
  // This method returns an array of arrays. Each sub array correlates to 
  // an Event row from the event database. Each sub array has this structure
  // [
  //   [title, date, description],
  //   [title, date, description],
  //   ...etc,
  //   [title, date, description],
  // ]
  $events = $current_user->events();

  // Create a blank string. I append characters to this string which makeup the 
  // HTML content which will be rendered. 
  $table_rows = '';

  // Iterate through each of the events that were returned by lin 34. 
  foreach($events as $event){
    // 
    list($title, $date, $description) = $event;
    $table_rows.="<tr>
      <td> $current_user->first_name $current_user->last_name </td>
      <td> $title </td>
      <td> $date </td>
      <td> $description </td>
      <td> 0 </td>
    </tr>";
  }

  // Retrieve all the events from the database. 
  // $events = $events_db->get_events();
  // $query = 'SELECT title, event_date, description FROM events';
  // $query = 'SELECT u.first, u.last, e.title, e.event_date, e.description FROM users AS u JOIN events as e USING(userid)';
  // $stmt = $db->prepare($query);
  // $stmt->execute();
  // $stmt->store_result();
  // $stmt->bind_result($first, $last, $title, $event_date, $description);

  $content = "<div class='row mt-3 justify-content-center'>
    <!-- Left page column. -->
    <div class='col-sm-9'>
      <div class='card'>
      <div class='card-header bg-dark text-light'>
          <h3 class='card-title text-center pt-2'>All Events</h3>
        </div>
        <div class='card-body'>
          <table class=' table table-striped table-hover border-start border-end border-top'>
            <thead>
              <th scope='col'>Host</th>
              <th scope='col'>Event Title</th>
              <th scope='col'>Event Date</th>
              <th scope='col'>Event Description</th>
              <th scope='col'>Guests</th>
            </thead>
            <tbody>".
              $table_rows.
            "</tbody>
          </table>
        </div>
      </div>  
    </div>
  </div>";

  $db->close();
  $page->set_content($content);
  $page->render();

?>