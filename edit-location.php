<?php
session_start();
require_once 'config.php';

$location_name = $location_coordinate = $location_min_time = $location_description = '';
$location_name_error = $location_coordinate_error = $location_min_time_error = $location_description_error = '';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $get_location_id = $_GET['id'];

    $get_location_information_sql = 'SELECT location_name, coordinate, minimum_time, description FROM m02_location WHERE location_id = ?';
    
    if ($get_location_information_stmt = mysqli_prepare($conn, $get_location_information_sql)) {
        mysqli_stmt_bind_param($get_location_information_stmt, 'i', $param_location_id);

        $param_location_id = $get_location_id;

        if (mysqli_stmt_execute($get_location_information_stmt)) {
            mysqli_stmt_store_result($get_location_information_stmt);

            if (mysqli_stmt_num_rows($get_location_information_stmt) > 0) {
                mysqli_stmt_bind_result($get_location_information_stmt, $saved_location_name, $saved_location_coordinate, $saved_location_minimum_time, $saved_location_description);

                mysqli_stmt_fetch($get_location_information_stmt);

            } else {
                $_SESSION['m02_location_not_found'] = TRUE;
                header('location: manage-location');

            }
        }
    }

    mysqli_stmt_close($get_location_information_stmt);

} else {
    // Main functionality of Edit Location
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];

        //Location name: Validate and submit
        if (empty(trim($_POST['LocationName']))) {
            $location_name_error = "Please enter the location name.";
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

        // Update location information to database
        if (empty($location_name_error) && empty($location_coordinate_error) && empty($location_min_time_error) && empty($location_description_error)) {
            $update_location_sql = 'UPDATE m02_location SET location_name="' . $location_name . '", coordinate="' . $location_coordinate . '", minimum_time=' . $location_min_time . ', description="' .$location_description . '" WHERE location_id=' . $id;
        
            if(mysqli_query($conn, $update_location_sql)) {
                $_SESSION['m02_edit_location_success'] = TRUE;
                header('location: manage-location');

                unset($_POST);
            } else {
                echo 'Error: ' . $update_location_sql . '<br/>' .mysqli_error($conn);
            }

        } else {
            $update_error = TRUE;
        }
    } else {
        header('location: manage-location');

    }
}

?>

<html>

<head>
    <title>Edit Location | Tour Management</title>

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

<body id="UpdateLocation">
    <?php include 'header.php'; ?>

    <!-- Edit Location Field -->
    <h1 class="text-center mt-3">Edit Location</h1>

    <div class="container">

        <!-- Form shows only when user selects location -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="hidden" id="id" name="id" value="<?php echo !empty($_POST['id']) ? $_POST['id'] : $_GET['id']; ?>">

            <div id="LocationInfo" class="<?php echo isset($update_error) && $update_error == TRUE ? 'd-block' : ''; ?> ">
                <div class="form-group row">
                    <label for="LocationName" class="col-sm-2 col-form-label">Location Name</label>
                
                    <div class="col-sm-10">
                        <input type="text" class="form-control <?php echo (!empty($location_name_error)) ? 'border border-danger' : ''; ?>" id="LocationName" name="LocationName" placeholder="Location Name" value="<?php echo !empty($_POST['LocationName']) ? $_POST['LocationName'] : $saved_location_name; ?>">
                    
                        <p class="text-danger">
                            <?php echo $location_name_error; ?>
                        </p>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="Coordinate" class="col-sm-2 col-form-label">Coordinate</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control <?php echo (!empty($location_coordinate_error)) ? 'border border-danger' : ''; ?>" id="Coordinate" name="Coordinate" placeholder="Enter Coordinate" value="<?php echo !empty($_POST['Coordinate']) ? $_POST['Coordinate'] : $saved_location_coordinate; ?>">
                    
                        <p class="text-danger">
                            <?php echo $location_coordinate_error; ?>
                        </p>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="MinTime" class="col-sm-2 col-form-label">Minimum Time</label>
                    
                    <div class="col-sm-10">
                        <input type="number" class="form-control <?php echo (!empty($location_min_time_error)) ? 'border border-danger' : ''; ?>" id="MinTime" name="MinTime" placeholder="Enter Minimum Time" value="<?php echo !empty($_POST['MinTime']) ? $_POST['MinTime'] : $saved_location_minimum_time; ?>">
                    
                        <p class="text-danger">
                            <?php echo $location_min_time_error; ?>
                        </p>
                    </div>
                </div>
                
                <div class="form-row row">
                    <label for="Description" class="col-sm-2 col-form-label">Description</label>

                    <div class="col-sm-10">
                        <textarea class="form-control <?php echo (!empty($location_description_error)) ? 'border border-danger' : ''; ?>" id="Description" name="Description" placeholder="Enter Description" rows="10" cols="30"><?php echo !empty($_POST['Description']) ? $_POST['Description'] : $saved_location_description; ?></textarea>
                    
                        <p class="text-danger">
                            <?php echo $location_description_error; ?>
                        </p>
                    </div>
                </div>
            </div>
                
            <button id="UpdateButton" type="submit" class="btn btn-primary btn-block">Submit</button>
        </form>
    </div>

    <!-- Edit Location Field -->

    <?php include 'footer.php'; ?>
</body>

</html>

<?php mysqli_close($conn); ?>