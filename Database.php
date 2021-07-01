<?php  
class Database {
  public $db = null;
    
  function __construct(){
    /*Require in the config file for retrieving sensitive data. 
    This is the correct filename. 
    The period (.) at the beginning of .config.php indicates that 
    this file is hidden from view in the operating system. */
    require("./.config.php");
    try{
      $this->db = new mysqli($CONFIG['HOST'], $CONFIG['USER_NAME'], $CONFIG['PASSWORD'], $CONFIG['DB_NAME']); 
      if(mysqli_connect_errno()){
        throw new Error('There was an error connecting to the DB.<br/>Check your environment vars.');
      }
    }catch(Error $e){
      echo $e->getMessage().'<br/>';
      exit();
    }
  }

  function &get_handle(){
    return $this->db;
  }

}
?>