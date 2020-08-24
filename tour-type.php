<?php
session_start();
require_once 'config.php';

$type_name = $type_name_error = '';

/*if (!isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] != TRUE) {
    header('location: login.php');

} elseif (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == 'TRUE') {*/
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (empty(trim($_POST['TourType']))) {
            $type_name_error = "Please enter a tour type name.";
        } else {
            $type_name = (trim($_POST['TourType']));
        }

        if (empty($type_name_error)) {
            $addTourTypeSql = "INSERT INTO m02_type (tour_type) VALUES ('" . $type_name . "')";

            if ($stmt = mysqli_prepare($conn, $addTourTypeSql)) {
                mysqli_stmt_bind_param($stmt, 's', $param_type_name);

                $param_type_name = $type_name;

                if (mysqli_stmt_execute($stmt)) {
                    $type_Added = TRUE;
                    unset($_POST);
                } else {
                    echo 'Error: ' . $addTourTypeSql . '</br>' . mysqli_error($conn);
                }

                mysqli_stmt_close($stmt);
            }
        }
}
?>

<html>

<head>
    <title>Add Tour Type | Tour Management</title>

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

    <!-- Tour Type Fields -->
    <h1 class="text-center mt-3">Add Tour Type</h1>

    <div class="container">
        <?php
        if ($type_Added === TRUE) {
            echo'
            <div class="alert alert-success my-4 alert-dismissable fade show" role="alert">
            Tour type added successfully.

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            ';
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-group">
                <label for="TourType">Tour Type Name</label>

                <input type="text" id="TourType" name="TourType" class="form-control <?php echo (!empty($type_name_error)) ? 'border border-danger' : ''; ?>" placeholder="Add Type Tour Name" value="<?php echo $_POST['TourType']; ?>">
            
                <div>
                    <p class="text-danger">
                        <?php echo $type_name_error; ?>
                    </p>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Add New Tour</button>
        </form>
    </div>

    <!-- Tour Type Fields -->

    <?php include 'footer.php'; ?>
</body>

</html>

<?php mysqli_close($conn); ?>