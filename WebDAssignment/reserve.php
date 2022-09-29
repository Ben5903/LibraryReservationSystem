<?php
    // start login session
    session_start();
    require_once "webdevdbconnect.php";
    
    if (isset ($_GET['ISBN']))
    {
        // Grabs info from ISBN and Reserved from database
        $ISBN = $_GET['ISBN'];
        $reserved = $_GET['Reserved'];
        $un = $_SESSION['Username'];
        // preparing the sql for reservations, whereby the books are inserted into the reservations database
        $sql = "INSERT INTO reservations (ISBN, Username, ReservedDate) VALUES ('$ISBN', '$un', now())";
        if ($conn->query($sql) === TRUE)
        {
            // changes the library from N to Y
            $sql ="UPDATE library SET Reserved = 'Y' WHERE ISBN = '$ISBN'";
            if ($conn->query($sql) === TRUE) 
            {
                // indicates success
                echo "Record updated succesfully";
            }
            else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

    }
    ?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport"content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="reserve.css">
        
        <title>Registation</title>
    </head>
    <body>
    <a id="logout" href="home.php?logout=true">Logout</a>
    <h2 id="title">Here is the reserved page!<h2>   
        <center>
		<nav id="nav">
			<a href ="search.php">Search for a book here!</a> - 
			<a href ="library.php">View all books here!</a> -
			<a href ="register.php">Register a new account here!</a>
        </nav>
        </center>
    <br><br>
    <h4 id="subtitle">Below is a list of books you have reserved:<h4>
    <br><br><br><br>
    <?php
    // selecting and printing from library whereby the reserves is set to Y
    $sql = "SELECT * FROM library WHERE Reserved = 'Y'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        
        // output data of each row into a table
        echo "<table border='1',";
        echo "<tr><th>ISBN</th>
              <th>BookTitle</th>
              <th>Author</th>
              <th>Edition</th>
              <th>Year</th>
              <th>Category</th>
              <th>Reserved</th>";
        // displays the contents within the database into a table on the webpage       
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>";
            echo $row['ISBN'];
            echo "</td><td>";
            echo $row['BookTitle'];
            echo "</td><td>";
            echo $row['Author'];
            echo "</td><td>";
            echo $row['Edition'];
            echo "</td><td>";
            echo $row['Year'];
            echo "</td><td>";
            echo $row['Category'];
            echo "</td><td>";
            echo '<a href="library.php?ISBN='.$row['ISBN'].'&Reserved='.$row['Reserved'].'"><button>Unreserve</button></a>';
            echo "</tr>\n";
        }
        echo "</table>\n";
    }
    // if there is not books in the reserves, '0 results' displays for the user
     else {
        echo "<p align=center>0 results</p>";
    }
    
?>

<div class="footer">
        <p>&copy; Ben's Site</p>
</div>
</body>


