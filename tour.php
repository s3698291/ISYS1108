<?php 
session_start();
require_once 'config.php';

$tour_name = $tour_type = $location1 = $location2 = $location3 = $tour_min_time = "";
$tour_name_error = $tour_type_error = $location1_error = "";

/*if (!isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] != TRUE) {
    header('location: login.php');

} elseif (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == 'TRUE') {*/
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //Tour Name: Validate and submit
        if (empty(trim($_POST['TourName']))) {
            $tour_name_error = "Please enter a tour name";
        } else {
            $tour_name = (trim($_POST['TourName']));
        }

        // Tour Type: Validate and submit
        if (empty(trim($_POST['TourType']))) {
            $tour_type_error = "Please select a tour type";
        } else {
            $tour_type = (trim($_POST['TourType']));
        }

        // Location 1: Validate and submit
        if (empty($_POST['Location1'])) {
            $location1_error = "Please select a location";
        } else {
            $location1 = $_POST['Location1'];
        }

        // Location 2 and 3: Validate and submit
        if (empty($_POST['Location2'])) {
            $location2 = 'NULL';
        } else {
            $location2 = $_POST['Location2'];
        }

        if (empty($_POST['Location3'])) {
            $location3 = 'NULL';
        } else {
            $location3 = $_POST['Location3'];
        }

        // Calculate Minimum Time
        $tour_min_time_query = "SELECT SUM(minimum_time) FROM m02_location WHERE location_id IN ('" . $location1 . "', '" . $location2 . "', '" . $location3 . "')";
        $tour_min_time_stmt = mysqli_prepare($conn, $tour_min_time_query);

        mysqli_stmt_bind_param($tour_min_time_stmt, 'iii', $location1, $location2, $location3);
        mysqli_stmt_execute($tour_min_time_stmt);
        mysqli_stmt_store_result($tour_min_time_stmt);
        mysqli_stmt_bind_result($tour_min_time_stmt, $tour_min_time);

        while (mysqli_stmt_fetch($tour_min_time_stmt)) { 
            if (empty($tour_name_error) && empty($tour_type_error) && empty($location1_error)) {
                $addTourSql = "INSERT INTO m02_tour (tour_name, tour_type, location1, location2, location3, minimum_time) VALUES ('" . $tour_name . "', '" . $tour_type . "', " . $location1 . ", " . $location2 . ", " . $location3 . ", " . $tour_min_time . ")";
                
                if ($stmt = mysqli_prepare($conn, $addTourSql)) {
                    mysqli_stmt_bind_param($stmt, 'ssiiii', $param_tour_name, $param_tour_type, $param_location1, $param_location2, $param_location3, $param_tour_min_time);

                    $param_tour_name = $tour_name;
                    $param_tour_type = $tour_type;
                    $param_location1 = $location1;
                    $param_location2 = $location2;
                    $param_location3 = $location3;
                    $param_tour_min_time = $tour_min_time;

                    if (mysqli_stmt_execute($stmt)) {
                        $tour_Added = TRUE;
                        unset($_POST);
                    } else {
                        echo 'Error: ' . $addTourSql . '</br>' . mysqli_error($conn);
                    }

                mysqli_stmt_close($stmt);

                }
            }
        }
    }

?>

<html>

