<?php 
session_start();
require_once 'config.php';

$tour_name = $tour_type = $location1 = $location2 = $location3 = $tour_min_time = "";
$tour_name_error = $tour_type_error = $location1_error = $tour_min_time_error = "";

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $get_tour_id = $_GET['id'];

    $get_tour_information_sql = 'SELECT tour_name, tour_type, location1, location2, location3, minimum_time FROM m02_tour WHERE tour_id = ?';

    if ($get_tour_information_stmt = mysqli_prepare($conn, $get_tour_information_sql)) {
        mysqli_stmt_bind_param($get_tour_information_stmt, 'i', $param_tour_id);

        $param_tour_id = $get_tour_id;

        if (mysqli_stmt_execute($get_tour_information_stmt)) {
            mysqli_stmt_store_result($get_tour_information_stmt);

            if (mysqli_stmt_num_rows($get_tour_information_stmt) > 0) {
                mysqli_stmt_bind_result($get_tour_information_stmt, $saved_tour_name, $saved_tour_type, $saved_location1, $saved_location2, $saved_location3, $saved_tour_minimum_time);

                mysqli_stmt_fetch($get_tour_information_stmt);
            } else {
                $_SESSION['m02_tour_not_found'] = TRUE;
                header('location: manage-tour');
            }
        }
    }

    mysqli_stmt_close($get_tour_information_stmt);

} else {
    // Main functionality of Edit Tour
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];

        //Tour name: Validate and submit
        if (empty(trim($_POST['TourName']))) {
            $tour_name_error = "Please enter the tour name.";
        } else {
            $tour_name = (trim($_POST['TourName']));
        }

        // Tour Type: Validate and submit
        if (empty(trim($_POST['TourType']))) {
            $tour_type_error = "Please select a tour type";
        } else {
            $tour_type = ($_POST['TourType']);
        }

        // Location 1: Validate and submit
        if (empty($_POST['Location1'])) {
            $location1_error = "Please select a location";
        } else {
            $location1 = $_POST['Location1'];
            $tour_minim_time = $_POST[''];
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
                $updateTourSql = "UPDATE m02_tour SET tour_name='" . $tour_name . "', tour_type='" . $tour_type . "', location1= " . $location1 . ", location2= " . $location2 . ", location3= " . $location3 . ", minimum_time= " . $tour_min_time . " WHERE tour_id=" . $id;
                
                if ($stmt = mysqli_prepare($conn, $updateTourSql)) {
                    mysqli_stmt_bind_param($stmt, 'ssiiii', $param_tour_name, $param_tour_type, $param_location1, $param_location2, $param_location3, $param_tour_min_time);

                    $param_tour_name = $tour_name;
                    $param_tour_type = $tour_type;
                    $param_location1 = $location1;
                    $param_location2 = $location2;
                    $param_location3 = $location3;
                    $param_tour_min_time = $tour_min_time;

                    if (mysqli_stmt_execute($stmt)) {
                        $tour_Updated = TRUE;
                        unset($_POST);
                    } else {
                        echo 'Error: ' . $updateTourSql . '</br>' . mysqli_error($conn);
                    }

                mysqli_stmt_close($stmt);

                }
            } else {
                $update_error = TRUE;
            }
        }
    } else {
        header('location: manage-tour');
    }
}

?>

<html>

<head>
    <title>Edit Tour | Tour Management</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- JavaScript from Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

    <!-- JavaScript for buttons -->
    <script src="https://use.fontawesome.com/08847fc84c.js"></script>

    <!-- CSS from Bootstrap -->
    <link rel="stylesheet" type="text/css" href="style/bootstrap.css">

    <!-- Individualised CSS -->
    <link rel="stylesheet" type="text/css" href="style/style.css?">
</head>

