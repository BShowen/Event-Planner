<?php
class UserTable implements FlatFileInterface {
  
  // Private class attributes used for holding class state. 
  private $file_path;
  private $file;
  private $file_mode;

  // Constructor is used to set the initial state of the object. 
  function __construct($path, $mode){
    $this->file_path = $path;
    $this->file_mode = $mode;
    $this->open_file();
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

  //This function returns the current user. 
  // Right now there is no logic to this method and there is only one user. So getting
  // the current user is as simple as reading the only line in the Users.txt file. 
  // Once I am using SQL I will change the implementation of this method. 
  function get_current_user(){
    // Get the users name from the database 
    return ucwords(fgetcsv($this->file, 0, "\n")[0]);
  }

}
?>