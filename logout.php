<?php

// Remove Cookie
setcookie('E', '', time()-3600, '/', null, null, true);
setcookie('M', '', time()-3600, '/', null, null, true);
setcookie('T', '', time()-3600, '/', null, null, true);

// Remove login session
session_start();
$_SESSION = [];
session_unset();
session_destroy();

header("Location: login.php");
?>