<div class='row'>
      <nav class='navbar navbar-expand-sm navbar-dark bg-dark pt-3 pb-3'>
        <div class='container-fluid p-0'>
          <a class='navbar-brand fs-4' href='./index.php'>Event Manager</a>
          <button
            class='navbar-toggler'
            type='button'
            data-bs-toggle='collapse'
            data-bs-target='#collapseMe'
            aria-controls='collapseMe'
            aria-expanded='false'
            aria-label='Toggle Navigation'
          >
            <span class='navbar-toggler-icon'></span>
          </button>
          <div class='collapse navbar-collapse' id='collapseMe'>
            <ul class='navbar-nav me-auto'>
              <li class='nav-item'>
                <a class='nav-link <?php $this->dashboard ?>fs-5' href='./index.php'>Dashboard</a>
              </li>
              <li class='nav-item'>
                <a class='nav-link <?php $this->events ?>fs-5' href='./events.php'>Events</a> 
              </li>
              <li class='nav-item'>
                <a class='nav-link <?php $this->profile ?>fs-5' href='#'>Profile</a> 
              </li>
              <li class='nav-item'>
                <a class='nav-link <?php $this->friends ?>fs-5' href='#'>Friends</a> 
              </li>
            </ul>
            <form action='/logout.php' method='POST' style='display:<?php $this->logout_button; ?>'>
              <button class='btn btn-primary fs-6'>Log out</button>
            </form>
            
          </div>
        </div>
      </nav> 
    </div>