<?php 
session_start();
require_once 'config.php';

$username = $password = $remember = '';
$username_error = $password_error = $remember_error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    //Username: Validate and submit
    if (empty(trim($_POST['Username']))) {
        $username_error = "Please enter a username.";
    } else {
        $username = trim($_POST['Username']);
    }

    //Password: Validate and submit
    if (empty(trim($_POST['Password']))) {
        $password_error = "Please enter a password.";
    } else {
        $password = trim($_POST['Password']);
    }

    //Send login information to database
    if (empty($username_error) && empty($password_error)) {
        $login_sql = 'SELECT account_id, password, role, is_deleted FROM m02_account WHERE username = ?';

        if ($login_stmt = mysqli_prepare($conn, $login_sql)) {
            mysqli_stmt_bind_param($login_stmt, 's', $param_username);

            $param_username = $username;

            if (mysqli_stmt_execute($login_stmt)) {
                mysqli_stmt_store_result($login_stmt);

                if (mysqli_stmt_num_rows($login_stmt) == 1) {
                    mysqli_stmt_bind_result($login_stmt, $account_id, $account_password, $account_role, $account_deactivate);

                    if (mysqli_stmt_fetch($login_stmt)) {
                        if ($account_deactivate == 1) {
                            $login_error = "This account has been deactivated by the Admin.";
                        } else {
                            if (password_verify($password, $account_password)) {
								if (isset($_POST['usernameRemember']) && $_POST['usernameRemember'] == 'on') {
									setcookie('m02_user_username', $username, time() + (86400 * 30), '/m02');
								}
								
                                session_start();

                                $_SESSION['m02_loggedIn'] = TRUE;
                                $_SESSION['m02_accountId'] = $account_id;

                                if ($account_role == 1) { 
                                    $_SESSION['m02_role'] = 'Admin';
                                } elseif ($account_role == 2) {
                                    $_SESSION['m02_role'] = 'Assistant';
                                }

                                header('location: tour.php');

                                unset($_POST);

                            } else {
                                $password_error = "The password you entered is incorrect.";
                            }
                        }
                    }

                } else {
                    $username_error = "No account was found with the entered username.";
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

    <div class="container sticky-footer">
        <div class="row">
            <div class="col-md-6 login-left-box">
                <h2>Welcome to <br>Tour Managemenet System</h2>
                <p>login as Admin/Assistant to start your session</p>
            </div>
            
        <div class="col-md-6 login-box">
		<h1 class="text-center mt-3">Login</h1>
		
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

            <div class="form-group">
                <label for="Username">Username</label>
				
				<?php 
				if (isset($_COOKIE['m02_user_username'])) {
					$saved_login_username = $_COOKIE['m02_user_username'];
				}
				?>
				
                <input type="text" class="form-control <?php echo (!empty($username_error)) ? 'border border-danger' : ''; ?>" id="Username" name="Username" placeholder="Enter Username" value="<?php echo (!empty($username)) ? $username : $saved_login_username; ?>">

                <div>
                    <p class="text-danger">
                        <?php echo $username_error; ?>
                    </p>
                </div>
            </div>

            <div class="form-group">
                <label for="Password">Password</label>
                <input type="password" class="form-control <?php echo (!empty($password_error)) ? 'border border-danger' : ''; ?>" id="Password" name="Password" placeholder="Enter Password" value="<?php echo (!empty($password)) ? $password : ''; ?>">

                <div>
                    <p class="text-danger">
                        <?php echo $password_error; ?>
                    </p>
                </div>
            </div>

            <div class="form-group">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="usernameRemember" id="usernameRemember" <?php echo (isset($_POST['usernameRemember']) && $_POST['usernameRemember'] == 'on') ? 'checked' : ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['usernameRemember']) ? '' : 'checked'); ?>>

                    <label class="form-check-label" for="usernameRemember">
                        Remember my username
                    </label>
                </div>
            </div>

            <div>
                <p class="text-danger">
                    <?php echo $login_error; ?>
                </p>
            </div>
                
            <button type="submit" class="btn btn-primary login-btn-block">Login</button>

        </form>
        </div>
        </div>
    </div>
    <!-- Login Form -->

    <?php include 'footer.php'; ?>
</body>

</html>

<?php mysqli_close($conn); ?>