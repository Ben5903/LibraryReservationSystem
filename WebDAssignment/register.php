<?php
require_once "webdevdbconnect.php";
if (isset($_POST['Username']) && isset($_POST['Password']) && isset($_POST['FirstName']) && isset($_POST['Surname']) && isset($_POST['AddressLine1']) && isset($_POST['AddressLine2']) && isset($_POST['City']) && isset($_POST['Telephone']) && isset($_POST['Mobile'])) {
    $un = $conn->real_escape_string($_POST['Username']);
    $pw = $conn->real_escape_string($_POST['Password']);
    $fn = $conn->real_escape_string($_POST['FirstName']);
    $sn = $conn->real_escape_string($_POST['Surname']);
    $al1 = $conn->real_escape_string($_POST['AddressLine1']);
    $al2 = $conn->real_escape_string($_POST['AddressLine2']);
    $cy = $conn->real_escape_string($_POST['City']);
    $tp = $conn->real_escape_string($_POST['Telephone']);
    $mb = $conn->real_escape_string($_POST['Mobile']);

    // if the fields are empty, error messages promts user
    if (empty($un)) { array_push($errors, "Username is required"); }
    if (empty($pw)) { array_push($errors, "Password is required"); }
    if (empty($fn)) { array_push($errors, "First Name is required"); }
    if (empty($sn)) { array_push($errors, "Surname is required"); }
    if (empty($al1)) { array_push($errors, "Address Line 1 is required"); }
    if (empty($al2)) { array_push($errors, "Address Line 2 is required"); }
    if (empty($cy)) { array_push($errors, "City is required"); }
    if (empty($tp)) { array_push($errors, "Telephone is required"); }
    if (empty($un)) { array_push($errors, "Username is required"); }

    // checks if the username inputted already exists
    $user_check_query = "SELECT * FROM user WHERE Username='$un' LIMIT 1";
    $result = mysqli_query($conn, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    
    if ($user) { 
        // if user exists
        if ($user['Username'] === $un) {
            // prompts to user to show that Username already exists
            array_push($errors, "Username already exists");
        }
    }
    // puts the information inputted into the User database
    $sql = "INSERT INTO User (Username, Password, FirstName, Surname, AddressLine1, AddressLine2, City, Telephone, Mobile)  VALUES ('$un', '$pw', '$fn', '$sn', '$al1', '$al2', '$cy', '$tp', '$mb')";
    if ($conn->query($sql) === TRUE) {
        // shows user that account is successfully created
        echo "Account Successfully Created!";
        // link to bring user to library.php 
        echo "Success - <a href='/WebDassignment/library.php'> Continue to the library...</a>";
        $_SESSION['Username'] = $un;
        $_SESSION['success'] = "You are now logged in";
        header('location: home.php');
        return;
    }   else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }          
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport"content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="register.css">
        
        <title>Registation</title>
    </head>
    <body> 
        <center>
        <h2>Registration</h2>
        <div class = container>
        <form method="POST">
            <label for="Username"><b>Username</b></label><br>
            <input type="text" id="Username" name="Username" placeholder="Your username..." required>
            <br>
            <label for="Password"><b>Password</b></label><br>
            <input type="password" id="Password" name="Password" placeholder="Your password is..." required>
            <br><br>
            <label for="password_confirm"><b>Please Re-enter your Password</b></label><br>
            <input type="password" id="password_confirm" name="password_confirm" placeholder="Please enter your password again..." required>
            <br><br>
            <label for="FirstName"><b>First Name</b></label><br>
            <input type="text" id="FirstName" name="FirstName" placeholder="Your First Name..." required>
            <br>
            <label for="Surname"><b>Surname</b></label><br>
            <input type="text" id="Surname" name="Surname" placeholder="Your Surname is..." required>
            <br>
            <label for="AddressLine1"><b>Address Line 1</b></label><br>
            <input type="text" id="AddressLine1" name="AddressLine1" placeholder="15 Jungle Avenue" required>
            <br><br>
            <label for="AddressLine2"><b>Address Line 2</b></label><br>
            <input type="text" id="AddressLine2" name="AddressLine2" placeholder="Address Line 2..." required>
            <br><br>
            <label for="City"><b>City</b></label><br>
            <input type="text" id="City" name="City" placeholder="Your City is..." required>
            <br><br>
            <label for="Telephone"><b>Telephone</b></label><br>
            <input type="tel" id="Telephone" name="Telephone" placeholder="Your telephone is..." required>
            <br><br>
            <label for="Mobile"><b>Mobile</b></label><br>
            <input type="tel" id="Mobile" name="Mobile" placeholder="Your mobile is..." required>
            <br><br>
            <br><br>
            <input type="submit" value="Create Account">
            <br><br><br><br>
            <h5>Already Have An Account Registered? Login Below!</h5>
            <a href="home.php">Login</a>
            </a>
        </form>
        </center>
    </body>
</html>