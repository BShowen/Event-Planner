<?php 
// Get the base file path.
$document_root = $_SERVER['DOCUMENT_ROOT'];

// Require in the template for the website
// and instantiate a page object. 
require $document_root.'/models/Page.php';
$page = new Page($title = "friends");
$page->render_header();

// Require in the User object. 
require $document_root.'/models/User.php';

// Retrieve the cookie form the users browser and set the current user
// to this user. 
$current_user = new User(intval($_COOKIE['auth']));

// Retrieve the filter selection and set the dropdown selection to the currently selected filter selection. 
// Select users from the database that meat the selection criteria. 
$selection = isset($_POST['selection']) ? intval($_POST['selection']) : 0 ; 
// Get a handle on the database.
$db = (new Database())->get_handle();
switch ($selection){
  case 1:
    echo "Selected 1";
    break;
  case 2:
    echo "Selected 2";
    break;
  default: 
    $query = 'SELECT first, last FROM users WHERE userid != ?';
    $stmt = $db->prepare($query);
    $user_id = $current_user->user_id;
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($first, $last);
    $users = [];
    while($stmt->fetch()){
      array_push($users, [$first, $last]);
    }
}

// Iterate through each of the users and create a table row.
$table_rows = '';
foreach($users as $user){
  // 
  list($first, $last) = $user;
  $table_rows.="<tr>
    <td> $first</td>
    <td> $last </td>
  </tr>";
}

$option_values = ['All Users', 'Friends', 'Not Friends'];
$options = "";
for($i = 0; $i <= 2; $i++){
  if($i == $selection){
    $options.="<option value=$i selected>$option_values[$i]</option>";
  }else{
    $options.="<option value=$i>$option_values[$i]</option>";
  }
}
?>

<div class='row mt-3 justify-content-center'>
  <!-- Single page column. -->
  <div class='col-sm-9'>
    <div class='card'>
      <div class='card-header bg-dark text-light'>
        <h3 class='card-title text-center pt-2'>Users</h3>
      </div>
      <div class='card-body'>
        <div class='card'>
          <div class='card-body'>
            <form action='./friends.php' method='POST'>
              <div class='row'>
                <label class='col-sm-2 col-lg-2 text-end'>Filter users:</label>
                <div class='col-sm-9 col-lg-3'>
                  <select name='selection' class='form-select form-select-sm' aria-label='.form-select-sm example'>
                  <?php
                    echo $options;
                  ?>
                  </select>
                </div>
                <div class='col-sm-1'>
                  <button class='btn btn-primary btn-sm'><span class='bi bi-search'></span></button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <table class='table table-striped table-hover border-start border-end border-top mt-3'>
          <thead>
            <th scope='col'>First name</th>
            <th scope='col'>Last name</th>
          </thead>
          <tbody>
            <?php
            echo $table_rows;
            ?>
          </tbody>
        </table>
      </div>
    </div>  
  </div>
</div>

<?php 
  $page->render_footer();
?>