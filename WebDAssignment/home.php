<?php
// starts login session
session_start();
require_once "webdevdbconnect.php";

// LOGIN USER
if (isset($_POST['Username'])) {
    // used to avoid sql injection
    $un = mysqli_real_escape_string($conn, $_POST['Username']);
    $pw = mysqli_real_escape_string($conn, $_POST['Password']);
  
    // if the username field is empty, this alert prompts the user
    if (empty($un)) {
        // error message alerts user that a username is required
        array_push($errors, "Username is required");
    }
    // if the password field is empty, this alert prompts the user
    if (empty($pw)) {
        // error message alerts the user that a password is required
        array_push($errors, "Password is required");
    }
    if (count($errors) == 0) {
        // retrieves the username and password from the database
        $query = "SELECT * FROM user WHERE Username='$un' AND Password='$pw'";
        $results = mysqli_query($conn, $query);
        // if the number of rows retrieved from the database are equal to 1
        if (mysqli_num_rows($results) == 1) {
          $_SESSION['Username'] = $un;
          $_SESSION['success'] = "You are now logged in";
          // re-directs user to library.php
          header('location: library.php');
        }else {
            // alerts the user that the username/password in incorrect to the database info
            array_push($errors, "Wrong username/password combination");
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport"content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="home.css">
        
        <title>Login</title>
    </head>
    <body>
        <center>
        <h2>Login Below!</h2>
        <form method="POST" action="home.php">
            <?php include('errors.php'); ?>
            <label for="Username"><b>Username</b></label><br>
            <input type="text" id="Username" name="Username"placeholder="Your username...">
            <br>
            <label for="Password"><b>Password</b></label><br>
            <input type="password" id="Password" name="Password" placeholder="Your password is...">
            <br><br>
            <input type="submit" id="submit" value="submit">
            <br><br><br><br>
            <h5>Dont have an account? Register here!</h5>
            <a href="register.php">Register</a>
            </a>
        </form>
        </center>
        <div class="footer">
        <p>&copy; Ben's Site</p>
    </div>
    </body>
</html>