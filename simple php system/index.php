<?php
session_start();
include("php/config.php");

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $query = "SELECT * FROM admin_users WHERE email='$email'";
    $result = mysqli_query($con, $query) or die("Select Error: " . mysqli_error($con));

    $row = mysqli_fetch_assoc($result);

    if ($row) {
        if ($row['password'] === $password) { 
            $_SESSION['valid'] = true; 
            $_SESSION['username'] = $row['username'];
            $_SESSION['id'] = $row['id'];


            header("Location: home.php");
            exit();
        } else {
            echo "<div class='message'>
            <p>Wrong Email or Password</p>
        </div><br>";
            echo "<a href='index.php'><button class='btn'>Go Back</button></a>";
        }
    } else {
        echo "<div class='message'>
            <p>Wrong Email or Password</p>
        </div><br>";
    }
}

if (isset($_GET['registration']) && $_GET['registration'] == 'success') {
    echo "<div class='message'>
            <p>Registration successful! Please log in.</p>
          </div> <br>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Login</title>
</head>

<body>
    <div class="container">
        <div class="box form-box">
            <header>Login</header>
            <form action="" method="post">
                <div class="field input">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                </div>
                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Login" required>
                </div>
                <div class="links">
                    Don't have an account? <a href="register.php">Sign Up Now</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
