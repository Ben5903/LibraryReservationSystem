<?php
        require_once "webdevdbconnect.php";
        // starts login session
        session_start();
        $un = $_SESSION['Username'];
        if (isset ($_GET['ISBN']))
        {
            // Grabs info from ISBN and Reserved from database
            $ISBN = $_GET['ISBN'];
            $reserved = $_GET['Reserved'];
            // if reserved is set to Y
            if($reserved === 'Y') {
                $un = $_SESSION['Username'];
                // removes the reservation from reservations
                $sql = "DELETE FROM reservations WHERE ISBN = '$ISBN'";
                if ($conn->query($sql) === TRUE)
                {
                    // changes the library from Y to N
                    $sql ="UPDATE library SET Reserved = 'N' WHERE ISBN = '$ISBN'";
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
            } else {
            $un = $_SESSION['Username'];
            // preparing the sql for reservations, whereby the books are inserted into the reservations database
            $sql = "INSERT INTO reservations (ISBN, Username, ReservedDate) VALUES ('$ISBN', '$un', now())";
            if ($conn->query($sql) === TRUE)
            {
                // sets the Reserved to Y
                $sql ="UPDATE library SET Reserved = 'Y' WHERE ISBN = '$ISBN'";
                if ($conn->query($sql) === TRUE) 
                {
                    // indicates success for the user
                    echo "Record updated succesfully";
                }
                else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            }
        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="library.css">
    <title>The Library</title>
    <br>
    <a id="logout" href="home.php?logout=true">Logout</a>
    <center>
		<nav id="nav">
			<a href ="search.php">Search for a book here!</a> - 
			<a href ="reserve.php">View your reserved books here!</a> -
			<a href ="register.php">Register a new account here!</a>
        </nav>
    </center>
    <h2 id="title">Welcome to the Library<h2>  

</head>
<body>
    <br><br><br>
    <?php
    // SELECTING AND PRINTING FROM LIBRARY
        $sql = "SELECT * FROM library";
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
                // if statement for distinguishing between reserve and unreserve
                if ($row['Reserved'] == 'Y')
                {
                    // shows unreserve in the reserved section
                    echo '<a href="library.php?ISBN='.$row['ISBN'].'&Username='.$un.'&Reserved='.$row['Reserved'].'"><button>Unreserve</button></a>';
                }
                else {
                    // shows reserve in the reserved section
                    echo '<a href="library.php?ISBN='.$row['ISBN'].'&Username='.$un.'&Reserved='.$row['Reserved'].'"><button>Reserve</button></a>';
                }
                echo "</tr>\n";
            }
            echo "</table>\n";
        } else {
            echo "0 results";
        }
    ?>

    <div class="footer">
        <p>&copy; Ben's Site</p>
    </div>
</body>
</html>
