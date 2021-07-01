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
  $db = new Database();
  $db = &$db->get_handle();

  
  // Retrieve all the events from the database. 
  // $events = $events_db->get_events();
  $query = 'SELECT title, event_date, description FROM events';
  $stmt = $db->prepare($query);
  $stmt->execute();
  $stmt->store_result();
  $stmt->bind_result($event_title, $event_date, $event_description);


  // create a long string which holds all of the rows for the table. 
  $table_rows = '';
  if($stmt->num_rows > 0){
    while($stmt->fetch()){
      $table_rows.="<tr>
      <td> Bradley Showen </td>
      <td> $event_title </td>
      <td> $event_date </td>
      <td> $event_description </td>
      <td> 0 </td>
    </tr>";
    }
  }

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