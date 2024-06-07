<?php
session_start();

// Check if the username session variable is set
if (!isset($_SESSION['uname'])) {
    // Redirect the user to the login page or handle the case where the user is not logged in
    // For demonstration purposes, let's assume a redirect to the login page
    header("Location: login.php");
    exit(); // Stop further execution of the script
}

// Database connection variables
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "book_db";
$user_id = -1;

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to retrieve user_id based on username from the session variable
$sql = "SELECT user_id FROM users WHERE username = '{$_SESSION['uname']}'";

// Execute the query
$result = mysqli_query($conn, $sql);

// Check if query executed successfully
if ($result) {
    // Fetch the result row as an associative array
    $row = mysqli_fetch_assoc($result);

    // Check if a row was found
    if ($row) {
        // Store the user_id in a variable
        $user_id = $row['user_id'];
    } else {
        echo "No matching user found.";
    }
} else {
    echo "Error: " . mysqli_error($conn);
}

// Retrieve all books from the books table
$sql_books = "SELECT book_id, title FROM books";
$result_books = mysqli_query($conn, $sql_books);

// Retrieve books from the reading_list table for the current user
$sql_reading_list = "SELECT r.book_id, b.title FROM reading_list r
                     INNER JOIN books b ON r.book_id = b.book_id
                     WHERE r.user_id = $user_id";
$result_reading_list = mysqli_query($conn, $sql_reading_list);

// Check if the form to add a book to the reading list is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_book"])) {
    $book_to_add = $_POST["book_to_add"];

    // Insert the selected book into the reading_list table
    $sql_add_book = "INSERT INTO reading_list (user_id, book_id) VALUES ($user_id, $book_to_add)";

    /*if (mysqli_query($conn, $sql_add_book)) {
        echo "Book added to reading list successfully.";
        header("Location: my-list.php");
    } else {
        echo "Error adding book to reading list: " . mysqli_error($conn);
    }*/

    //begin here
    try {
        if (mysqli_query($conn, $sql_add_book)) {
            echo "New record created successfully";
            header('Location: my-list.php');
            exit; // Terminate the current script
        } else {
            //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            echo "Error adding book to reading list: " . mysqli_error($conn);
            header('Location: index.php');
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) { // MySQL error code for duplicate key
            // Handle the duplicate key error
            echo "Duplicate key error: " . $e->getMessage();
        } else {
            // Handle other database errors
            echo "Database error: " . $e->getMessage();
        }
    }
    //end here
}

// Check if the form to remove a book from the reading list is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["remove_book"])) {
    $book_to_remove = $_POST["book_to_remove"];

    // Remove the selected book from the reading_list table
    $sql_remove_book = "DELETE FROM reading_list WHERE user_id = $user_id AND book_id = $book_to_remove";

    if (mysqli_query($conn, $sql_remove_book)) {
        echo "Book removed from reading list successfully.";
        header("Location: my-list.php");
    } else {
        echo "Error removing book from reading list: " . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove Book from Reading List</title>
    <link href="alternate-style.css" rel="stylesheet" >
    <style>
        #my-list-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .reading-list-table {
            width: 60%;
            border: 1px solid black;
            border-collapse: collapse;
        }

        .reading-list-table td {
            width: 50%; /* Set a fixed width for the td elements */
            padding: 8px;
            text-align: left;
        }
        .reading-list-table th {
            width: 50%; /* Set a fixed width for the th elements */
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>

<!-- First Navigation Bar -->
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

<div id="my-list-content" style="padding: 10px">
<h2>Add or Remove Book from Reading List</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="book_to_add">Select Book to Add:</label>
    <select id="book_to_add" name="book_to_add">
        <?php
        // Populate the dropdown list with books from the books table
        if (mysqli_num_rows($result_books) > 0) {
            while ($row = mysqli_fetch_assoc($result_books)) {
                echo "<option value='" . $row["book_id"] . "'>" . $row["title"] . "</option>";
            }
        } else {
            echo "<option>No books found</option>";
        }
        ?>
    </select>
    <input type="submit" name="add_book" value="Add Book">

    <br><br>

    <label for="book_to_remove">Select Book to Remove:</label>
    <select id="book_to_remove" name="book_to_remove">
        <?php
        // Populate the dropdown list with books from the reading_list table
        if (mysqli_num_rows($result_reading_list) > 0) {
            while ($row = mysqli_fetch_assoc($result_reading_list)) {
                echo "<option value='" . $row["book_id"] . "'>" . $row["title"] . "</option>";
            }
        } else {
            echo "<option>No books found in reading list</option>";
        }
        ?>
    </select>
    <input type="submit" name="remove_book" value="Remove Book">
</form>
<br><br>
<h2>Reading List</h2>
<?php
// Populate the table with books from the reading_list table
mysqli_data_seek($result_reading_list, 0);
if (mysqli_num_rows($result_reading_list) > 0) {
    echo "<table class='reading-list-table'>";
    echo "<tr><th>Book ID</th><th>Title</th></tr>";
    while ($row = mysqli_fetch_assoc($result_reading_list)) {
	    echo "<tr>
            <td>{$row['book_id']}</td>
            <td>{$row['title']}</td>
        </tr>";
    }
    echo "</table>";
} else {
    echo "<tr><td colspan='2'>No books found in reading list</td></tr>";
}
?>
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