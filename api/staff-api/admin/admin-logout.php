<?php
require_once "../../../db/config.php";

session_destroy();
header("Location: ../../../staff-management/admin/admin_login.php");
exit;
?>