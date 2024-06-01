<?php include 'header.php'; ?>
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/default.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <?php include '../handle_operation/handle_fetch_user.php'; ?>
    <?php include 'edit_user_modal.php'; ?>
    <?php include 'crop_img_modal.php'; ?>
    <?php include 'change_pass_modal.php'; ?>

    <p>
    <div class="row m-2">
        <div class="col-4"></div>
        <div class="col-4">
            <h1 style="color:#011a42 ;" class='bolder mt-4 text-shadow' align='center'>CHAT DASHBOARD</h1>
            <?php include 'msg_display.php'; ?>
        </div>
        <div class="col-4 dfjeac px-4 m-0">
            <button class="btn bg-tranparent border-0 dropdown-toggle p-0" data-bs-toggle="dropdown">
                <img class="rounded-circle box-shadow-dark border border-2 border-dark" width="60" height="60" src="../images/<?php echo $user['image'] ?>" alt="profile-img" />
            </button>
            <ul style="background-color:#252626 ;" class="dropdown-menu p-2 box-shadow-dark m-0 rounded-4">
                <li class="p-2"><span class="fs-5 bold text-off-white"><?php echo $user['name'] ?></span></li>
                <li class=" p-2">
                    <a title="Logout" href="../index.php" class=" btn p-0 rounded-5 border-0 dfjsac">
                        <?php include '../icons/logout_icon.php'; ?>
                        <?php echo "<span style='color:#d10808' class='bold fs-5 mb-1 px-2'>Logout</span>" ?>
                    </a>
                </li>

            </ul>

        </div>
    </div>
    </p>

    <div class="container w-50 mt-5">
        <div class="container-fluid pumf w-100 mt-3">
            <?php
            $query = "SELECT * FROM users WHERE status = 1 AND id != $user_id";
            $result = mysqli_query($conn, $query);

            if ($result) {
                $new_row = [];
                while ($rows = mysqli_fetch_assoc($result)) {
                    $new_row[] = array_reverse($rows);
                }
                $reversedRows = array_reverse($new_row);
                if (count($reversedRows) > 0) {
                    foreach ($reversedRows as $row) {
            ?>
                        <div style="background-color:#011a42 ;" class="row mt-3 p-2 rounded-5 chat-box-select">
                            <div class="col-2 dfjcac">
                                <img class="rounded-circle box-shadow-dark" width="70" height="70" src="../images/<?php echo $row['image'] ?>" alt="profile-img" />
                            </div>
                            <div class="col-6 text-light dfjsac">
                                <span class="h5 text-off-white text-shadow"> <?php echo $row['name'] ?></span>
                            </div>
                            <div class="col-4 dfjcac">
                                <div class="row w-100">
                                    <div class="col-2"></div>
                                    <div class="col-6 dfjsac">
                                        <div style="width: 40px; color: red" class=" bold fs-5 dfjeac">
                                            <?php
                                            $receiver_id = $row['id'];
                                            $r_query = "SELECT * FROM messages WHERE sender_id = ? AND receiver_id = ? AND seen = 0";
                                            $stmt = $conn->prepare($r_query);
                                            $stmt->bind_param("ii", $receiver_id, $user_id);
                                            $stmt->execute();
                                            $r_result = $stmt->get_result();
                                            $row_count = $r_result->num_rows;
                                            if ($row_count !== 0) {
                                                echo $row_count;
                                            }
                                            $stmt->close();
                                            ?>

                                        </div>
                                        <a href="#" class=" bg-transparent border-0">
                                            <?php include '../icons/msg_icon.php'; ?>
                                        </a>
                                    </div>
                                    <div class="col-4 dfjeac">
                                        <button data-toggle="modal" data-target="#showMessageModal" onclick="showMessageModal(<?php echo $row['id'] ?>,  <?php echo $user_id ?>)" class="btn btn-outline-info bold box-shadow-dark">Send</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                } else {
                    ?>
                    <h5 class="text-dark mt-5" align='center'>No Users Found</h5>
            <?php
                }
            }
            ?>

        </div>
    </div>


    <!-- Show Profile Modal -->
    <div class="overlay"></div>
    <div style="width: 32% ;" class="modal-dialog rounded-4 center box " role="document">
        <div style="background-color: #dcdcde;" class="modal-content rounded-4 box-shadow-dark">
            <div class="card p-2 center">
                <div class="row">
                    <div class="col-4">
                        <div class="image_area">
                            <form method="post">
                                <label for="upload_image">
                                    <img src="../images/<?php echo $user['image'] ?>" id="uploaded_image" class="img-responsive img-circle rounded" height="200px" width="200px" />
                                    <?php include 'crop_img_library.php'; ?>
                                    <div class="card-overlay">
                                        <div class="text bold">Change Image</div>
                                        <input id='user_profile_input' type="hidden" value='<?php echo $user['id'] ?>'>
                                    </div>
                                    <input type="file" name="image" class="image" id="upload_image" style="display:none" />
                                </label>
                            </form>
                        </div>
                    </div>
                    <div class="col-8 m-0 p-0">
                        <div class="row m-0 p-0 ">
                            <div class="col-9 dfjsac m-0 p-0">
                                <h4 style="color: #011a42 ;" class="modal-title bold" id="receiver-label"><?php echo $user['name'] ?></h4>
                            </div>
                            <div class="col-3 dfjeac p-0 m-0">
                                <a href="dashboard.php?id=<?php echo $_GET['id'] ?>" style="color: #011a42 ;" align='right' type="button" class="m-0 btn border-0 bg-transparent fs-4">
                                    <?php include '../icons/cross_icon.php'; ?>
                                </a>
                            </div>
                        </div>
                        <span><?php echo $user['email'] ?></span>
                        <div style="width: 97%;" class=" p-2 mt-2 bg-primary d-flex justify-content-between rounded text-white stats">
                            <div class="d-flex flex-column">
                                <span class="articles bold">Articles</span>
                                <span class="number1">38</span>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="followers bold">Followers</span>
                                <span class="number2">980</span>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="rating bold">Rating</span>
                                <span class="number3 text-warning">8.9</span>
                            </div>
                        </div>
                        <div style="width: 97% ;" class="button d-flex flex-row mt24px">
                            <button data-bs-toggle="modal" data-bs-target="#change_pass_Modal" style="background-color:#011a42 ;" class="btn  text-info rounded-1 w-100">Change Password</button>
                            <button data-bs-toggle="modal" data-bs-target="#editModal" style="color: #011a42" class="btn  btn-info rounded-1 bold w-100 ml-2">Edit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <script>
            // textarea auto focus logic
            document.addEventListener('DOMContentLoaded', (event) => {
                const textarea = document.querySelector('.form-control');
                if (textarea) {
                    textarea.focus();
                }
            });
            //  Scroll to bottom after content is loaded
            setTimeout(function() {
                var modalBody = document.getElementById('modalBody2');
                modalBody.scrollTo({
                    top: modalBody.scrollHeight
                });
            }, 100);

            //  Add File Logic
            const uploadButton = document.getElementById('uploadButton');
            const fileInput = document.getElementById('fileInput');
            uploadButton.addEventListener('click', (event) => {
                event.preventDefault();
                fileInput.click();
            });
            fileInput.addEventListener('change', () => {
                if (fileInput.files.length > 0) {
                    const file_icon = document.getElementById('file_icon');
                    file_icon.style.color = '#f70707';
                }
            });
        </script>

        <?php include 'footer.php'; ?>
        <?php include 'crop_img_library.php'; ?>