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
            <ul style="background-color:#252626 ;"; class="w-15 dropdown-menu p-2 box-shadow-dark m-0 rounded-4">
                <li class="p-2"><span class="fs-5 bold text-off-white"><?php echo $user['name'] ?></span></li>
                <li class=" p-2">
                    <a title="my_profile" href="profile_page.php?id=<?php echo $user['id']?>" class=" btn p-0 rounded-5 border-0 dfjsac">
                        <?php include '../icons/profile_icon.php'; ?>
                        <?php echo "<span class='text-off-white fs-5 mb-1 px-2'>Profile</span>" ?>
                    </a>
                </li>
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
                                        <div style="width: 40px; color: red" class=" bold fs-5 dfjeac p-1">
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
                                        <a href="chat_page.php?id=<?php echo $user_id ?>&&receiver_id=<?php echo $row['id'] ?>" class=" bg-transparent border-0">
                                            <?php include '../icons/msg_icon.php'; ?>
                                        </a>
                                    </div>
                                    <div class="col-4 dfjeac">
                                        <a href="chat_page.php?id=<?php echo $user_id ?>&&receiver_id=<?php echo $row['id'] ?>" class="btn btn-outline-info bold box-shadow-dark">Send</a>
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


    <?php include 'footer.php'; ?>