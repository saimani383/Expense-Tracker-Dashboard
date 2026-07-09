<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {

    $id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $sql = "DELETE FROM transactions WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: transactions.php");
        exit();
    } else {
        echo "Error deleting transaction.";
    }
}
?>