<?php
        require_once "webdevdbconnect.php";
        // starts login session
        session_start();
        $un = $_SESSION['Username'];
        if (isset ($_GET['ISBN']))
        {
            $ISBN = $_GET['ISBN'];
            $reserved = $_GET['Reserved'];
            if($reserved === 'Y') {
                $sql = "DELETE FROM reservations WHERE ISBN = '$ISBN'";
                // checking if reserved is set to Y
                if ($conn->query($sql) === TRUE)
                {
                    // changing the reserved to N
                    $sql ="UPDATE library SET Reserved = 'N' WHERE ISBN = '$ISBN'";
                    if ($conn->query($sql) === TRUE) 
                    {
                        // indicating success where reserved is has changed
                        echo "Record updated succesfully";
                    }
                    else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
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
    <link rel="stylesheet" href="search.css">
    <title>The Library</title>
</head>
<body>
    <a id="logout" href="home.php?logout=true">Logout</a>
    <center>
		<nav id="nav">
			<a href ="library.php">View all the books here!</a> - 
			<a href ="reserve.php">View your reserved books!</a> -
			<a href ="register.php">Register a new account here!</a>
        </nav>
        </center>
    <h2 id="title">Welcome to the Search Page!<h2>
        <br><br>
    <h2 id="title">Search for a book in our system here!<h2>
    
    <form action="search.php" method="GET">
        <input type="text" name="search" placeholder="Search for a book...">
        <input type="submit" value="Search">
    </form>  
    <br><br><br>
    <?php
    $output = '';
    if(isset($_GET['search'])){
        $searchq = trim($_GET['search']);
        // preparing the sql, selecting all from the library where it picks directly from the author and booktitle
        $query = "SELECT * FROM library WHERE BookTitle LIKE '%$searchq%' OR Author LIKE '%$searchq%'";
        $result1 = mysqli_query($conn, $query);
        //counts the number of rows in the database
        $count = mysqli_num_rows($result1);
        // if statement if not rows have been counted
        if($count == 0) {
            // indicates no search results were found
            echo '<p align=center> There was no search results!</p>';
        } 
        else{
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
                while ($row = $result1->fetch_assoc()) {
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
                        echo '<a href="library.php?ISBN='.$row['ISBN'].'&Username='.$un.'&Reserved='.$row['Reserved'].'"><button>Unreserve</button><a>';
                    }
                    else{
                        // shows reserve in the reserved section
                        echo '<a href="library.php?ISBN='.$row['ISBN'].'&Username='.$un.'&Reserved='.$row['Reserved'].'"><button>Reserve</button></a>';
                    }
                    echo "</tr>\n";
                }
                echo "</table>\n";
        }
    }
?>

<div class="footer">
        <p>&copy; Ben's Site</p>
</div>
</body>
</html>
