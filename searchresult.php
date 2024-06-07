<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Search Result</title>
    <link href="alternate-style.css" rel="stylesheet" >
    <style>
        .wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 1px solid black;
            margin-bottom: 10px;
            padding: 10px;
        }
        /*.image {
            display: flex;
            max-width: 80%;
            overflow: hidden;
            margin: auto;
        }*/
        /*img {
            max-width: 600px;
        }*/
        .image {
            width: 300px;
            height: 360px; /* Fixed height for the image */
            overflow-y: auto; /* Enable vertical scrolling */
            margin-bottom: 10px;
        }
        .image img {
            width: 100%; /* Make the image fill the width of the card */
            height: auto; /* Allow the image to adjust its height */
            object-fit: cover; /* Ensure the image maintains aspect ratio and covers the entire space */
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }
        .resultDiv {
            max-width: 100%;
            display: flex;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 10px;
        }
        #search-result-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        table {
            min-width: 600px;
            max-width: 600px;
        }
    </style>
</head>

<body>

<!-- Navigation bar -->
<div class="navbar" id="search-result-navbar">
    <a href="index.php">Home</a>
    <a href="catalog.php">Catalog</a>
    <!--<a href="readers-choice.php">Readers' Choice</a>-->
    <a href="index.php#about">About</a>
    <a href="index.php#contact">Contact Us</a>
</div>

<!-- Second Navigation Bar -->
<div class="navbar-second">
    <div class="nav-right">
        <a href="my-profile.php">My Profile</a>
        <a href="my-list.php">My List</a>
        <!--<a href="login.php">Login</a>-->
        <a href="signup.php">Signup</a>
        <?php
        // Check if user is authenticated
        if (isset($_SESSION['authenticated']) && $_SESSION['authenticated']) {
            // User is authenticated, hide login button and show logout button
            $logout = 'logout.php';
            //echo "<button id='logoutBtn'><a href='logout.php'>Logout</a></button>";
            echo "<div class='dropdown' id='logoutBtn'>
                <button class='dropbtn'>{$_SESSION['uname']}
                    <i class='fa fa-caret-down'></i>
                </button>
                <div class='dropdown-content'>
                    <a href='my-profile.php'>My Profile</a>
                    <a href='my-list.php'>My List</a>
                    <a href='logout.php'>Logout</a>
                </div>
                </div>";
        } else {
            // User is not authenticated, show login button and hide logout button
            $login = 'login.php';
            echo "<div class='dropdown'>";
            echo "<a id='loginBtn' href='login.php'>Login</a>";
            echo "</div>";
        }
        ?>
    </div>
</div>

<div id="search-result-content">
<?php

// Database connection variables
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "book_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$searchParameter = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["searchInput"])) {
    $searchParameter = "%". $_POST["searchInput"] . "%";
}

if (isset($_POST["filter"]) && $_POST["filter"] != "none") {
    $filter = $_POST["filter"];
    $sql = "SELECT * FROM books WHERE {$filter} LIKE '{$searchParameter}'";
} else {
    $sql = "SELECT * FROM books WHERE title LIKE '{$searchParameter}' OR author LIKE '{$searchParameter}' OR isbn LIKE '{$searchParameter}' OR genre LIKE '{$searchParameter}' OR year_published LIKE '{$searchParameter}'";
}

if (isset($_POST["sort"]) && $_POST["sort"] != "none") {
    $sort = $_POST["sort"];
    $sql = $sql . " ORDER BY $sort";
}

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<h1>Search Result</h1>";
    echo "<p><a href='index.php'>Search for another book</a></p>";
    while($row = mysqli_fetch_assoc($result)) {
        echo '<div class="wrapper">'. "<div class='image'><img src='" . "assets/" . $row["image_url"] . "' alt='" . $row["title"] . "' /></div>" . "<div class='resultDiv'>" .
            "<table>
	    <tr>
    	    <td>Book ID: </td>
            <td>{$row['book_id']}</td>
        </tr>
        <tr>
    	    <td>Title: </td>
            <td>{$row['title']}</td>
        </tr>
        <tr>
    	    <td>Author: </td>
            <td>{$row['author']}</td>
        </tr>
        <tr>
    	    <td>ISBN: </td>
            <td>{$row['isbn']}</td>
        </tr>
        <tr>
    	    <td>Genre: </td>
            <td>{$row['genre']}</td>
        </tr>
        <tr>
    	    <td>Year Published: </td>
            <td>{$row['year_published']}</td>
        </tr>
        <tr>
    	    <td>Description: </td>
            <td>{$row['description']}</td>
        </tr>
        </table>
        </div>
        </div>";
    }
} else {
    echo "0 results";
}

mysqli_close($conn);
?>
<p>
    <a href="#search-result-navbar">Go back to the top of the page</a>
</p>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var loginBtn = document.getElementById('loginBtn');
        var logoutBtn = document.getElementById('logoutBtn');

        // Add event listener for login button click
        /*loginBtn.addEventListener('click', function() {
            // Redirect to login page
            window.location.href = 'login.php';
        });

        // Add event listener for logout button click
        logoutBtn.addEventListener('click', function() {
            // Perform logout operation, e.g., redirect to logout script
            window.location.href = 'logout.php';
        });*/

        // Check if user is logged in and toggle button visibility
        <?php
        if (isset($_SESSION['authenticated']) && $_SESSION['authenticated']) {
            echo 'loginBtn.style.display = "none";';
            echo 'logoutBtn.style.display = "block";';
        } else {
            echo 'loginBtn.style.display = "block";';
            echo 'logoutBtn.style.display = "none";';
        }
        ?>
    });
</script>
</body>
</html>
