<?php
session_start();

// Delete the session variables
unset($_SESSION['user']);
unset($_SESSION['user_id']);
unset($_SESSION['level']);

// Delete the session
session_destroy();

// Remove the cookie
setcookie("user", "", time()-3600);

header("Location: /");
