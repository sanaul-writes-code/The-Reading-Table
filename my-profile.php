<?php

// Set session cookie parameters
$sessionLifetime = 0; // expire when the browser is closed
$sessionPath = '/';
$sessionDomain = ''; // optional, specify your domain
$sessionSecure = true; // if using HTTPS
$sessionHttpOnly = true; // prevent JavaScript access to the session cookie

session_set_cookie_params($sessionLifetime, $sessionPath, $sessionDomain, $sessionSecure, $sessionHttpOnly);

session_start();

// Check if user is authenticated
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    // User is not authenticated, redirect to login page
    header('Location: login.php');
    exit();
}

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

$sql = "SELECT * FROM users WHERE username ='{$_SESSION["uname"]}'";
$result = mysqli_query($conn, $sql);

//begin here
/*if (mysqli_num_rows($result) > 0) {
    echo "<h2>My Profile</h2>";
    echo "<table>";
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr><th>First Name: </th><td>".$row["first_name"]."</td></tr>";
        echo "<tr><th>Last Name: </th><td>".$row["last_name"]."</td></tr>";
        echo "<tr><th>Email: </th><td>".$row["email"]."</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

$get_data_sql = "SELECT * FROM users WHERE username ='{$_SESSION['uname']}'";
$data_result = mysqli_query($conn, $get_data_sql);

if (mysqli_num_rows($data_result) > 0) {
    while($row = mysqli_fetch_assoc($data_result)) {
        //var_dump($result);
        $fname = $row['first_name'];
        $lname = $row['last_name'];
    }
} else {
    echo "0 results";
}

mysqli_close($conn);*/
//end here
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Profile</title>
    <link href="alternate-style.css" rel="stylesheet" >
    <style>
        #profile-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        #updateForm {
            margin-top: 40px;
        }
        #change-password {
            margin-top: 40px;
        }
        #profile-table {
            margin-bottom: 40px;
        }
        #profile-table th {
            padding: 10px;
        }
        #profile-table td {
            padding: 10px;
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

<!--<p>
    <a href="update.php">Update My Info</a>
</p>-->
<div id="profile-content" style="padding: 10px">
    <?php
    if (mysqli_num_rows($result) > 0) {
        echo "<h2>My Profile</h2>";
        echo "<table id='profile-table'>";
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr><th>Username: </th><td>".$row["username"]."</td></tr>";
            echo "<tr><th>First Name: </th><td>".$row["first_name"]."</td></tr>";
            echo "<tr><th>Last Name: </th><td>".$row["last_name"]."</td></tr>";
            echo "<tr><th>Email: </th><td>".$row["email"]."</td></tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }

    $get_data_sql = "SELECT * FROM users WHERE username ='{$_SESSION['uname']}'";
    $data_result = mysqli_query($conn, $get_data_sql);

    if (mysqli_num_rows($data_result) > 0) {
        while($row = mysqli_fetch_assoc($data_result)) {
            //var_dump($result);
            $fname = $row['first_name'];
            $lname = $row['last_name'];
        }
    } else {
        echo "0 results";
    }

    mysqli_close($conn);
    ?>
<button id="changeInfoBtn">Update My Info</button>
<div id="updateForm" style="display: none;">
<form method="post" action="update.php">
    <label for="firstname">First Name:</label><br>
    <input type="text" id="firstname" name="firstname" value="<?php echo $fname; ?>"><br><br>

    <label for="lastname">Last Name:</label><br>
    <input type="text" id="lastname" name="lastname" value="<?php echo $lname; ?>"><br><br>

    <button type="submit">Update</button>
</form>

<form id="change-password" method="post" action="update.php">
    <label for="oldpwd">Old Password:</label><br>
    <input type="text" id="oldpwd" name="oldpwd"><br><br>
    <label for="newpwd">New Password:</label><br>
    <input type="text" id="newpwd" name="newpwd"><br><br>
    <label for="confnewpwd">Confirm New Password:</label><br>
    <input type="text" id="confnewpwd" name="confnewpwd"><br><br>
    <button type="submit">Update</button>
</form>
</div>
</div>

<script>
    document.getElementById('changeInfoBtn').addEventListener('click', function() {
        document.getElementById('updateForm').style.display = 'block';
    });
    document.getElementById('change-password').addEventListener('submit', function(event) {
        var newPassword = document.getElementById('newpwd').value;
        var confirmNewPassword = document.getElementById('confnewpwd').value;

        // Check if the passwords match
        if (newPassword !== confirmNewPassword) {
            // Prevent form submission
            event.preventDefault();
            alert('New password and confirm new password do not match');
        }
        if (newPassword === "") {
            // Prevent form submission
            event.preventDefault();
            alert('New password cannot be empty');
        }
    });
</script>

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
