<?php
include '../handle_operation/handle_fetch_user.php';
$userId = $user['id'];

// Edit Profile Logic
if (isset($_POST['change_password'])) {
    $old_password = $_POST["old_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];
    $timestamp = time();

    if ($old_password !== '' && $new_password !== '' && $confirm_password !== '') {
   
        if (password_verify($old_password, $user['password'])) {
            if ($new_password === $confirm_password) {

                // Hash the password before storing it
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                $sql = "UPDATE users SET password = ?, modified_date = ?  WHERE id = ?";
                $stmt = $conn->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param("ssi", $hashed_password, $timestamp, $userId);
                    if ($stmt->execute()) {
                        header("Location: profile_page.php?id=$userId&&msg=Password Changed&&status=1");
                        exit;
                    } else {
                        header("Location: profile_page.php?id=$userId&&msg=Something went wrong&&status=0");
                        exit;
                    }
                    $stmt->close();
                } else {
                    echo "Error: " . $conn->error;
                }
            } else {
                header("Location: profile_page.php?id=$userId&&msg=New password and Confirm password must be same&&status=0");
            }
        } else {
            header("Location: profile_page.php?id=$userId&&msg=Old password is incorrect&&status=0");
        }
    } else {
        header("Location: profile_page.php?id=$userId&&msg=Error: Please fill all the fields&&status=0");
    }
}
?>


<!-- Change Password Modal -->
<div class="modal fade" id="change_pass_Modal" tabindex="-1" aria-labelledby="changePassModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h1 style="color: #252626;" class="modal-title fs-5" id="changePassModalLabel">Change Password</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-0">
                <form method="post" class="mt-2">
                    <div class="mb-3">
                        <label class="form-label ">Old password</label>
                        <input type="password" class="form-control" name="old_password" value='' />
                    </div>
                    <div class="mb-3">
                        <label class="form-label ">New password</label>
                        <input type="password" class="form-control" name="new_password" value='' />
                    </div>
                    <div class="mb-3">
                        <label class="form-label ">Confirm password</label>
                        <input type="password" class="form-control" name="confirm_password" value='' />
                    </div>

                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input style="background-color:#011a42 ;" name="change_password" type="submit" class="btn text-info" value="Update"></input>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
