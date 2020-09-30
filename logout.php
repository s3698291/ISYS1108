<?php
session_start();

unset($_SESSION['m02_loggedIn']);
unset($_SESSION['m02_accountId']);
unset($_SESSION['m02_role']);

header('location: /m02/login');
exit;
?>