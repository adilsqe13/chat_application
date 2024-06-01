<?php include 'header.php'; ?>
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/default.css">
</head>

<body>

    <?php
    include '../db.php';
    if (isset($_POST['signup'])) {
        $timestamp = time();
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $file_name = $timestamp . $_FILES["image"]["name"];
        $tempname = $_FILES["image"]["tmp_name"];
        $folder = '../images/' . $file_name;


        if ($name !== '' && $email !== '' &&  $password !== '' &&  $file_name !== null) {
            move_uploaded_file($tempname, $folder);

            $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if (!$row['email']) {
                // Hash the password before storing it
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $sql = "INSERT INTO users (name, email, password, image, added_date)
                VALUES ('$name', '$email', '$hashed_password', '$file_name', '$timestamp')";

                if ($conn->query($sql) === TRUE) {
                    $query = "SELECT * FROM users WHERE email = ?";
                    if ($stmt = mysqli_prepare($conn, $query)) {
                        mysqli_stmt_bind_param($stmt, "s", $email);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        if ($rows = mysqli_fetch_assoc($result)) {
                            $id = $rows['id'];
                            header("Location: dashboard.php?id=$id");
                            exit();
                        }
                    }
                } else {
                    header("Location: signup_page.php?msg=Error: Something went wrong");
                }
            } else {
                echo "<script>alert('Email is alredy registered.')</script>";
            }
        } else {
            header("Location: signup_page.php?msg=Error: Please fill all the fields");
        }
    }
    ?>
    <div style="background-color: #011a42;" class="container box-shadow-dark px-4 py-3 rounded-4 center w-25">
        <h1 align='left' class="text-secondary">Sign Up</h2>
            <div style="height: 20px">
                <h6 class="text-danger  msg-display" align='left'><?php if (isset($_GET['msg'])) {
                                                                        echo $_GET['msg'];
                                                                    } ?></h6>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="mb-3 mt-2">
                    <label class="form-label text-light">Name</label>
                    <input type="text" name='name' class="form-control p-2" placeholder="Enter Name">
                </div>
                <div class="mb-3">
                    <label class="form-label text-light">Email</label>
                    <input type="email" name='email' class="form-control p-2" placeholder="example@gmail.com">
                </div>
                <div class="mb-3">
                    <label class="form-label text-light">Password</label>
                    <input type="password" name='password' class="form-control p-2" placeholder="******">
                </div>
                <div class="">
                    <button id="uploadButton" class="bg-transparent border-0 text-secondary">
                        <?php include '../icons/profile_pic_icon.php'; ?>
                    </button>
                    <label class="form-label text-secondary">Choose profile photo</label>
                    <input id="fileInput" type="file" class="form-control" name="image" accept="image/*" />
                </div>
                <input name="signup" type="submit" value="Sign Up" class="form-control btn btn-lg btn-info bold mt-3"></input>
                <p class="dfjcac"><a align='center' href="../index.php" class="btn btn-outline-primary mt-3 tdn">Login</a></p>
            </form>
    </div>

    <script>
        //  Select Profile Picture Logic
        const uploadButton = document.getElementById('uploadButton');
        const fileInput = document.getElementById('fileInput');
        uploadButton.addEventListener('click', (event) => {
            event.preventDefault();
            fileInput.click();
        });
        fileInput.addEventListener('change', () => {
            const imagePreview = document.getElementById('imagePreview');
            const file = fileInput.files[0];
            console.log(file);
            if (fileInput.files.length > 0) {
                const file_icon = document.getElementById('uploadButton');
                // Display image preview
                const reader = new FileReader();
                reader.onload = (e) => {
                    file_icon.innerHTML = `<img src='${e.target.result}' class='rounded-3 mt-2' width='100px' height='100px'></img>`
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

    <?php include 'footer.php'; ?>