<?php 
session_start();
require_once 'config.php';

?>

<html>

<head>
    <title>Create Tour | Tour Management</title>

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

    <h1 class="text-center mt-3">Create Tour</h1>

    <div class="container">
        <form method="POST" enctype="multipart/form-data">
            <!--Tour Name-->
            <div class="form-group">
                <label for="tname">Tour Name</label>
                <input type="text" class="form-control" id="tname" name="tname" placeholder="Tour Name" value="<?php echo $_POST['tname']; ?>">
            </div>

            <!--Tour Type-->
            <div class="form-group">
                <label for="ttype">Tour Type</label>
                <input type="text" class="form-control" id="ttype" name="ttype" placeholder="Tour Type" value="<?php echo $_POST['ttype']; ?>">
            </div>

            <!--Location 1-->
            <div class="form-group">
                <label for="location1">Location 1</label>
                <input type="text" class="form-control" id="location1" name="location1" placeholder="Location 1" value="<?php echo $_POST['location1']; ?>">
            </div>



        </form>
    </div>



    <?php include 'footer.php'; ?>
</body>

</html>

<?php mysqli_close($conn); ?>
