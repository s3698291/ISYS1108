<?php
session_start();
require_once 'config.php';

$location_id = "";
$location_id_error = "";

?>

<html>

<head>
    <title>Remove Location | Tour Management</title>

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

<body id="CopyLocation">
    <?php include 'header.php'; ?>

    <!-- Remove Location Field -->
    <h1 class="text-center mt-3">Remove Location</h1>

    <div class="container">
        <form method="POST">
            <label for="locationId">Location</label>

            <select class="form-control <?php echo (!empty($tour_id_err)) ? 'border border-danger' : ''; ?>" id="locationId" name="locationId">
                <option value="" selected>Select location</option>
            </select>

            <button type="submit" class="btn btn-primary btn-block">Delete Location</button>
        </form>

    </div>

    <!-- Remove Location Field -->


    <?php include 'footer.php'; ?>
</body>

</html>

<?php mysqli_close($conn); ?>
