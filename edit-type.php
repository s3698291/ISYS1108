<?php
session_start();
require_once 'config.php';

$tour_type = $tour_type_error = '';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $get_type_id = $_GET['id'];

    $get_type_information_sql = 'SELECT tour_type FROM m02_type WHERE type_id = ?';
    
    if ($get_type_information_stmt = mysqli_prepare($conn, $get_type_information_sql)) {
        mysqli_stmt_bind_param($get_type_information_stmt, 'i', $param_type_id);

        $param_type_id = $get_type_id;

        if (mysqli_stmt_execute($get_type_information_stmt)) {
            mysqli_stmt_store_result($get_type_information_stmt);

            if (mysqli_stmt_num_rows($get_type_information_stmt) > 0) {
                mysqli_stmt_bind_result($get_type_information_stmt, $saved_tour_type);

                mysqli_stmt_fetch($get_type_information_stmt);

            } else {
                $_SESSION['m02_type_not_found'] = TRUE;
                header('location: manage-tour-types');

            }
        }
    }

    mysqli_stmt_close($get_type_information_stmt);

} else {
    // Main functionality of Edit Tour Type
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];

        //Tour Type: Validate and submit
        if (empty(trim($_POST['TourType']))) {
            $tour_type_error = "Please enter the tour type.";
        } else {
            $tour_type = (trim($_POST['TourType']));
        }

        // Update tour type information to database
        if (empty($tour_type_error)) {
            $update_type_sql = 'UPDATE m02_type SET tour_type="' . $tour_type . '" WHERE type_id=' . $id;
        
            if(mysqli_query($conn, $update_type_sql)) {
                $_SESSION['m02_edit_type_success'] = TRUE;
                header('location: manage-tour-types');

                unset($_POST);
            } else {
                echo 'Error: ' . $update_type_sql . '<br/>' .mysqli_error($conn);
            }

        } else {
            $update_error = TRUE;
        }
    } else {
        header('location: manage-tour-types');

    }
}

?>
<html>

<head>
    <title>Edit Tour Type | Tour Management</title>

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

<body id="UpdateType">
    <?php include 'header.php'; ?>

    <!-- Edit Type Field -->

    <div class="container sticky-footer">
		<h1 class="text-center mt-3">Edit Tour Type</h1>

        <!-- Form shows only when user selects tour type -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="hidden" id="id" name="id" value="<?php echo !empty($_POST['id']) ? $_POST['id'] : $_GET['id']; ?>">

            <div id="TypeInfo" class="<?php echo isset($update_error) && $update_error == TRUE ? 'd-block' : ''; ?> ">
                <div class="form-group row">
                    <label for="TourType" class="col-sm-2 col-form-label">Tour Type</label>
                
                    <div class="col-sm-10">
                        <input type="text" class="form-control <?php echo (!empty($tour_type_error)) ? 'border border-danger' : ''; ?>" id="TourType" name="TourType" placeholder="Tour Type" value="<?php echo !empty($_POST['TourType']) ? $_POST['TourType'] : $saved_tour_type; ?>">
                    
                        <p class="text-danger">
                            <?php echo $tour_type_error; ?>
                        </p>
                    </div>
                </div>
            </div>
                
            <button id="UpdateButton" type="submit" class="btn btn-primary btn-block">Save Changes</button>
        </form>
    </div>

    <!-- Edit Type Field -->

    <?php include 'footer.php'; ?>
</body>

</html>

<?php mysqli_close($conn); ?>