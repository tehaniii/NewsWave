<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'newswave');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the username is set in session
if (!isset($_SESSION['username'])) {
    die("Session error: Username not set in session.");
}

$username = $_SESSION['username'];

// Fetch employee data from the database
$sql = "SELECT * FROM employee WHERE username = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Debugging: Check if user data is fetched correctly
if (!$user) {
    die("Error: No user data found for the username '$username'.");
}

// Close the statement
$stmt->close();

// Handle form submission for creating news
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if file upload for image exists and is successful
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
        // Get form data
        $title = $_POST['title'];
        $category = $_POST['category'];
        $content = $_POST['content'];
        $created_at = $_POST['created_at'];
        
        // File upload directory
        $image_dir = 'uploads/images/';

        // Check if directory exists, if not, create it
        if (!is_dir($image_dir)) {
            mkdir($image_dir, 0777, true);
        }

        // Initialize variables for file paths
        $image_path = '';

        // File paths
        $image_file = $image_dir . basename($_FILES["image"]["name"]);

        // Check if file is an actual image
        $check_image = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check_image === false) {
            die("File is not an image.");
        }

        // Move uploaded image to designated directory
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $image_file)) {
            die("Failed to move image file.");
        }

        $image_path = $image_file;

        // Insert news data into database
        $sql = "INSERT INTO news (title, category, content, created_at, author_first_name, author_last_name, author_contact, image_path) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error: " . $conn->error);
        }

        $stmt->bind_param("ssssssss", $title, $category, $content, $created_at, $user['firstName'], $user['lastName'], $user['contact'], $image_path);

        // Execute the statement
        if ($stmt->execute()) {
            echo "News submitted successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    } else {
        die("Image file upload failed or not found.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Upload</title>
    <link rel="stylesheet" href="styles/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 50%;
            margin: 0 auto;
            padding-top: 20px;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #ff5722;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #ff7043;
        }

        button[type="reset"] {
            background-color: #ccc;
            color: #333;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button[type="reset"]:hover {
            background-color: #bbb;
        }
    </style>
</head>
<body>
<header>
    <div class="container">
        <div class="logo">
            <img src="images/logo.png" alt="NewsWave Logo">
        </div>
        <nav>
            <ul>
                <li><a href="employee_news.php">Create</a></li>
                <li><a href="view_employee.php">View</a></li>
                <li><a href="logout.php">Logout</a></li>

            </ul>
        </nav>
    </div>
</header>

<div class="container">
    <h2>Upload News</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required><br><br>

        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <option value="politics">Politics</option>
            <option value="technology">Technology</option>
            <option value="entertainment">Entertainment</option>
            <option value="sports">Sports</option>
        </select><br><br>

        <label for="image">Select image:</label>
        <input type="file" name="image" id="image" accept="image/*" required><br><br>
        
        <label for="content">Content:</label>
        <textarea id="content" name="content" rows="5" cols="50" required></textarea><br><br>

        <input type="hidden" name="created_at" value="<?php echo date('Y-m-d H:i:s'); ?>">
        
        <label for="authorFirstName">First Name:</label>
        <input type="text" id="authorFirstName" name="authorFirstName" value="<?php echo htmlspecialchars($user['firstName']); ?>" readonly><br><br>
        
        <label for="authorLastName">Last Name:</label>
        <input type="text" id="authorLastName" name="authorLastName" value="<?php echo htmlspecialchars($user['lastName']); ?>" readonly><br><br>
        
        <label for="authorContact">Contact:</label>
        <input type="text" id="authorContact" name="authorContact" value="<?php echo htmlspecialchars($user['contact']); ?>" readonly><br><br>

        <input type="submit" value="Submit">
        <button type="reset">Cancel</button>
    </form>
</div>

</body>
</html>
