<?php
  /* High level overview of this PHP file. I am retrieving form data, 
  storing that data in a flat file, and then reading that data back and 
  displaying it to the user. */

  // Get the base file path. 
  $document_root = $_SERVER['DOCUMENT_ROOT'];

  // Require in the Event class to create a new Event object. 
  require $document_root.'/models/Event.php';
  // Require in the EventTable class.
  require $document_root.'/database/EventTable.php';
  // Require in the ConfirmationCard component. 
  require $document_root.'/partials/ConfirmationCard.php';
  // Require in the page header. 
  require $document_root.'/partials/Header.php';
  // Require in page navigation.
  require $document_root.'/partials/SiteNav.php';
  // Require in the page Footer.
  require $document_root.'/partials/Footer.php';
  

  // Instantiate an EventTable class so I can retrieve data from the database (flat file).
  $event_db = new EventTable("$document_root/flat_db/events.txt", "a+b");
  
  // Retrieve the form data and store it in variables to be used later.
  $event_title = trim($_POST['event_title']);
  $event_date = trim($_POST['event_date']);
  /*I replace all occurrences of new line characters.
  This way an Event is saved in a flat file as a single line. 
  I can read this file easier later on.
  An alternative is to use JavaScript to disable the "ENTER" key
  inside my form's <textarea> */
  $event_description = str_replace("\r\n", "*r*n", trim($_POST['event_description']));

  // Don't mind this. I was debugging. I couldn't read/write when I uploaded 
  // my code to the seminole state server.
  // $file_path = "$document_root/flat_db/events.txt";
  // if(!file_exists($file_path)){
  //   echo "FILE NOT FOUND";
  // }elseif (!fopen("$document_root/flat_db/events.txt", "a+b")) {
  //   echo "CANT OPEN FILE";
  // }
  
  // Create a new Event object which holds the data for a particular event. 
  $new_event = new Event($event_title, $event_date, $event_description);
  if($new_event->valid()){
    // Write the new string (row) to the file. 
    // The return value from toString() is properly formatted to be saved in a flat file. 
    $event_db->save_event($new_event->__toString());

    /* Clear the form data out of the variables. 
    I will read the data back from the database. 
    This ensures the data was saved correctly.*/
    $event_title = $event_date = $event_description = '';
    // This is valid syntax according to the book, Page 29 - Values Returned from Assignment. 
    // I mention this because I have never seen this syntax. I am new to php. 

    // Read the newly saved event from the database. 
    $saved_event = $event_db->get_last_event();

    // Convert the array to scalar values. 
    extract($saved_event);
  }else{
    $new_event->display_errors();
    exit;
  }

  

  // Instantiate a navigation component. 
  $nav = new SiteNav("dashboard");

  Header::render();
?>
<!-- This is the container for the page content. -->
<div class="container-fluid">
  
  <?php 
    // Display the navigation component. 
    $nav->render(); 
  ?>

  <!-- Row 2 for page content -->
  <div class="row mt-3 justify-content-center">
    <div class="col-xs- 12 col-sm-9 col-md-6 col-lg-3">
      <?php 
        $card = new ConfirmationCard($event_title, $event_date, $event_description);
        $card->render();
      ?>
    </div>
  </div>
</div>

<?php
  Footer::render();
?>
