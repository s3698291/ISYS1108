<?php 
session_start();
require_once 'config.php';

$location_name = $location_coordinate = $location_min_time = $location_description = '';
$location_name_error = $location_coordinate_error = $location_min_time_error = $location_description_error = '';

/*if (!isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] != TRUE) {
    header('location: login.php');

} elseif (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == 'TRUE') {*/
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //Location name: Validate and submit
        if (empty(trim($_POST['LocationName']))) {
            $location_name_error = "Please enter a location name.";
        } else {
            $location_name = (trim($_POST['LocationName']));
        }

        //Coordinate: Validate and submit
        if (empty(trim($_POST['Coordinate']))) {
            $location_coordinate_error = "Please enter location coordinates.";
        } else {
            $location_coordinate = (trim($_POST['Coordinate']));
        }

        //Minimum Time: Validate and submit
        if (empty(trim($_POST['MinTime']))) {
            $location_min_time_error = "Please enter the minimum time.";
        } else {
            $location_min_time = (trim($_POST['MinTime']));
        }

        //Description: Validate and submit
        if (empty(trim($_POST['Description']))) {
            $location_description_error = "Please enter a description of the location.";
        } else {
            $location_description = (trim($_POST['Description']));
        }

        // Send location information to database
        if (empty($location_name_error) && empty($location_coordinate_error) && empty($location_min_time_error) && empty($location_description_error)) {
            $addLocationSql = "INSERT INTO m02_location (location_name, coordinate, minimum_time, description) VALUES ('" . $location_name . "', '" . $location_coordinate . "', " . $location_min_time . ", '" . $location_description . "')";
        
            if ($stmt = mysqli_prepare($conn, $addLocationSql)) {
                mysqli_stmt_bind_param($stmt, 'ssis', $param_location_name, $param_coordinate, $param_minimum_time, $param_description);
                
                $param_location_name = $location_name;
                $param_coordinate = $location_coordinate;
                $param_minimum_time = $location_min_time;
                $param_description = $location_description;

                if (mysqli_stmt_execute($stmt)) {
                    $location_Added = TRUE;
                    unset($_POST);
                } else {
                    echo 'Error: ' . $addLocationSql . '<br/>' .mysqli_error($conn);
                }
                
                
            }

        } 
    }
//}
?>

<html>

<head>
    <title>Add Location | Tour Management</title>

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

    <!-- Location Fields -->
    <h1 class="text-center mt-3">Add Location</h1>

    <div class="container">
        <?php 
        if ($location_Added === TRUE) {
            echo '
            <div class="alert alert-success my-4 alert-dismissable fade show" role="alert">
            Location added successfully.

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            ';
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <div class="form-group">
                    <label for="LocationName">Location Name</label>
                    <input type="text" class="form-control <?php echo (!empty($location_name_error)) ? 'border border-danger' : ''; ?>" id="LocationName" name="LocationName" placeholder="Add Location Name" value="<?php echo $_POST['LocationName']; ?>">
                    
                    <div>
                        <p class="text-danger">
                            <?php echo $location_name_error; ?>
                        </p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="Coordinate">Coordinate</label>
                    <input type="text" class="form-control <?php echo (!empty($location_coordinate_error)) ? 'border border-danger' : ''; ?>" id="Coordinate" name="Coordinate" placeholder="Enter Coordinate" value="<?php echo $_POST['Coordinate']; ?>">
                
                    <div>
                        <p class="text-danger">
                            <?php echo $location_coordinate_error; ?>
                        </p>
                    </div>
                </div>
            
                <div class="form-group">
                    <label for="MinTime">MinTime</label>
                    <input type="number" class="form-control <?php echo (!empty($location_min_time_error)) ? 'border border-danger' : ''; ?>" id="MinTime" name="MinTime" placeholder="Enter MinTime" value="<?php echo $_POST['MinTime']; ?>">
                
                    <div>
                        <p class="text-danger">
                            <?php echo $location_min_time_error; ?>
                        </p>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="col">
                        <label for="Description">Description</label>
                        <textarea class="form-control <?php echo (!empty($location_description_error)) ? 'border border-danger' : ''; ?>" id="Description" name="Description" placeholder="Enter Description" rows="10" cols="30" value="<?php echo $_POST['Description']; ?>"> </textarea>
                    </div>

                    <div>
                        <p class="text-danger">
                            <?php echo $location_description_error; ?>
                        </p>
                    </div>
                </div>
                
                <button type="submit" name="submit" class="btn btn-primary btn-block">Submit</button>
            </div>
        </form>
    </div>
    <!-- Location Fields -->

    <?php include 'footer.php'; ?>
</body>

</html>

<?php mysqli_close($conn); ?>