<?php
session_start();

if (isset($_SESSION['user_status'])) {
    if ($_SESSION['user_status'] == 1) {
        header("Location: admin/index.php");
    } else if ($_SESSION['user_status'] == 2) {
        header("Location: kasir/index.php");
    }
} else {
    header("Location: login.php");
}
exit;
?>
