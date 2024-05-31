<?php
session_start();
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.html");
    exit();
} else {
    header("Location: index.html");
    exit();
}
?>