<?php
require_once 'auth.php';

// Log the logout action
error_log("User logged out: " . getUserEmail());

// Clear user session and cookies
clearUserSession();

// Redirect to login page with success message
header('Location: login.php?msg=logged_out');
exit();
?>