<head>
    <title>Add Tour | Tour Management</title>

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

    <!-- Tour Fields -->
    <h1 class="text-center mt-3">Add Tour</h1>

    <div class="container">
        <?php
        if ($tour_Added === TRUE) {
            echo'
            <div class="alert alert-success my-4 alert-dismissable fade show" role="alert">
            Tour added successfully.

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            ';
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="TourName">Tour Name</label>

                <input type="text" class="form-control <?php echo (!empty($tour_name_error)) ? 'border border-danger' : ''; ?>" id="TourName" name="TourName" placeholder="Add Tour Name" value="<?php echo $_POST['TourName']; ?>">
            
                <div>
                    <p class="text-danger">
                        <?php echo $tour_name_error; ?>
                    </p>
                </div>
            </div>

            <!-- Tour Type Field -->
            <div class="form-group">
                <label for="TourType">Tour Type</label>

                <select class="form-control <?php echo (!empty($tour_type_error)) ? 'border border-danger' : ''; ?>" id="TourType" name="TourType">
                    <option value="" selected> Select Tour Type</option>

                    <!-- Show dropdown list from Location table-->
                    <?php 
                    $get_tour_type_sql = "SELECT * FROM WHERE is_deleted = 0 ORDER BY Tour_Type ASC";
                    $get_tour_type = mysqli_query($conn, $get_tour_type_sql);

                    if (mysqli_num_rows($get_tour_type) > 0) {
                        while ($tour_type = mysqli_fetch_assoc($get_tour_type)) {
                            $select_type = (isset($_POST['TourType']) && $_POST['TourType'] == $tour_type['id']) ? ' selected="selected" ' : '';

                            echo '<option value="' . $tour_type['id'] . '"' . $select_type . '>' . $tour_type['Tour_Type'] . '</option>';
                        }
                    }
                    ?>

                </select>

                <div>
                    <p class="text-danger">
                        <?php echo $tour_type_error; ?>
                    </p>
                </div>
            </div>
            <!-- Tour Type Field -->

            <!-- Location 1 -->
            <div class="form-group">
                <label for="location1">Location 1</label>

                <select class="form-control <?php echo (!empty($location1_error)) ? 'border border-danger' : ''; ?>" id="Location1" name="Location1">
                    <option value="" selected>Select First Location</option>

                    <!-- Show dropdown list from Location table-->
                    <?php 
                    $get_location1_sql = "SELECT location_id, location_name, minimum_time FROM m02_location WHERE is_deleted = 0 ORDER BY location_name ASC";
                    $get_location1 = mysqli_query($conn, $get_location1_sql);

                    if (mysqli_num_rows($get_location1) > 0) {
                        while ($location1 = mysqli_fetch_assoc($get_location1)) {
                            $select_location1 = (isset($_POST['Location1']) && $_POST['Location1'] == $location1['location_id']) ? ' selected="selected" ' : '';

                            echo '<option value="' . $location1['location_id'] . '"' . $select_location1 . '>' . $location1['location_name'] . '</option>';
                        }
                    }
                    ?>
                </select>

                <div>
                    <p class="text-danger">
                        <?php echo $location1_error; ?>
                    </p>
                </div>

            </div>
            <!-- Location 1 -->

            <!-- Location 2 -->
            <div class="form-group">
                <label for="location2">Location 2</label>

                <select class="form-control <?php echo (!empty($location2_error)) ? 'border border-danger' : ''; ?>" id="Location2" name="Location2">
                    <option value="" selected>Select Second Location</option>

                    <!-- Show dropdown list from Location table-->
                    <?php 
                    $get_location2_sql = "SELECT location_id, location_name, minimum_time FROM m02_location WHERE is_deleted = 0 ORDER BY location_name ASC";
                    $get_location2 = mysqli_query($conn, $get_location2_sql);

                    if (mysqli_num_rows($get_location2) > 0) {
                        while ($location2 = mysqli_fetch_assoc($get_location2)) {
                            $select_location2 = (isset($_POST['Location2']) && $_POST['Location2'] == $location2['location_id']) ? ' selected="selected" ' : '';

                            echo '<option value="' . $location2['location_id'] . '"' . $select_location2 . '>' . $location2['location_name'] . '</option>';
                        }
                    }
                    ?>
                </select>

            </div>
            <!-- Location 2 -->

            <!-- Location 3 -->
            <div class="form-group">
                <label for="location3">Location 3</label>

                <select class="form-control <?php echo (!empty($location3_error)) ? 'border border-danger' : ''; ?>" id="Location3" name="Location3">
                    <option value="" selected>Select Third Location</option>

                    <!-- Show dropdown list from Location table-->
                    <?php 
                    $get_location3_sql = "SELECT location_id, location_name, minimum_time FROM m02_location WHERE is_deleted = 0 ORDER BY location_name ASC";
                    $get_location3 = mysqli_query($conn, $get_location3_sql);

                    if (mysqli_num_rows($get_location3) > 0) {
                        while ($location3 = mysqli_fetch_assoc($get_location3)) {
                            $select_location3 = (isset($_POST['Location3']) && $_POST['Location3'] == $location3['location_id']) ? ' selected="selected" ' : '';

                            echo '<option value="' . $location3['location_id'] . '"' . $select_location3 . '>' . $location3['location_name'] . '</option>';
                        }
                    }
                    ?>
                </select>

            </div>
            <!-- Location 3 -->

        </form>
    </div>

    <!-- Tour Fields -->

    <?php include 'footer.php'; ?>
</body>

</html>

<?php mysqli_close($conn); ?>
