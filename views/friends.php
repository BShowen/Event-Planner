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
    // Display only users who are friends. 
    $users = $current_user->friends;
    break;
  case 2:
    // Display only users who are not friends.  
    $query = (
      "SELECT userid
      FROM users
      WHERE userid NOT IN (SELECT userid0 FROM users_friends WHERE userid1 = ?
                          UNION 
                          SELECT userid1 FROM users_friends WHERE userid0 = ?)
                          AND userid != ?"
    );
    $stmt = $db->prepare($query);
    $current_user_id = $current_user->user_id;
    $stmt->bind_param('iii', $current_user_id, $current_user_id, $current_user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($other_user_id);
    $users = [];
    while($stmt->fetch()){
      array_push($users, new User(intval($other_user_id)));
    }
    break;
  default: 
  // Select all the users from the database. 
    $query = 'SELECT userid FROM users WHERE userid != ?';
    $stmt = $db->prepare($query);
    $user_id = $current_user->user_id;
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($other_user_id);
    $users = [];
    while($stmt->fetch()){
      array_push($users, new User(intval($other_user_id)));
    }
}

// Iterate through each of the users and create a table row.
$table_rows = '';
foreach($users as $user){
  $table_rows.="<tr>
    <td> $user->first_name </td>
    <td> $user->last_name </td>
    <td>
      <button type='button' class='btn btn-primary btn-sm'>
        <span class='bi bi-person-plus'></span>
      </button>
    </td>
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
  <div class='col-sm-12 col-md-10 col-lg-8 col-xl-6 col-xxl-4'>
    <div class='card'>
      <div class='card-header bg-dark text-light'>
        <h3 class='card-title text-center pt-2'>Users</h3>
      </div>
      <div class='card-body'>
        <div class='card'>
          <div class='card-body'>
            <form action='./friends.php' method='POST'>
              <div class='row'>
                <label class='col-sm-3 text-end'>Filter users:</label>
                <div class='col-sm-5'>
                  <select name='selection' class='form-select form-select-sm' aria-label='.form-select-sm example'>
                  <?php
                    echo $options;
                  ?>
                  </select>
                </div>
                <div class='col-sm-2'>
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
            <th scope='col'>Action</th>
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