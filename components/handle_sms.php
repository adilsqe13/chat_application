<?php
include '../db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_msg'])) {
    $sender_id = $_POST['sender_id1'];
    $receiver_id = $_POST['receiver_id1'];
    $content = htmlspecialchars($_POST['content']);
    $file = $_FILES["image"]["name"];
    $timestamp = time();
    $file_name = $timestamp . $file;
    $tempname = $_FILES["image"]["tmp_name"];
    $folder = '../images/' . $file_name;

    if ($file !== '') {
        move_uploaded_file($tempname, $folder);
    }

    if (($content !== '' && $file !== '')) {
        $sql = "INSERT INTO messages (sender_id, receiver_id, content, file, added_date)
         VALUES ('$sender_id', '$receiver_id', '$content', '$file_name', '$timestamp')";
        if ($conn->query($sql) === TRUE) {
            header("Location: chat_page.php?id=$sender_id&&receiver_id=$receiver_id&&status=1&&msg=Message Sent");
        } else {
            header("Location: dashboard.php?id=$sender_id&&status=0&&msg=Error: Something went wrong");
        }
    } else if (($content !== '' && $file === '')) {
        $sql = "INSERT INTO messages (sender_id, receiver_id, content, added_date)
        VALUES ('$sender_id', '$receiver_id', '$content', '$timestamp')";
        if ($conn->query($sql) === TRUE) {
            header("Location: chat_page.php?id=$sender_id&&receiver_id=$receiver_id&&status=1&&msg=Message Sent");
        } else {
            header("Location: dashboard.php?id=$sender_id&&status=0&&msg=Error: Something went wrong");
        }
    } else if (($content === '' && $file !== '')) {
        $sql = "INSERT INTO messages (sender_id, receiver_id, file, added_date)
        VALUES ('$sender_id', '$receiver_id','$file_name', '$timestamp')";
        if ($conn->query($sql) === TRUE) {
            header("Location: chat_page.php?id=$sender_id&&receiver_id=$receiver_id&&status=1&&msg=Message Sent");
        } else {
            header("Location: dashboard.php?id=$sender_id&&status=0&&msg=Error: Something went wrong");
        }
    } else {
        header("Location: dashboard.php?id=$sender_id&&status=0&&msg=Please write before send!");
    }
}
