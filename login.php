<?php 
session_start();
require_once 'config.php';

$email = $password = '';
$email_error = $password_error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    //Email: Validate and submit
    if (empty(trim($_POST['email']))) {
        $email_error = "Please enter an email adddress.";
    } else {
        $email = trim($_POST['email']);
    }

    //Password: Validate and submit
    if (empty(trim($_POST['password']))) {
        $password_error = "Please enter a password.";
    } else {
        $password = trim($_POST['password']);
    }

    //Send login information to database
    if (empty($email_error) && empty($password_error)) {
        $login_sql = '';

        if ($login_stmt = mysqli_prepare($conn, $login_sql)) {
            mysqli_stmt_bind_param($login_stmt, 's', $param_email);

            $param_email = $email;

            if (mysqli_stmt_execute($login_stmt)) {
                mysqli_stmt_store_result($login_stmt);

                if (mysqli_stmt_num_rows($login_stmt) == 1) {
                    mysqli_stmt_bind_result($login_stmt, $account_id, $account_password, $account_role, $account_deactivate);

                    if (mysqli_stmt_fetch($login_stmt)) {
                        if ($account_deactivate == 1) {
                            $login_error = "This account has been deactivated by the Admin.";
                        } else {
                            if (password_verify($password, $account_password)) {
                                session_start();

                                $_SESSION['loggedIn'] == TRUE;
                                $_SESSION['accountId'] == $account_id;

                                if ($account_role == 1) { 
                                    $_SESSION['role'] = 'Admin';
                                } elseif ($account_role == 2) {
                                    $_SESSION['role'] = 'Assistant';
                                }

                                header('location: tour.php');

                                unset($_POST);

                            } else {
                                $password_error = "The password you entered is incorrect.";
                            }
                        }
                    }

                } else {
                    $email_error = "No account was found with the entered email address.";
                }

            } else {
                echo 'Error: ' . $login_sql . '<br/>' . mysqli_error($conn);
            }
        }

        mysqli_stmt_close($login_stmt);
    }
}
?>

<html>

<head>
    <title>Login | Tour Management</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- JavaScript from Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

    <!-- CSS from Bootstrap -->
    <link rel="stylesheet" type="text/css" href="style/bootstrap.css">

    <!-- Individualised CSS -->
    <link rel="stylesheet" type="text/css" href="style/style.css">

</head>

<body>
    <?php include 'header.php'; ?>

    <!-- Login Form -->
    <h1 class="text-center mt-3">Login</h1>

    <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" class="form-control <?php echo (!empty($email_error)) ? 'border border-danger' : ''; ?>" id="email" name="email" placeholder="Enter Email Address" value="">

                <div>
                    <p class="text-danger">
                        <?php echo $email_error; ?>
                    </p>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control <?php echo (!empty($password_error)) ? 'border border-danger' : ''; ?>" id="password" name="password" placeholder="Enter Password" value="">

                <div>
                    <p class="text-danger">
                        <?php echo $password_error; ?>
                    </p>
                </div>
            </div>

            <div>
                <p class="text-danger">
                    <?php echo $login_error; ?>
                </p>
            </div>
                
            <button type="submit" class="btn btn-primary btn-block">Login</button>

        </form>
    </div>
    <!-- Login Form -->

    <?php include 'footer.php'; ?>
</body>

</html>

<?php mysqli_close($conn); ?>