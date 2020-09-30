<?php 
session_start();
require_once 'config.php';

if (isset($_SESSION['m02_loggedIn']) && $_SESSION['m02_loggedIn'] == 'TRUE') {
    // Manage Location functionalities: Recover, Remove, Copy, Edit
    if (isset($_GET['action']) && !empty($_GET['action'])) {
      switch ($_GET['action']) {
        case 'recover':
          $recover_location_sql = 'UPDATE m02_location SET is_deleted = 0 WHERE location_id = ' . $_GET['id'];

          if (mysqli_query($conn, $recover_location_sql)) {
              $recovered = TRUE;
          
          } else {
              echo 'Error: ' . $recover_location_sql . '</br>' . mysqli_error($conn);
          }

        break;

        case 'remove':
          $remove_location_sql = 'UPDATE m02_location SET is_deleted = 1 WHERE location_id = ' . $_GET['id'];

          if (mysqli_query($conn, $remove_location_sql)) {
            $removed = TRUE;

          } else {
              echo 'Error: ' . $remove_location_sql . '</br>' . mysqli_error($conn);
          }

        break;

        case 'copy':
            $location_name = trim($_GET['location_name']);
            $location_coordinate = trim($_GET['coordinate']);
            $location_min_time = trim($_GET['minimum_time']);
            $location_description = trim($_GET['description']);

          $copy_location_sql = 'INSERT INTO m02_location (location_name, coordinate, minimum_time, description) VALUES ("' . $location_name . '", "' . $location_coordinate . '", ' . $location_min_time . ', "' . $location_description . '")';

          if (mysqli_query($conn, $copy_location_sql)) {
            $copied = TRUE;

          } else { 
              echo 'Error: ' . $copy_location_sql . '</br>' . mysqli_error($conn);
          }

        break;

      }
    }

} else {
	header('location: /m02/login');
}

?>

<html>

<head>
    <title>Manage Location | Tour Management</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- JavaScript from Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

    <!-- CSS from Bootstrap -->
    <link rel="stylesheet" type="text/css" href="style/bootstrap.css">

    <!-- Individualised CSS -->
    <link rel="stylesheet" type="text/css" href="style/style.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
</head>

<body>
    <?php include 'header.php'; ?>
 
    <div class="container sticky-footer">
		<h1 class="text-center mt-3"> Manage Locations </h1>
		
        <!-- Recover/Remove/Copy/Edit Location Validation -->
        <?php
        if ($_SESSION['m02_edit_location_success'] === TRUE) {
            echo '
            <div class="alert alert-success my-4 alert-dismissible fade show" role="alert">
                Location edited successfully.

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            ';
            
            unset($_SESSION['m02_edit_location_success']);
        }

        if ($_SESSION['m02_location_not_found'] === TRUE) {
            echo '
            <div class="alert alert-warning my-4 alert-dismissible fade show" role="alert">
                Location not found.

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            ';
            
            unset($_SESSION['m02_location_not_found']);
        }

        if ($recovered === TRUE){
          echo '
          <div class="alert alert-success my-4 alert-dismissable fade show" role="alert">
          Location recovered successfully.

              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>

          </div>
          ';
        }
        
        elseif ($removed === TRUE){
          echo '
          <div class="alert alert-success my-4 alert-dismissable fade show" role="alert">
          Location removed successfully.

              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>

          </div>
          ';
        }

        elseif ($copied === TRUE){
          echo '
          <div class="alert alert-success my-4 alert-dismissable fade show" role="alert">
          Location copied successfully.

              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>

          </div>
          ';
        }
        
        $get_location_sql = 'SELECT * FROM m02_location ORDER BY is_deleted, location_id';
        $get_location = mysqli_query($conn, $get_location_sql);

        if (mysqli_num_rows($get_location) > 0) {
            echo '
            <div class="table-responsive">
                <table class="table table-hover text-center">
                    <thead>
                        <tr>
                        <th scope="col" class="align-middle">ID</th>
                        <th scope="col" class="align-middle">Location Name</th>
                        <th scope="col" class="align-middle">Coordinate</th>
                        <th scope="col" class="align-middle">Minimum Time</th>
                        <th scope="col" class="align-middle">Description</th>
                        <th scope="col" class="align-middle">Action</th>
                        </tr>
                    </thead>

                    <tbody>
            '; 

            while ($location_list = mysqli_fetch_assoc($get_location)) {
                echo '
                <tr>
                <td class="align-middle">' . $location_list['location_id'] . '</td>
                <td class="align-middle">' . $location_list['location_name'] . '</td>
                <td class="align-middle">' . $location_list['coordinate'] . '</td>
                <td class="align-middle">' . $location_list['minimum_time'] . '</td>
                <td class="align-middle">' . $location_list['description'] . '</td>
                
                <td class="align-middle">

                ';

                if ($location_list['is_deleted'] == 1) {
                    echo '
                    <a class="btn btn-primary" href="manage-location?action=recover&id=' . $location_list['location_id'] . '">Recover</a>
                    ';
                } elseif ($location_list['is_deleted'] == 0) {
                    echo '
                    <a class="btn btn-primary" href="edit-location?id=' . $location_list['location_id'] . '">Edit</a>

                    <a class="btn btn-primary" href="manage-location?action=copy&location_name=' . $location_list['location_name'] . '&coordinate=' . htmlspecialchars($location_list['coordinate']) . '&minimum_time=' . $location_list['minimum_time'] . '&description=' . $location_list['description'] . '">Copy</a>

                    <a class="btn btn-primary" href="manage-location?action=remove&id=' . $location_list['location_id'] . '">Remove</a>                    
                    ';
                } 
                    
                echo '
                </td>
            </tr>
                ';
            }

            echo '
                    </tbody>
                </table>
            </div>
            ';
        } 

        ?>

        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>

<?php mysqli_close($conn); ?>