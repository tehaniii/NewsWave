<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'newswave');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete article request
if (isset($_GET['delete_id'])) {
    $article_id = intval($_GET['delete_id']);
    $sql_delete = "DELETE FROM news WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $article_id);
    $stmt_delete->execute();
    $stmt_delete->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Query all articles
$sql_articles = "SELECT * FROM news";
$result_articles = $conn->query($sql_articles);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage News Articles</title>
    <link rel="stylesheet" href="styles/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            margin: 0 auto;
            padding-top: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 90%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-left: auto;
            margin-right: auto;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #333;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        @media screen and (max-width: 600px) {
            table {
                width: 100%;
                border-collapse: collapse;
            }

            th, td {
                padding: 8px;
                text-align: left;
            }

            th {
                background-color: #333;
                color: #fff;
            }

            tr:nth-child(even) {
                background-color: #f2f2f2;
            }

            th, td {
                border-bottom: 1px solid #ddd;
            }

            tr {
                display: block;
                margin-bottom: 15px;
            }

            td {
                display: flex;
                align-items: center;
            }

            td:before {
                content: attr(data-label);
                font-weight: bold;
                flex: 0 0 50%;
            }
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
            <li><a href="admin.php">Admin Page</a></li>
            <li><a href="manage_articles.php">Manage Articles</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>
<div class="container">
    <h2>Manage News Articles</h2>
    <table>
        <tr>
            <th>Article ID</th>
            <th>Title</th>
            <th>Category</th>
            <th>Content</th>
            <th>Created At</th>
            <th>Author First Name</th>
            <th>Author Last Name</th>
            <th>Author Contact</th>
            <th>Image Path</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result_articles->num_rows > 0) {
            while ($row_article = $result_articles->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row_article['id'] . "</td>";
                echo "<td>" . $row_article['title'] . "</td>";
                echo "<td>" . $row_article['category'] . "</td>";
                echo "<td>" . substr($row_article['content'], 0, 50) . "...</td>";
                echo "<td>" . $row_article['created_at'] . "</td>";
                echo "<td>" . $row_article['author_first_name'] . "</td>";
                echo "<td>" . $row_article['author_last_name'] . "</td>";
                echo "<td>" . $row_article['author_contact'] . "</td>";
                echo "<td>" . $row_article['image_path'] . "</td>";
                echo "<td>
                        <a href='" . $_SERVER['PHP_SELF'] . "?delete_id=" . $row_article['id'] . "' onclick=\"return confirm('Are you sure you want to delete this article?');\">Delete</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='11'>No articles found</td></tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
<?php
$conn->close();
?>
