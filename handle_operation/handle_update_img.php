<?php
include '../db.php';

if (isset($_POST['image'])) {
	$data = $_POST['image'];
	$userId = $_POST['userId'];
	$image_array_1 = explode(";", $data);
	$image_array_2 = explode(",", $image_array_1[1]);
	$data = base64_decode($image_array_2[1]);
    $timestamp =  time();
    $file_name = $timestamp . '.jpg';
    $folder = '../images/';
	$image_name =  $folder . $timestamp . '.jpg';
	file_put_contents($image_name, $data);

    // Unlink (delete) the current image file if it exists
    $d_sql = "SELECT * FROM users WHERE id = $userId";
        $d_result = mysqli_query($conn, $d_sql);
        $user = mysqli_fetch_assoc($d_result);
        $image = $user['image'];
        $current_image_path = '../images/'. $image;
         if (file_exists($current_image_path)) {
            unlink($current_image_path);
        }

    $sql = "UPDATE users SET image = ?, modified_date = ?  WHERE id = ?";

    // Prepare and bind
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $file_name, $timestamp, $userId);

    // Execute the query
    if ($stmt->execute()) {
        header("location: ../components/profile_page.php?id=$userId&&msg=Profile image updated");
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
