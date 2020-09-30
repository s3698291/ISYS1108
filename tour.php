<?php 
session_start();
require_once 'config.php';

$tour_name = $tour_type = $location1 = $location2 = $location3 = $tour_min_time = "";
$tour_name_error = $tour_type_error = $location1_error = "";

if (isset($_SESSION['m02_loggedIn']) && $_SESSION['m02_loggedIn'] == TRUE) {
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
} else {
	header('location: /m02/login');
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

    <!-- JavaScript for buttons-->
	<!--
    <script src="https://use.fontawesome.com/08847fc84c.js"></script>
	-->

    <!-- CSS from Bootstrap -->
    <link rel="stylesheet" type="text/css" href="style/bootstrap.css">

    <!-- Individualised CSS -->
    <link rel="stylesheet" type="text/css" href="style/style.css?<?php echo date('l jS \of F Y h:i:s A'); ?>">
</head>

<body>
    <?php include 'header.php'; ?>

    <!-- Tour Fields -->

    <div class="container sticky-footer">
		<h1 class="text-center mt-3">Add Tour</h1>
		
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
            <div class="form-group row">
                <label for="TourName" class="col-sm-2 col-form-label">Tour Name</label>

                <div class="col-sm-10">
                    <input type="text" class="form-control <?php echo (!empty($tour_name_error)) ? 'border border-danger' : ''; ?>" id="TourName" name="TourName" placeholder="Add Tour Name" value="<?php echo $_POST['TourName']; ?>">
                
                    <div>
                        <p class="text-danger">
                            <?php echo $tour_name_error; ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tour Type Field -->
            <div class="form-group row">
                <label for="TourType" class="col-sm-2 col-form-label">Tour Type</label>

                <div class="col-sm-10">
                    <select class="form-control <?php echo (!empty($tour_type_error)) ? 'border border-danger' : ''; ?>" id="TourType" name="TourType">
                        <option value="" selected>Select Tour Type</option>

                        <!-- Show dropdown list from Tour Type table-->
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

                    <div>
                        <p class="text-danger">
                            <?php echo $tour_type_error; ?>
                        </p>
                    </div>
                </div>
            </div>
            <!-- Tour Type Field -->

            <!-- Location Table -->

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width:60%">Location</th>
                            <th>Minimum Time</th>
                            <!-- <th style="width:10%"><button type="button" class="btn btn-default"><i class="fa fa-plus"></i></button></th> -->
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td> 
                                <select class="form-control <?php echo !empty($location1_error) ? 'border border-danger' : ''; ?>" id="Location1" name="Location1" onchange="calculateMinTime(this.value, this.id)">
                                    <option value="" selected>Select First Location</option>
                
                                    <?php
                                    // Include error message !!
                                    $get_location1_sql = "SELECT location_id, location_name, minimum_time FROM m02_location WHERE is_deleted = 0 ORDER BY location_name ASC";
                                    $get_location1 = mysqli_query($conn, $get_location1_sql);

                                    if (mysqli_num_rows($get_location1) > 0) {
                                        while ($location1 = mysqli_fetch_assoc($get_location1)) {
                                            $select_location1 = (isset($_POST['Location1']) && $_POST['Location1'] == $location1['location_id']) ? ' selected="selected" ' : '';
                                            
                                            echo '<option id="' . $location1['minimum_time'] . '" value="' . $location1['location_id'] . '"' . $select_location1 . '>' . $location1['location_name'] . '</option>';
                                        
                                        }
                                    }
                                    ?>
                                </select>

                                <?php
                                if (!empty($location1_error)) {
                                    echo '<p class="text-danger mb-0">' . $location1_error . '</p>';
                                }
                                ?>
                            </td>
                            <td><input type="number" class="form-control-plaintext number-hide" id="Location1_mintime" name="Location1_mintime" value="<?php echo $_POST['Location1_mintime']; ?>" readonly></td>
							<!--
                            <td>
                                <button type="button" class="btn btn-default" onclick="">
                                    <i class="fa fa-close"></i>
                                </button>
                            </td>
							<-->
                        </tr>

                        <tr>
                            <td>
                                <select class="form-control" id="Location2" name="Location2" onchange="calculateMinTime(this.value, this.id)">
                                    <option value="" selected>Select Second Location</option>
                
                                    <?php
                                    // Include error message !!
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
                            </td>
                            <td><input type="number" class="form-control-plaintext number-hide" id="Location2_mintime" name="Location2_mintime" value="<?php echo $_POST['Location2_mintime']; ?>" readonly></td>
							<!--
                            <td>
                                <button type="button" class="btn btn-default" onclick="">
                                    <i class="fa fa-close"></i>
                                </button>
                            </td>
							-->
                        </tr>

                        <tr>
                            <td>
                                <select class="form-control" id="Location3" name="Location3" onchange="calculateMinTime(this.value, this.id)">
                                    <option value="" selected>Select Third Location</option>

                                    <?php
                                    // Include error message !!
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
                            </td>
                            <td><input type="number" class="form-control-plaintext number-hide" id="Location3_mintime" name="Location3_mintime" value="<?php echo $_POST['Location3_mintime']; ?>" readonly></td>
							<!--
                            <td>
                                <button type="button" class="btn btn-default" onclick="">
                                    <i class="fa fa-close"></i>
                                </button>
                            </td>
							<-->
                        </tr>
                    </tbody>
                </table>

                <div class="col-sm-5 float-right">
                    <div class="form-group">
                        <label for="total_mintime">Total minimum time: </label>

                        <input type="number" id="total_mintime" name="total_mintime" class="form-control number-hide d-inline" value="<?php echo $_POST['total_mintime']; ?>" readonly>
                    </div>
                </div>

                <script>
                    function calculateMinTime(selected_id, form_id) {
                        var xhttpAccount, resultAccount, parsedAccount, accountInfo;

                        xhttpAccount = new XMLHttpRequest();

                        xhttpAccount.onreadystatechange = function() {
                            if (this.readyState == 4 && this.status == 200) {
                                resultAccount = this.responseText;
                                parsedAccount = JSON.parse(resultAccount);
                                accountInfo = parsedAccount[0];

                                document.getElementById(form_id + '_mintime').value = accountInfo.mintime;

                                if (document.getElementById('total_mintime').value == '') {
                                    document.getElementById('total_mintime').value = accountInfo.mintime;

                                } else {
                                    document.getElementById('total_mintime').value = parseInt(document.getElementById('total_mintime').value) + accountInfo.mintime;

                                }
                            }
                        };

                        xhttpAccount.open('GET', '/m02/get-minimum-time?id=' + selected_id, true);
                        xhttpAccount.send();

                        //document.getElementById('selectedStaffId').value = id;
                    }
                </script>

                
            <!-- Location Table -->

            <button type="submit" class="btn btn-primary btn-block">Submit</button>
        </form>
    </div>

    <!-- Tour Fields -->

    <?php include 'footer.php'; ?>
</body>

</html>

<?php mysqli_close($conn); ?>
