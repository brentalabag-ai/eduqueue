<?php
require_once "../../db/config.php";

session_destroy();
header("Location: ../../staff-management/index.php");
exit;
?>