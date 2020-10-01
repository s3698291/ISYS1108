<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['m02_loggedIn']) && $_SESSION['m02_loggedIn'] == 'TRUE') {
    // Manage Tour Type functionalities: Recover, Remove, Edit
    if (isset($_GET['action']) && !empty($_GET['action'])) {
        switch ($_GET['action']) {
          case 'recover':
            $recover_type_sql = 'UPDATE m02_type SET is_deleted = 0 WHERE type_id = ' . $_GET['id'];
  
            if (mysqli_query($conn, $recover_type_sql)) {
                $recovered = TRUE;
            
            } else {
                echo 'Error: ' . $recover_type_sql . '</br>' . mysqli_error($conn);
            }
  
          break;
  
          case 'remove':
            $remove_type_sql = 'UPDATE m02_type SET is_deleted = 1 WHERE type_id = ' . $_GET['id'];
  
            if (mysqli_query($conn, $remove_type_sql)) {
              $removed = TRUE;
  
            } else {
                echo 'Error: ' . $remove_type_sql . '</br>' . mysqli_error($conn);
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
    <title>Manage Tour Type | Tour Management</title>

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
		<h2> Manage Tour Type </h2>
		
        <!-- Recover/Remove/Edit Tour Validation -->
        <?php
        if ($_SESSION['m02_edit_type_success'] === TRUE) {
            echo '
            <div class="alert alert-success my-4 alert-dismissible fade show" role="alert">
                Tour Type edited successfully.

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            ';
            
            unset($_SESSION['m02_edit_type_success']);
        }

        if ($_SESSION['m02_type_not_found'] === TRUE) {
            echo '
            <div class="alert alert-warning my-4 alert-dismissible fade show" role="alert">
                Tour Type not found.

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            ';
            
            unset($_SESSION['m02_type_not_found']);
        }

        if ($recovered === TRUE){
          echo '
          <div class="alert alert-success my-4 alert-dismissable fade show" role="alert">
          Tour Type recovered successfully.

              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>

          </div>
          ';
        }
        
        elseif ($removed === TRUE){
          echo '
          <div class="alert alert-success my-4 alert-dismissable fade show" role="alert">
          Tour Type removed successfully.

              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>

          </div>
          ';
        }
        
        $get_type_sql = 'SELECT * FROM m02_type ORDER BY is_deleted, type_id';
        $get_type = mysqli_query($conn, $get_type_sql);

        if (mysqli_num_rows($get_type) > 0) {
            echo '
            <div class="table-responsive">
                <table class="table table-hover text-center">
                    <thead>
                        <tr>
                            <th scope="col" class="align-middle">ID</th>
                            <th scope="col" class="align-middle">Tour Type</th>
                            <th scope="col" class="align-middle">Action</th>
                        </tr>
                    </thead>

                    <tbody>
            '; 

            while ($type_list = mysqli_fetch_assoc($get_type)) {
                echo '
                        <tr>
                            <td class="align-middle">' . $type_list['type_id'] . '</td>
                            <td class="align-middle">' . $type_list['tour_type'] . '</td>
                            <td class="align-middle">

                ';

                if ($type_list['is_deleted'] == 1) {
                    echo '
                    <a class="btn btn-primary" href="manage-tour-types?action=recover&id=' . $type_list['type_id'] . '">Recover</a>
                    ';
                } elseif ($type_list['is_deleted'] == 0) {
                    echo '
                    <a class="btn btn-primary" href="edit-type?id=' . $type_list['type_id'] . '">Edit</a>

                    <a class="btn btn-primary" href="manage-tour-types?action=remove&id=' . $type_list['type_id'] . '">Remove</a>                    
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

        <?php include 'footer.php'; ?>
</body>

</html>

<?php mysqli_close($conn); ?>