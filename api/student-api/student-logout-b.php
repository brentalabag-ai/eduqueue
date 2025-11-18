<?php
require_once "../../db/config.php";

session_destroy();
header("Location: ../../student-management/student_login.php");
exit;
?>