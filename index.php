<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Reading Table</title>
    <link href="alternate-style.css" rel="stylesheet" >
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

<!-- Main Content -->
<div style="padding: 20px;">
    <h1>Welcome to The Reading Table</h1>
    <p>Use the search bar below to discover your next favorite book. Simply type in keywords such as the title, author, genre, or any other relevant information, and press the "Search" button. For more refined results, you can also utilize the optional filter and sort functionalities. Filter your search by selecting criteria such as title, author, year, or genre, and sort the results by various attributes like title, author, or publication year. Our comprehensive database will provide you with a curated selection of books that match your criteria. Happy exploring and happy reading!</p>
</div>
<div class="container">
    <!-- Left Div -->
    <div class="left-div">
        <div class="form-container">
            <form id="filter-form" method="post" action="searchresult.php">
                <input type="text" class="search-input" name="searchInput" placeholder="Enter Title / Writer / ISBN / Genre / Year">
                <button type="submit" class="search-button">Search</button>
                <fieldset>
                    <legend>Filter By</legend>
                    <label><input type="radio" name="filter" value="none"> None</label>
                    <label><input type="radio" name="filter" value="title"> Title</label>
                    <label><input type="radio" name="filter" value="isbn"> ISBN</label>
                    <label><input type="radio" name="filter" value="author"> Author</label>
                    <label><input type="radio" name="filter" value="year_published"> Year</label>
                    <label><input type="radio" name="filter" value="genre"> Genre</label>
                </fieldset>
                <fieldset>
                    <legend>Sort By</legend>
                    <label><input type="radio" name="sort" value="none"> None</label>
                    <label><input type="radio" name="sort" value="title"> Title (Ascending)</label>
                    <label><input type="radio" name="sort" value="title DESC"> Title (Descending)</label>
                    <label><input type="radio" name="sort" value="author"> Author (Ascending)</label>
                    <label><input type="radio" name="sort" value="author DESC"> Author (Descending)</label>
                    <label><input type="radio" name="sort" value="year_published"> Year (Ascending)</label>
                    <label><input type="radio" name="sort" value="year_published DESC"> Year (Descending)</label>
                </fieldset>
            </form>
        </div>
    </div>

    <!-- Right Div -->
    <div class="right-div">
        <div class="form-container">
            <form class="search-form" method="post" action="searchresult.php">
                <!--<input type="text" class="search-input" name="searchInput" placeholder="Enter Title / Writer / ISBN / Genre / Year">
                <button type="submit" class="search-button">Search</button>-->
            </form>
        </div>
        <div class="form-container summer-reading" style="margin:auto">
            <h2>Books To Read This Summer</h2>
        </div>
        <div class="form-container slideshow-container">
            <div class="mySlides fade">
                <img src="assets/16-divergent.jpg" width="300px" height="380px">
            </div>
        
            <div class="mySlides fade">
                <img src="assets/17-hobbit.jpg" width="300px" height="380px">
            </div>
        
            <div class="mySlides fade">
                <img src="assets/19-narnia.jpg" width="300px" height="380px">
            </div>
        
            <div class="mySlides fade">
                <img src="assets/18-percy-jackson.jpg" width="300px" height="380px">
            </div>
        
            <div class="mySlides fade">
                <img src="assets/20-php-and-mysql.jpg" width="300px" height="380px">
            </div>
        
            <!-- Add more images as needed -->
        </div>
    </div>
</div>
<div style="padding: 20px;">
    <div id="about">
        <h2>About Us</h2>
        <p>The Reading Table is your ultimate destination for all things book-related! We're not just another book website—we're a vibrant community of readers dedicated to enhancing your reading experience. Here's what we offer:</p>
        <ul>
            <li><strong>Digital Library:</strong> Dive into our extensive digital library and borrow books at your convenience. Whether you're looking for the latest bestseller, a timeless classic, or a hidden gem, our library has something for everyone.</li>
            <li><strong>Personalized Book Lists:</strong> Keep track of your reading journey with personalized book lists. Create lists of books you want to read, books you're currently reading, and books you've already enjoyed. With personalized recommendations based on your interests, finding your next favorite read has never been easier.</li>
            <li><strong>Reading Progress:</strong> Track your reading progress with our intuitive reading and done reading features. Mark books as "currently reading" to keep track of your progress, and when you're finished, move them to your "done reading" list. Celebrate your reading milestones and share your achievements with fellow book lovers.</li>
            <li><strong>Vast Catalog:</strong> Explore our vast catalog of books spanning across genres, authors, and themes. From fiction to non-fiction, mystery to romance, we offer a diverse selection of titles to suit every taste and interest.</li>
        </ul>
        <p>Join us at The Reading Table and embark on a journey of discovery, imagination, and endless possibilities. Happy reading!</p>
    </div>
    <div id="contact">
        <h2>Contact Us</h2>
        <p>Have questions, suggestions, or just want to say hello? We'd love to hear from you! Feel free to send us an email directly at <a href="mailto:email@example.com">email@example.com</a>. Our team is dedicated to providing you with excellent service and assistance, and we're always here to help with any inquiries you may have. Let's connect and share our love for books together!</p>
    </div>
</div>
<script src="script.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var loginBtn = document.getElementById('loginBtn');
        var logoutBtn = document.getElementById('logoutBtn');

        // Add event listener for login button click
        /*loginBtn.addEventListener('click', function() {
            // Redirect to login page
            window.location.href = 'login.php';
        });*/

        // Add event listener for logout button click
        /*logoutBtn.addEventListener('click', function() {
            // Perform logout operation, e.g., redirect to logout script
            window.location.href = 'logout.php';
        });*/

        // Check if user is logged in and toggle button visibility
        /*?php
        if (isset($_SESSION['authenticated']) && $_SESSION['authenticated']) {
            echo 'loginBtn.style.display = "none";';
            echo 'logoutBtn.style.display = "block";';
        } else {
            echo 'loginBtn.style.display = "block";';
            echo 'logoutBtn.style.display = "none";';
        }
        ?>*/
    });
</script>
</body>
</html>
