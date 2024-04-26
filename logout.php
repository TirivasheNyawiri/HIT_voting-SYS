<?php
session_start();
session_destroy();
header("Location: startvoting.php");
exit();
?>
