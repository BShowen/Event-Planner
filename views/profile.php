<?php 
// Get the base file path.
$document_root = $_SERVER['DOCUMENT_ROOT'];

// Require in the template for the website
// and instantiate a page object. 
require $document_root.'/models/Page.php';
$page = new Page($title = "profile");
$page->render_header();

// Require in the User object. 
require $document_root.'/models/User.php';

// Retrieve the cookie form the users browser and set the current user
// to this user. 
$current_user = new User(intval($_COOKIE['auth']));

// This sets a constant that is used to generate content for the page based on this value.]
// If set to true then the user will be able to edit their profile. 
// If set to false then the user is simply viewing their profile. 
define('EDITING', isset($_POST['edit_profile']));

// The button text for editing/saving the users profile information is determined
// by whether or not the user is in edit mode or viewing mode. 
$button_text = EDITING ? "Save" : "Edit profile";
?>

<!-- container for page content. It is also the first (and only) row in the page. -->
<div class='row mt-3 justify-content-center'>
  <!-- This is the first (and only) column on this page. -->
  <div class='card col-sm-5 col-md-4 col-xl-3 col-xxl-3'>
    <div class='row '>
      <div class='col-sm-6 col-md-6 mt-2'>
        <img src='./Photos/img.png' alt='User photo' class='card-img-top img-thumbnail'>
      </div>
    </div>
    <div class='card-body'>
      <!-- This form is a hack. It allows me to send a POST request on the click of a button. 
      Alternatively I should be using javaScript. However, this is not a JavaScript class and I don't want to 
      lose points. -->
      <form action="./profile.php" method="POST">
      <!-- If the user is editing their profile then I need to render a form so they can edit their details. -->
      <!-- Otherwise, I simply need to display their profile information. -->
      <?php if(EDITING){ ?>
        <div class="row">
          <div class="col">
            <label for="first_name">First Name</label>
            <input name="first_name" id="first_name" type="text" class="form-control" placeholder="<?php echo $current_user->first_name; ?>" aria-label="First Name">
          </div>
          <div class="col">
            <label for="last_name">Last Name</label>
            <input name="last_name" id="last_name" type="text" class="form-control" placeholder="<?php echo $current_user->last_name; ?>" aria-label="Last Name">
          </div>
        </div>
        <div class="row">
          <div class="col">
            <label for="email_address">Email</label>
            <input name="email" id="email" type="email" class="form-control" placeholder="<?php echo $current_user->email; ?>" aria-label="Email">
          </div>
        </div>
      <?php }else{ ?>
        <select name='edit_profile' hidden>
          <option value="0" selected>as</option>
        </select>
        <h1 class='card-title'>
        <?php echo $current_user->first_name.' '.$current_user->last_name; ?>
        </h1>
        <p class='card-text'>Email: <?php echo $current_user->email ?></p>
      <?php } ?>
        <button class='btn btn-primary btn-sm mt-1' type="submit">
          <?php echo $button_text; ?>
          <span class='bi bi-pen'></span>
        </button>
      </form>
    </div>
  </div>
</div>

<?php
  $page->render_footer();
?>