<?php include 'header.php'; ?>
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/default.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <?php
    include '../db.php';
    $user_id = $_GET['id'];
    $user_query = "SELECT * FROM users WHERE id = $user_id";
    $user_result = mysqli_query($conn, $user_query);
    $user = mysqli_fetch_assoc($user_result);
    ?>
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


    <!-- Show Message Modal -->
    <div class="overlay"></div>
    <div style="width: 32% ;" class="modal-dialog rounded-4 center box " role="document">
        <div style="background-color: #dcdcde;" class="modal-content rounded-4 box-shadow-dark">
            <div style="background-color: #659bf7;" class="rounded-2 p-2 modal-header-new">
                <div class="row">
                    <div class="col-6 dfjsac">
                        <h4 style="color: #011a42 ;" class="modal-title bold px-2" id="receiver-label">Message</h4>
                    </div>
                    <div class="col-6 dfjeac p-0 m-0">
                        <a href="dashboard.php?id=<?php echo $_GET['id'] ?>" style="color: #011a42 ;" align='right' type="button" class="m-0 btn border-0 bg-transparent fs-4">
                            <?php include '../icons/cross_icon.php'; ?>
                        </a>
                    </div>
                </div>

            </div>
            <div class="modal-body p-2 " style="max-height: 520px; overflow-y: auto;" id="modalBody2">
                <!-- Messages displayed here -->
                <?php
                date_default_timezone_set('Asia/Kolkata');
                $sender_id = $_GET['id'];
                $receiver_id = $_GET['receiver_id'];

                // Update seen = 1
                $u_query = "UPDATE messages SET seen = 1 WHERE sender_id = $receiver_id AND receiver_id = $sender_id";
                $u_result = mysqli_query($conn, $u_query);

                $query = "SELECT * FROM messages WHERE 
    (sender_id = $sender_id AND receiver_id = $receiver_id) OR 
    (sender_id = $receiver_id AND receiver_id = $sender_id)";
                $result = mysqli_query($conn, $query);
                $n_date = date('d-m-Y', strtotime(857088000));
                if ($result->num_rows > 0) {
                    while ($rows = mysqli_fetch_assoc($result)) {
                        $date = date('j F Y', $rows['added_date']);

                        if ($date !== $n_date) {
                ?>
                            <div class="row w-100 m-0 p-0">
                                <div class="box-shadow-bottom col-5 p-0"></div>
                                <div class="col-2 p-0">
                                    <p align='center' style="font-size: 13px" class="m-0 text-secondary">
                                        <?php
                                        $current_date = date('j F Y', time());
                                        if ($date === $current_date) {
                                            echo 'Today';
                                        } else {
                                            echo $date;
                                        }

                                        ?>
                                    </p>
                                </div>
                                <div class="col-5 box-shadow-bottom p-0"></div>
                            </div>
                            <?php
                        };
                        if ($sender_id !== $rows['sender_id']) {
                            $r_query = "SELECT * FROM users WHERE id = $receiver_id";
                            $r_result = mysqli_query($conn, $r_query);
                            $r_rows = mysqli_fetch_assoc($r_result);


                            if ($rows['file'] !== '') {
                            ?>
                                <h4 class="p-0 m-0 dfjsac">
                                    <img class="rounded-circle" width="40" height="40" src="../images/<?php echo $r_rows['image'] ?>" alt="profile-img" />
                                    <span align='left' style="background-color:#bdbebf ; max-width: 65%" class="h5 mt-2 text-dark px-3 py-2 rounded-4">
                                        <div class="row">
                                            <div class="col-8">
                                                <?php
                                                $filePath = '../images/' . $rows['file'];
                                                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                                                $fileMimeType = finfo_file($finfo, $filePath);
                                                finfo_close($finfo);

                                                if (strpos($fileMimeType, 'image/') === 0) {
                                                ?>
                                                    <img src="<?php echo $filePath; ?>" class="rounded-3" width="110px" alt="img">
                                                <?php
                                                } elseif (strpos($fileMimeType, 'video/') === 0) {
                                                ?>
                                                    <video controls width="110px">
                                                        <source src="<?php echo $filePath; ?>" type="<?php echo $fileMimeType; ?>">
                                                    </video>
                                                <?php
                                                } elseif (strpos($fileMimeType, 'application/pdf') === 0) {
                                                ?>
                                                    <a class="p-0 m-0" href="<?php echo $filePath; ?>" target="_blank">
                                                        <?php include '../icons/pdf_icon.php'; ?>
                                                    </a>
                                                <?php
                                                } elseif (strpos($fileMimeType, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') === 0) {
                                                ?>
                                                    <a href="<?php echo $filePath; ?>" target="_blank">
                                                        <?php include '../icons/docx_icon.php'; ?>
                                                    </a>
                                                <?php

                                                } elseif (strpos($fileMimeType, 'application/msword') === 0) {
                                                ?>
                                                    <a href="<?php echo $filePath; ?>" target="_blank">
                                                        <?php include '../icons/doc_icon.php'; ?>
                                                    </a>
                                                <?php
                                                } else {
                                                    echo $rows['content'];
                                                }
                                                ?>
                                            </div>
                                            <div class="col-4 dfjsae px-1"><span style="font-size: 10px" class="text-secondary"><?php echo date('h:i A', $rows['added_date']); ?></span></div>
                                        </div>
                                    </span>
                                </h4>
                            <?php
                            }
                            if ($rows['content'] !== '') {
                            ?>
                                <h4 class="p-0 m-0 dfjsac">
                                    <img class="rounded-circle" width="40" height="40" src="../images/<?php echo $r_rows['image'] ?>" alt="profile-img" />
                                    <span align='left' style="background-color:#bdbebf; max-width: 65%" class=" h5 mt-2 text-dark px-3 py-2 rounded-4">
                                        <?php echo $rows['content'] ?>
                                        <span style="font-size: 10px" class="text-secondary"><?php echo date('h:i A', $rows['added_date']); ?></span>
                                    </span>
                                </h4>
                            <?php
                            }
                        } else {
                            $s_query = "SELECT * FROM users WHERE id = $sender_id";
                            $s_result = mysqli_query($conn, $s_query);
                            $s_rows = mysqli_fetch_assoc($s_result);

                            if ($rows['file'] !== '') {
                            ?>
                                <h4 class="p-0 m-0 dfjeac">
                                    <span align='right' style="background-color:#bdbebf; max-width: 65%" class=" h5 mt-2 text-dark px-3 py-2 rounded-4">
                                        <div class="row">
                                            <div class="col-8" style="border: 1px solid #bdbebf">
                                                <?php
                                                $filePath = '../images/' . $rows['file'];
                                                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                                                $fileMimeType = finfo_file($finfo, $filePath);
                                                finfo_close($finfo);

                                                if (strpos($fileMimeType, 'image/') === 0) {
                                                ?>
                                                    <img src="<?php echo $filePath; ?>" class="rounded-3" width="110px" alt="img">
                                                <?php
                                                } elseif (strpos($fileMimeType, 'video/') === 0) {
                                                ?>
                                                    <video controls width="110px">
                                                        <source src="<?php echo $filePath; ?>" type="<?php echo $fileMimeType; ?>">
                                                    </video>
                                                <?php
                                                } elseif (strpos($fileMimeType, 'application/pdf') === 0) {
                                                ?>
                                                    <a class="p-0 m-0" href="<?php echo $filePath; ?>" target="_blank">
                                                        <?php include '../icons/pdf_icon.php'; ?>
                                                    </a>
                                                <?php
                                                } elseif (strpos($fileMimeType, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') === 0) {
                                                ?>
                                                    <a href="<?php echo $filePath; ?>" target="_blank">
                                                        <?php include '../icons/docx_icon.php'; ?>
                                                    </a>
                                                <?php

                                                } elseif (strpos($fileMimeType, 'application/msword') === 0) {
                                                ?>
                                                    <a href="<?php echo $filePath; ?>" target="_blank">
                                                        <?php include '../icons/doc_icon.php'; ?>
                                                    </a>
                                                <?php
                                                } else {
                                                    echo "Unsupported file type.";
                                                }
                                                ?>
                                            </div>
                                            <div class="col-4 dfjsae px-1"><span style="font-size: 10px" class="text-secondary"><?php echo date('h:i A', $rows['added_date']); ?></span></div>
                                        </div>
                                    </span>
                                    <img class="rounded-circle" width="40" height="40" src="../images/<?php echo $s_rows['image'] ?>" alt="profile-img" />
                                </h4>
                            <?php
                            }
                            if ($rows['content'] !== '') {
                            ?>
                                <h4 class="p-0 m-0 dfjeac">
                                    <span align='right' style="background-color:#bdbebf; max-width: 65%" class=" h5 mt-2 text-dark px-3 py-2 rounded-4">
                                        <?php echo $rows['content'] ?>
                                        <span style="font-size: 10px" class="text-secondary"><?php echo date('h:i A', $rows['added_date']); ?></span>
                                    </span>
                                    <img class="rounded-circle" width="40" height="40" src="../images/<?php echo $s_rows['image'] ?>" alt="profile-img" />
                                </h4>
                            <?php
                            }
                            ?>
                    <?php
                        }
                        $n_date = $date;
                    }
                } else {
                    ?>
                    <h6 class="dfjcac p-4">No messages found</h6>
                <?php
                }
                ?>
            </div>
            <div>
                <!-- Sending Message Input -->
                <form style="background-color: #659bf7;" action="handle_sms.php" method="post" enctype="multipart/form-data" class=" py-2 rounded-bottom">
                    <div class="row m-0">
                        <div class="col-10">
                            <div class="row m-0 p-0">
                                <div class="col-11 m-0 p-0">
                                    <textarea class="form-control p-2 rounded-3 fs-5 box-shadow resize-none" type="text" name="content" placeholder="Type Here" rows="1"></textarea>
                                </div>
                                <div class="col-1 m-0 p-0">
                                    <button class="btn bg-transparent border-0 padding-2px" id="uploadButton">
                                        <?php include '../icons/file_icon.php'; ?>
                                    </button>
                                </div>
                            </div>
                            <input type="file" class="form-control" name="image" accept="image/*,video/*,.pdf,.doc,.docx" id="fileInput" />
                            <input id="receiver_id1" type="hidden" name="receiver_id1" value="<?php echo $_GET['receiver_id'] ?>">
                            <input id="sender_id1" type="hidden" name="sender_id1" value="<?php echo $_GET['id'] ?>">
                        </div>
                        <div class="col-2 dfjcac p-0 m-0">
                            <button style="background-color: #011a42; color: #97baf7" type="submit" value="Send" name='send_msg' class="btn btn-md bold box-shadow">Send</button>
                        </div>
                    </div>
                </form>
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