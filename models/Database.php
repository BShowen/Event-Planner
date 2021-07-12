<?php  
class Database {
  public $db = null;
  
  function __construct(){
    // Get the base file path. 
    $document_root = $_SERVER['DOCUMENT_ROOT'];
    // Require in the config file for retrieving sensitive data. 
    require $document_root."/.config.php";
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