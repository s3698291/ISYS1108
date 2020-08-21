<?php 
session_start();
require_once 'config.php';

/*if (!isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] != TRUE) {
    header('location: login.php');

} elseif (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == 'TRUE') {*/
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
          $remove_location_sql = 'UPDATE m02_location SET is_deleted = 1 WHERE location_id ' . $_GET['id'];

          if (mysqli_query($conn, $remove_location_sql)) {
            $removed = TRUE;

          } else {
              echo 'Error: ' . $remove_location_sql . '</br>' . mysqli_error($conn);
          }

        break;

        case 'copy':
          $copy_location_sql = 'INSERT INTO m02_location (location_name, coordinate, minimum_time, description) VALUES ("' . $location_name . '", "' . $location_coordinate . '", "' . $location_min_time . '", "' . $location_description . '")';

          if (mysqli_query($conn, $copy_location_sql)) {
            $copied = TRUE;

          } else { 
              echo 'Error: ' . $copy_location_sql . '</br>' . mysqli_error($conn);
          }

        break;

      }
    }

  // }

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

    <h1 class="text-center mt-3"> Manage Locations </h1>
 
    <div class="container">
        <!-- Recover Location Table -->
        <?php
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
        ?>

        <?php
        $get_removed_location_sql = 'SELECT * FROM m02_location WHERE is_deleted = 1 ORDER BY location_id ASC';
        $get_removed_location = mysqli_query($conn, $get_removed_location_sql);

        if (mysqli_num_rows($get_removed_location) > 0) {
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

          while ($removed_location = mysqli_fetch_assoc($get_removed_location)) {
            echo '
            <tr>
              <td class="align-middle">' . $removed_location['LocationId'] . '</td>
              <td class="align-middle">' . $removed_location['LocationName'] . '</td>
              <td class="align-middle">' . $removed_location['Coordinate'] . '</td>
              <td class="align-middle">' . $removed_location['MinTime'] . '</td>
              <td class="align-middle">' . $removed_location['Description'] . '</td>
              
              <td class="align-middle">
                <a class="btn btn-primary" href="manage-location.php?action=recover&id=' . $removed_location['LocationId'] . '">Recover</a>
              </td>
            </tr>
            ';
          }

          echo '
                </tbody>
              </table>
          </div>
          ';

        } else {
            echo '
              <div class="jumbotron text-center mt-4">
                <h3>No Removed Location Found</h3>
              </div>
            ';
        }
        ?>
        <!-- Recover Location Table -->


        <!-- Remove/Copy/Edit Location Table -->

        <?php
        if ($removed === TRUE){
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
        ?>

        <?php
        $get_location_sql = 'SELECT * FROM m02_location WHERE is_deleted = 0 ORDER BY location_name ASC';
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

          while ($location = mysqli_fetch_assoc($get_location)) {
            echo '
            <tr>
              <td class="align-middle">' . $location['LocationId'] . '</td>
              <td class="align-middle">' . $location['LocationName'] . '</td>
              <td class="align-middle">' . $location['Coordinate'] . '</td>
              <td class="align-middle">' . $location['MinTime'] . '</td>
              <td class="align-middle">' . $location['Description'] . '</td>
              
              <td class="align-middle">
                <a class="btn btn-primary" href="manage-location.php?action=remove&id=' . $location['LocationId'] . '">Remove</a>
                <a class="btn btn-primary" href="manage-location.php?action=copy&id=' . $location['LocationId'] . '">Copy</a>
              </td>
            </tr>
            ';
          }

          echo '
                </tbody>
              </table>
          </div>
          ';

        } else {
            echo '
              <div class="jumbotron text-center mt-4">
                <h3>No Existing Location Found</h3>
              </div>
            ';
        }
        ?>  

        <!-- Remove/Copy/Edit Location Table -->


        <!-- <div class="table-body">
            <table id="manageTable" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Location Name</th>
                <th>Coordinate</th>
                <th>Min. Time(min)</th>
                <th>Description</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>National Gallery Victoria</td>
                <td>x: 6.1912° y: 106.7675°</td>
                <td>20</td>
                <td>testing..</td>
                <td>
                    <button type="button" class="btn btn-outline-dark">edit</button>
                    <button type="button" class="btn btn-outline-dark">copy</button>
                    <button type="button" class="btn btn-outline-dark">delete</button>
                </td>
              </tr>
            </tbody>
            </table>
        </div>
    </div>

    -->

    <?php include 'footer.php'; ?>
</body>

</html>

<?php mysqli_close($conn); ?>