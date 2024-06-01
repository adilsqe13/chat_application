<?php
$userId = $user['id'];

// Edit Profile Logic
if (isset($_POST['edit_profile'])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $timestamp = time();
    $user_email = $user['email'];
    if ($name !== '' && $email !== '') {
        $u_stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $u_stmt->bind_param("s", $email); // Bind the user email to the placeholder
        $u_stmt->execute();
        $u_result = $u_stmt->get_result();

        // Fetch the results
        if ($u_result->num_rows === 0) {
            $sql = "UPDATE users SET name = ?, email = ?, modified_date = ?  WHERE id = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("sssi", $name, $email, $timestamp, $userId);
                if ($stmt->execute()) {
                    header("Location: profile_page.php?id=$userId&&msg=Details updated&&status=1");
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
            header("Location: profile_page.php?id=$userId&&msg=Error: Email is already registered !&&status=0");
        }
    } else {
        header("Location: profile_page.php?id=$userId&&msg=Error: Please fill all the fields&&status=0");
    }
}
?>


<!-- Edit Profile Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h1 style="color: #252626;" class="modal-title fs-5" id="editModalLabel">Edit Profile</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-0">
                <form method="post" class="mt-2">
                    <div class="mb-3">
                        <label class="form-label ">Name</label>
                        <input type="text" class="form-control" name="name" value='<?php echo htmlspecialchars($user["name"]) ?>' />
                    </div>
                    <div class="mb-3">
                        <label class="form-label ">Email</label>
                        <input type="email" class="form-control" name="email" value='<?php echo htmlspecialchars($user['email']) ?>' />
                    </div>

                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input style="background-color:#011a42 ;" name="edit_profile" type="submit" class="btn text-info" value="Update"></input>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>