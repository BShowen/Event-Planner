<?php
// Get the document root. 
$document_root = $_SERVER['DOCUMENT_ROOT'];

require $document_root.'/database/FlatFileInterface.php';
require $document_root.'/errors/FileSaveError.php';

class EventTable implements FlatFileInterface {
  
  // Private class attributes used for holding class state. 
  private $file_path;
  private $file;
  private $file_mode;

  function __construct($path, $mode){
    $this->file_path = $path;
    $this->file_mode = $mode;
  }

  function get_events($number_of_events = -1){
    $this->open_file();
    rewind($this->file);
    // Index is used as the index into an array inside of the following loop. 
    $index = 0;
    // I store the data I want inside this array. 
    $events = []; 
    // I don't use feof($file) in my while loop. feof() returns false even for 
    // an empty file because I haven't actually read past the last line. So
    // if the file is empty, it will still try to read from it and this 
    // causes a bug. 
    // while(!feof($this->file)){
    while($current_event = fgets($this->file)){
      $current_event = explode("\t", $current_event);
      $events[$index] = [
        "event_title"=>$current_event[0],
        "event_date"=>$current_event[1],
        "event_description"=>str_replace("*r*n", "\r\n", $current_event[2])
      ];
      $index++;

      // If the index is equal to the $number_of_events then we need to stop reading. 
      // $index < $number_of_events will always be true, unless you specify the number
      // of events that you want to retrieve when you call this method. 
      // For example get_events(); retrieves all the events and
      // get_events(5) retrieves 5 events. 
      if($index == $number_of_events){
        break;
      }
    }
    $this->close_file();
    return $events;
  }

  function get_last_event(){
    $events = $this->get_events();
    return $events[count($events) - 1];
  }

  function save_event($event){
    $this->open_file();
    try{
      if(!fwrite($this->file, $event)){
        throw new FileSaveError("We could not save your information. Please try again.");
      }
    }catch(FileSaveError $e){
      echo $e;
      $this->close_file();
      exit;
    }
    $this->close_file();
  }

  // This method is an implementation of the method from the abstract class. 
  function close_file(){
    // Close the database connection.
    fclose($this->file);
  }

  // This method is an implementation of the method from the abstract class. 
  function open_file(){
    // Connect to the database (open the file).
    $this->file = fopen("$this->file_path",$this->file_mode);
  }
}
?>