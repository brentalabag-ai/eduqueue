<?php
require_once "../../../db/config.php";

session_destroy();
header("Location: ../../../staff-management/cashier/index.php");
exit;
?>