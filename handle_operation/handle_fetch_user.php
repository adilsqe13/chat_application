<?php
    include '../db.php';
    $user_id = $_GET['id'];
    $user_query = "SELECT * FROM users WHERE id = $user_id";
    $user_result = mysqli_query($conn, $user_query);
    $user = mysqli_fetch_assoc($user_result);
    ?>