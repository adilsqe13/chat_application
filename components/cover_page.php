<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/default.css">
</head>

<body>

    <?php
    include 'db.php';
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if ($email !== '' && $password !== '') {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
    
            if ($row) {
                // Verify the password
                if (password_verify($password, $row['password'])) {
                    $id = $row['id'];
                    header("Location: components/dashboard.php?id=$id");
                    exit();
                } else {
                    header("Location: index.php?msg=Error: Invalid email or password");
                }
            } else {
                header("Location: index.php?msg=Error: Invalid email or password");
            }
            $stmt->close();
        } else {
            header("Location: index.php?msg=Error: Please fill all the fields");
        }
    }
    $conn->close();
    ?>

    <div style="background-color: #011a42;" class="container box-shadow-dark px-4 py-4 rounded-4 center w-25">
        <h1 align='left' class="text-secondary">Login</h1>
        <form method="POST">
            <div class="mb-3 margin-top-50">
                <label class="form-label text-light">Email</label>
                <input type="email" name="email" class="form-control fs-5 p-2" placeholder="example@gmail.com">
            </div>
            <div class="mb-2">
                <label class="form-label text-light">Password</label>
                <input type="password" name='password' class="form-control fs-5 p-2" placeholder="******">
            </div>
            <!-- Invalid Credentials message display -->
            <div style="height: 20px">
                <h6 class="text-danger  msg-display" align='left'><?php if (isset($_GET['msg'])) {
                                                                        echo $_GET['msg'];
                                                                    } ?></h6>
            </div>
            <input name="login" type="submit" value="Login" class=" mt-3 form-control btn btn-info bold btn-lg"></input>
            <p class= "dfjcac"><a align='center' href="components/signup_page.php" class="btn btn-outline-primary mt-5 tdn">Sign-Up</a></p> 
        </form>
    </div>