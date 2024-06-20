<?php
session_start();

// Redirect to login page if user is not logged in or is not a contributer
if (!isset($_SESSION['username']) || $_SESSION['user_type'] != 'contributer') {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'newswave');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];

// Fetch contributer data from the database
$sql = "SELECT id, first_name, last_name, email, address, nic, dob, contact, username, password FROM contributer WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$contributorId = $user['id'];
$oldContact = $user['contact'];  // Store old contact number

// Handle form submission for updating contributer data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $nic = $_POST['nic'];
    $dob = $_POST['dob'];

    // Update contributer data in the database
    $sql = "UPDATE contributer SET first_name = ?, last_name = ?, email = ?, address = ?, password = ?, contact = ?, nic = ?, dob = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $firstName, $lastName, $email, $address, $password, $contact, $nic, $dob, $username);

    // Execute the update query
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            // Update the user data for immediate display
            $user['first_name'] = $firstName;
            $user['last_name'] = $lastName;
            $user['email'] = $email;
            $user['address'] = $address;
            $user['contact'] = $contact;
            $user['nic'] = $nic;
            $user['dob'] = $dob;
            $user['password'] = $password;

            // Update news table with new contributor details
            $updateNewsSql = "UPDATE news SET author_first_name = ?, author_last_name = ?, author_contact = ? WHERE author_contact = ?";
            $updateStmt = $conn->prepare($updateNewsSql);
            $updateStmt->bind_param("ssss", $firstName, $lastName, $contact, $oldContact); // Use old contact value to identify records to update
            if ($updateStmt->execute()) {
                echo "Profile and news articles updated successfully.";
            } else {
                echo "Error updating news articles: " . $updateStmt->error;
            }
            $updateStmt->close();
        } else {
            echo "No rows affected. Make sure you are actually updating the profile.";
        }
    } else {
        echo "Error executing update query: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Uploaded News</title>
    <link rel="stylesheet" href="styles/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', sans-serif;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        td a {
            color: #ff5722;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
        }

        td a:hover {
            text-decoration: underline;
        }

        td img {
            max-width: 100px;
            height: auto;
        }

        form {
            display: inline;
        }

        button {
            background-color: #ff5722;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
        }

        button:hover {
            background-color: #e64a19;
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
                <li><a href="home.html">Home</a></li>
                <li><a href="#">Categories</a>
                    <ul>
                        <li><a href="politics.php">Politics</a></li>
                        <li><a href="tech.php">Technology</a></li>
                        <li><a href="entertainment.php">Entertainment</a></li>
                        <li><a href="sports.php">Sports</a></li>
                    </ul>
                </li>
                <li><a href="view.php">View</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>

<div class="container">
    <h2>Your Uploaded News Articles</h2>
    <?php if (!empty($uploads)) : ?>
    <table>
        <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Content</th>
            <th>Image</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
        <?php foreach ($uploads as $upload) : ?>
        <tr>
            <td><?php echo htmlspecialchars($upload['title']); ?></td>
            <td><?php echo htmlspecialchars($upload['category']); ?></td>
            <td><?php echo htmlspecialchars($upload['content']); ?></td>
            <td>
                <?php if (!empty($upload['image_path'])) : ?>
                    <img src="<?php echo htmlspecialchars($upload['image_path']); ?>" alt="News Image">
                <?php else : ?>
                    No image
                <?php endif; ?>
            </td>
            <td><?php echo htmlspecialchars($upload['created_at']); ?></td>
            <td>
                <form method="post" onsubmit="return confirm('Are you sure you want to delete this news article?');">
                    <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($upload['id']); ?>">
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else : ?>
    <p>No news articles uploaded yet.</p>
    <?php endif; ?>
</div>

</body>
</html>
