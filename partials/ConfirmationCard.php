<?php
class ConfirmationCard{
  
  private $event_title;
  private $event_date;
  private $event_description;

  public function __construct($new_title, $new_date, $new_description){
    $this->event_title = $new_title;
    $this->event_date = $new_date;
    $this->event_description = $new_description;
  }

  public function render(){
    return "<div class='card'>
        <div class='card-header'>
          <h3 class='card-title'>Your event has been saved!</h3>
          <h5 class='card-subtitle'>Here are your event details.</h5>
        </div>
        <div class='card-body'>
          <p class='card-text'><span class='fw-bold'>Event title:</span> $this->event_title </p>
          <p class='card-text'><span class='fw-bold'>Event date:</span> $this->event_date </p>
          <p class='card-text'><span class='fw-bold'>Event description:</span> $this->event_description </p>
        </div>
      </div>";
  }
}
?>