<body id="UpdateTour">
    <?php include 'header.php'; ?>

    <!-- Edit Location Field -->
    <h1 class="text-center mt-3">Edit Tour</h1>

    <div class="container">
        <!-- -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="hidden" id="id" name="id" value="<?php echo !empty($_POST['id']) ? $_POST['id'] : $_GET['id']; ?>">

            <div id="TourInfo" class="<?php echo isset($update_error) && $update_error == TRUE ? 'd-block' : ''; ?>">
                <div class="form-group row">
                    <label for="TourName" class="col-sm-2 col-form-label">Tour Name</label>

                    <div class="col-sm-10">
                        <input type="text" class="form-control <?php echo (!empty($tour_name_error)) ? 'border border-danger' : ''; ?>" id="TourName" name="TourName" placeholder="Tour Name" value="<?php echo !empty($_POST['TourName']) ? $_POST['TourName'] : $saved_tour_name; ?>">

                        <p class="text-danger">
                            <?php echo $tour_name_error; ?>
                        </p>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="TourType" class="col-sm-2 col-form-label">Tour Type</label>

                    <div class="col-sm-10">
                        <select class="form-control <?php echo (!empty($tour_type_error)) ? 'border border-danger' : ''; ?>" id="TourType" name="TourType">
                            <option value="<?php echo !empty($_POST['TourType']) ? $_POST['TourType'] : $saved_tour_type; ?>">Select Tour Type</option>

                            <!-- Show dropdown list from Tour Type table -->
                            <?php 
                            $get_tour_type_sql = "SELECT * FROM m02_type WHERE is_deleted = 0 ORDER BY tour_type ASC";
                            $get_tour_type = mysqli_query($conn, $get_tour_type_sql);

                            if (mysqli_num_rows($get_tour_type) > 0) {
                                while ($tour_type = mysqli_fetch_assoc($get_tour_type)) {
                                    $select_type = (isset($_POST['TourType']) && $_POST['TourType'] == $tour_type['type_id']) ? ' selected="selected" ' : '';

                                    echo '<option value="' . $tour_type['type_id'] . '"' . $select_type . '>' . $tour_type['tour_type'] . '</option>';
                                }
                            }
                            ?>
                        </select>

                        <p class="text-danger">
                            <?php echo $tour_type_error; ?>
                        </p>
                    </div>
                </div>

                <!-- Location Table -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width:60%">Location</th>
                            <th>Minimum Time</th>
                            <th style="width:10%">
                                <button type="button" class="btn btn-default">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>
                                <select class="form-control <?php echo (!empty($tour_type_error)) ? 'border border-danger' : ''; ?>" id="TourType" name="TourType">
                                    <option value="<?php echo !empty($_POST['TourType']) ? $_POST['TourType'] : $saved_tour_type; ?>">Select First Location</option>

                                    <!-- Show dropdown list from Tour Type table -->
                                    <?php 
                                    $get_tour_type_sql = "SELECT * FROM m02_type WHERE is_deleted = 0 ORDER BY tour_type ASC";
                                    $get_tour_type = mysqli_query($conn, $get_tour_type_sql);

                                    if (mysqli_num_rows($get_tour_type) > 0) {
                                        while ($tour_type = mysqli_fetch_assoc($get_tour_type)) {
                                            $select_type = (isset($_POST['TourType']) && $_POST['TourType'] == $tour_type['type_id']) ? ' selected="selected" ' : '';

                                            echo '<option value="' . $tour_type['type_id'] . '"' . $select_type . '>' . $tour_type['tour_type'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>

                                <?php
                                if (!empty($tour_type_error)) {
                                    echo '<p class="text-danger mb-0">' . $tour_type_error . '</p>';
                                }
                                ?>
                            </td>
                            <td></td>
                            <td>
                                <button type="button" class="btn btn-default" onclick="">
                                    <i class="fa fa-close"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- Location Table -->
            </div>
        </form>

        <!-- -->


        <form action="" method="POST">
            <input type="hidden" id="id" name="id">
            <!-- 
            <div id="TourInfo" class="">
                <div class="form-group row">
                    <label for="LocationName" class="col-sm-2 col-form-label">Tour Name</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="TournName" name="TourName" placeholder="Tour Name" value="">
                        <p class="text-danger">
                        </p>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="Coordinate" class="col-sm-2 col-form-label">Tour Type</label>
                    <div class="col-sm-4">
                        <select class="form-control"></select>
                        <option value=""></option>
                        <p class="text-danger"> 
                        </p>
                    </div>
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width:60%">Location</th>
                            <th>Min Duration</th>
                            <th style="width:10%"><button type="button" class="btn btn-default"><i class="fa fa-plus"></i></button></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>
                                <select class="form-control">
                                    <option value="">
                                    </option>
                                </select>
                            </td>
                            <td>
                            </td>
                            <td>
                            <button type="button" class="btn btn-default" onclick=""><i class="fa fa-close"></i></button>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <select class="form-control">
                                    <option value="">
                                    </option>
                                </select>
                            </td>
                            <td>
                            </td>
                            <td>
                            <button type="button" class="btn btn-default" onclick=""><i class="fa fa-close"></i></button>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <select class="form-control">
                                    <option value="">
                                    </option>
                                </select>
                            </td>
                            <td>
                            </td>
                            <td>
                            <button type="button" class="btn btn-default" onclick=""><i class="fa fa-close"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
               
                <div class="col-sm-4 pull pull-right">
                    <div class="form-group">
                    <label>Tour Minimum Time</label>
                    <input></input>
                    </div>

                    
                </div>

            </div>
                

             --> 
            <button id="UpdateButton" type="submit" class="btn btn-primary btn-block">Save Changes</button>
        
        </form>
    </div>

    <!-- Edit Location Field -->

    <!-- JS Retrieve Tour Info -->

    <!-- JS Retrieve Tour Info -->

    <?php include 'footer.php'; ?>
</body>

</html>