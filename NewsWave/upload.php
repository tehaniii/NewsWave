<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'newswave');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch contributor data from the database
$username = $_SESSION['username'];
$sql = "SELECT * FROM contributer WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Close the statement
$stmt->close();

// Handle form submission for creating news
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $title = $_POST['title'];
    $category = $_POST['category'];
    $content = $_POST['content'];
    $created_at = $_POST['created_at'];
    $image = $_FILES['image']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES['image']['tmp_name']);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES['image']['size'] > 5000000) { // 5MB limit
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    $allowed_extensions = array("jpg", "jpeg", "png", "gif");
    if (!in_array($imageFileType, $allowed_extensions)) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            echo "The file " . htmlspecialchars(basename($image)) . " has been uploaded.";

            // Prepare SQL statement to insert news into the database
            $sql = "INSERT INTO news (title, category, content, created_at, author_first_name, author_last_name, author_contact, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                die("Error: " . $conn->error);
            }

            $stmt->bind_param("ssssssss", $title, $category, $content, $created_at, $user['first_name'], $user['last_name'], $user['contact'], $target_file);

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
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>