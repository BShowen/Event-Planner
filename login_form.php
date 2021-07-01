<?php
class LoginForm{
  private $content = 
  "<div class='row mt-3 justify-content-center'>
    <!-- Left page column. -->
    <div class='col-sm-12 col-lg-7 d-flex justify-content-center'> 
      <div class='col-sm-12 col-md-7 col-lg-8 justify-content-center'>
        <div class='card bg-light border-0'>
          <div class='card-header bg-dark text-light'>
            <h3 class='card-title text-center'>Login to Event Planner</h3>
          </div>
          <div class='card-body'>
            <form action='./LoginHandler.php' method='POST'>
              <div class='row justify-content-center'>
                <div class='col-sm-8'>
                  <label for='emailAddress' class='form-label mt-2'>Email address</label>
                  <input type='text' class='form-control' name='email_address' id='emailAddress'>
                  
                  <label for='password' class='form-label mt-2'>Password</label>
                  <input type='password' class='form-control' name='password' id='password'>
                  
                  <input class='btn btn-primary mt-2' type='submit' value='Submit'>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>";

  function render(){
    echo $this->content;
  }
} 
?>