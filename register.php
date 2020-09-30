<?php 
session_start();
require_once 'config.php';

$full_name = $username = $password = $confirm_password = $role = "";
$full_name_error = $username_error = $password_error = $confirm_password_error = $role_error = "";

if (isset($_SESSION['m02_loggedIn']) && $_SESSION['m02_loggedIn'] == 'TRUE') {
    if (!isset($_SESSION['m02_role']) || $_SESSION['m02_role'] == 'Assistant') {
        header('location: tour.php');

    } elseif (isset($_SESSION['m02_role']) && $_SESSION['m02_role'] == 'Admin') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (empty(trim($_POST['FullName']))) {
                $full_name_error = 'Please enter staff\'s full name.';

            } else {
                $full_name = trim($_POST['FullName']);
            }

            if (empty(trim($_POST['Username']))){
                $username_error = 'Please enter staff\'s username.';
            
            } else { 
                $username = trim($_POST['Username']);
            } 

            if (empty(trim($_POST['Password']))) {
                $password_error = 'Please enter a password.';
            
            } else {
                $password = trim($_POST['Password']);
            }

            if (empty(trim($_POST['ConfirmPassword']))) {
                $confirm_password_error = "Please confirm the password.";

            } else {
                $confirm_password = trim($_POST['ConfirmPassword']);

                if (empty($confirm_password_error) && empty($password_error) && ($password != $confirm_password)) {
                    $confirm_password_error = $password_error = 'Password did not matched.';
                }
            }

            if (empty(trim($_POST['Role']))) {
                $role_error = 'Please enter staff\'s role.';

            } else {
                $role = trim($_POST['Role']);
            }

            if (empty($full_name_error) && empty($username_error) && empty($password_error) && empty($confirm_password_error) && empty($role_error)) {
                $registerSql = 'INSERT INTO m02_account (full_name, username, password, role) VALUES (?, ?, ?, ?)';

                if ($stmt = mysqli_prepare($conn, $registerSql)) {
                    mysqli_stmt_bind_param($stmt, 'sssi', $param_full_name, $param_username, $param_password, $param_role);

                    $param_full_name = $full_name;
                    $param_username = $username;
                    $param_password = password_hash($password, PASSWORD_DEFAULT);
                    $param_role = $role;

                    if (mysqli_stmt_execute($stmt)) {
                        $registered = TRUE;
                        unset($_POST);

                    } else {
                        echo 'Error: ' . $registerSql . '<br />' .mysqli_error($conn);
                    }
                }

                mysqli_stmt_close($stmt);
			}
    	}	
	}
}

?>

<html>

<head>
    <title>Register Account | Tour Management</title>

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

    <!-- Register Account field -->

    <div class="container sticky-footer">
		<h1 class="text-center mt-3"> Register Account </h1>
		
        <?php 
        if ($registered === TRUE) {
            echo  '
            <div class="alert alert-success my-4 alert-dismissable fade show" role="alert">
                Account registered successfully.

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            ';
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-group">
                <div class="form-group">
                    <label for="FullName">Full Name</label>
                    <input type="text" class="form-control <?php echo (!empty($full_name_error)) ? 'border border-danger' : ''; ?>" id="FullName" name="FullName" placeholder="Enter Full Name" value="<?php echo $_POST['FullName']; ?>">

                    <div>
                        <p class="text-danger">
                            <?php echo $full_name_error; ?>
                        </p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="Username">Username</label>
                    <input type="text" class="form-control <?php echo (!empty($username_error)) ? 'border border-danger' : ''; ?>" id="Username" name="Username" placeholder="Enter Username" value="<?php echo $_POST['Username']; ?>">

                    <div>
                        <p class="text-danger">
                            <?php echo $username_error; ?>
                        </p>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col">
                        <label for="Password">Password</label>
                        <input type="password" class="form-control <?php echo (!empty($password_error)) ? 'border border-danger' : ''; ?>" id="Password" name="Password" placeholder="Enter Password" value="<?php echo $_POST['Password']; ?>">

                        <div>
                            <p class="text-danger">
                                <?php echo $password_error; ?>
                            </p>
                        </div>
                    </div>

                    <div class="col">
                        <label for="ConfirmPassword">Confirm Password</label>
                        <input type="password" class="form-control <?php echo (!empty($confirm_password_error)) ? 'border border-danger' : ''; ?>" id="ConfirmPassword" name="ConfirmPassword" placeholder="Confirm Password" value="<?php echo $_POST['ConfirmPassword']; ?>">

                        <div>
                            <p class="text-danger">
                                <?php echo $confirm_password_error; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="Role">Role</label>
                    <select class="form-control <?php echo (!empty($role_error)) ? 'border border-danger' : ''; ?>" id="Role" name="Role">
                        <option value="" selected>Select Role</option>

                        <?php 
                        $getRoleSql = "SELECT * FROM m02_role ORDER BY role_type ASC";
                        $getRole = mysqli_query($conn, $getRoleSql);

                        if (mysqli_num_rows($getRole) > 0) {
                            while ($role = mysqli_fetch_assoc($getRole)) {
                                $selected_role = (isset($_POST['Role']) && $_POST['Role'] == $role['role_id']) ? ' selected="selected"' : '';

                                echo '<option value="' . $role['role_id'] . '"' . $selected_role . '>' . $role['role_type'] . '</option>';
                            }
                        }
                        ?>
                    </select>

                    <div>
                        <p class="text-danger">
                            <?php echo $role_error; ?>
                        </p>
                    </div>
                </div>

            </div>

            <button type="submit" class="btn btn-primary btn-block">Register</button>
        </form>
    </div>

    <!-- Register Account field -->

    <?php include 'footer.php'; ?>
</body>

</html>

<?php mysqli_close($conn); ?>