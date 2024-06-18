<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'newswave');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch contributor data from the database using session username
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $sql = "SELECT * FROM contributer WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    if ($user) {
        // Fetch uploaded news articles by the current contributor based on author_contact
        $author_contact = $user['contact'];
        
        $sql = "SELECT * FROM news WHERE author_contact = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $author_contact);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            
            // Store results in an array
            $uploads = [];
            while ($row = $result->fetch_assoc()) {
                $uploads[] = $row;
            }
        } else {
            echo "Error executing query: " . $stmt->error;
        }
        
        // Close the statement
        $stmt->close();
    } else {
        die("No contributor found with the given username.");
    }
} else {
    die("Session username not set.");
}

// Function to delete uploaded news article
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $sql = "DELETE FROM news WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        // Remove the deleted article from the $uploads array
        $uploads = array_filter($uploads, function($upload) use ($delete_id) {
            return $upload['id'] !== $delete_id;
        });
    } else {
        echo "Error deleting news article: " . $stmt->error;
    }
    $stmt->close();
}

// Function to update news article
if (isset($_POST['update_id'], $_POST['title'], $_POST['category'], $_POST['content'])) {
    $update_id = $_POST['update_id'];
    $title = $_POST['title'];
    $category = $_POST['category'];
    $content = $_POST['content'];

    $sql = "UPDATE news SET title = ?, category = ?, content = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $title, $category, $content, $update_id);
    if ($stmt->execute()) {
        echo "News article updated successfully.";
        // Update the $uploads array with the modified article
        foreach ($uploads as &$upload) {
            if ($upload['id'] == $update_id) {
                $upload['title'] = $title;
                $upload['category'] = $category;
                $upload['content'] = $content;
                break;
            }
        }
    } else {
        echo "Error updating news article: " . $stmt->error;
    }
    $stmt->close();
}

// Close the database connection
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
            margin-right: 10px;
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
            margin: 0;
        }

        button {
            background-color: #ff5722;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
            margin-right: 10px;
        }

        button:hover {
            background-color: #e64a19;
        }

        .edit-btn {
            background-color: #2196f3;
        }

        .edit-btn:hover {
            background-color: #0b7dda;
        }

        .edit-form {
            display: none;
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
           
                <li><a href="news_create.php">Create</a></li>
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
            <th>Actions</th>
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
                <button class="edit-btn" onclick="toggleEditForm(<?php echo $upload['id']; ?>)">Edit</button>
                <form method="post" onsubmit="return confirm('Are you sure you want to delete this news article?');">
                    <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($upload['id']); ?>">
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
        <tr class="edit-form" id="edit-form-<?php echo $upload['id']; ?>">
            <td colspan="6">
                <form method="post" action="view.php">
                    <input type="hidden" name="update_id" value="<?php echo htmlspecialchars($upload['id']); ?>">
                    <label for="edit-title-<?php echo $upload['id']; ?>">Title:</label><br>
                    <input type="text" id="edit-title-<?php echo $upload['id']; ?>" name="title" value="<?php echo htmlspecialchars($upload['title']); ?>"><br>
                    <label for="edit-category-<?php echo $upload['id']; ?>">Category:</label><br>
                    <input type="text" id="edit-category-<?php echo $upload['id']; ?>" name="category" value="<?php echo htmlspecialchars($upload['category']); ?>"><br>
                    <label for="edit-content-<?php echo $upload['id']; ?>">Content:</label><br>
                    <textarea id="edit-content-<?php echo $upload['id']; ?>" name="content"><?php echo htmlspecialchars($upload['content']); ?></textarea><br>
                    <button type="submit">Save Changes</button>
                    <button type="button" onclick="toggleEditForm(<?php echo $upload['id']; ?>)">Cancel</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else : ?>
    <p>No news articles uploaded yet.</p>
    <?php endif; ?>
</div>

<script>
    function toggleEditForm(id) {
        var editForm = document.getElementById('edit-form-' + id);
        if (editForm.style.display === 'none' || editForm.style.display === '') {
            editForm.style.display = 'table-row';
        } else {
            editForm.style.display = 'none';
        }
    }
</script>

</body>
</html>
