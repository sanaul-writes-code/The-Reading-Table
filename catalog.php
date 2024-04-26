<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Catalog</title>
    <link href="alternate-style.css" rel="stylesheet" >
    <style>
        /* Main content container */
        .main-content {
            margin-top: 10px; /* Adjust according to the height of the navigation bar */
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Card styles */
        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .card {
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 300px; /* Fixed width for the card */
            height: auto; /* Let the height adjust based on content */
            overflow: hidden; /* Hide overflowing content */
            display: flex;
            flex-direction: column;
        }
        .image-container {
            height: 360px; /* Fixed height for the image */
            overflow-y: auto; /* Enable vertical scrolling */
        }
        .card img {
            width: 100%; /* Make the image fill the width of the card */
            height: auto; /* Allow the image to adjust its height */
            object-fit: cover; /* Ensure the image maintains aspect ratio and covers the entire space */
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }
        .card-content {
            padding: 10px;
            overflow: auto; /* Enable scrolling for overflow content */
        }
    </style>
</head>
<body>

<!-- Navigation bar -->
<div class="navbar">
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
            echo "<button id='logoutBtn'><a href='logout.php'>Logout</a></button>";

        } else {
            // User is not authenticated, show login button and hide logout button
            $login = 'login.php';
            echo "<button id='loginBtn'>Login</button>";
        }
        ?>
    </div>
</div>

<!-- Main content -->
<div class="main-content">
    <h1>Catalog</h1> <!-- Heading for catalog -->
    <div class="card-container">
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

        $sql = "SELECT * FROM books ORDER BY title";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            // Output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                echo "<div class='card'>";
                echo "<div class='image-container'>";
                echo "<img src='" . "assets/" . $row["image_url"] . "' alt='" . $row["title"] . "' />";
                echo "</div>"; // Close image-container
                echo "<div class='card-content'>";
                echo "<h3>" . $row["title"] . "</h3>";
                echo "<p><strong>Author:</strong> " . $row["author"] . "</p>";
                echo "<p><strong>ISBN:</strong> " . $row["isbn"] . "</p>";
                echo "<p><strong>Genre:</strong> " . $row["genre"] . "</p>";
                echo "<p><strong>Year Published:</strong> " . $row["year_published"] . "</p>";
                echo "<p><strong>Description:</strong> " . $row["description"] . "</p>";
                echo "</div>"; // Close card-content
                echo "</div>"; // Close card
            }
        } else {
            echo "0 results";
        }

        mysqli_close($conn);
        ?>
    </div> <!-- Close card-container -->
</div> <!-- Close main-content -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var loginBtn = document.getElementById('loginBtn');
        var logoutBtn = document.getElementById('logoutBtn');

        // Add event listener for login button click
        loginBtn.addEventListener('click', function() {
            // Redirect to login page
            window.location.href = 'login.php';
        });

        // Add event listener for logout button click
        logoutBtn.addEventListener('click', function() {
            // Perform logout operation, e.g., redirect to logout script
            window.location.href = 'logout.php';
        });

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
