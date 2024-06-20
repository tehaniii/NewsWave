<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'newswave');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all news articles from the database
$sql = "SELECT * FROM news";
$result = $conn->query($sql);

// Array to store fetched news articles
$articles = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $articles[] = $row;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Categories</title>
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
            width: 80%;
            margin: 0 auto;
            padding-top: 20px;
        }
        .news-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .card {
            width: calc(50% - 10px);
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 5px;
        }
        .card img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .card-content {
            padding: 10px 0;
        }
        .filter-buttons {
            margin-top: 20px;
            margin-left: 150px;

            margin-bottom: 20px;
        }
        .filter-buttons button {
            background-color: #ff5722;
            color: #fff;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            border-radius: 3px;
            margin-right: 10px;
        }
        .filter-buttons button:hover {
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
                    <li><a href="categories.php">Explore News</a>
                      
                    </li>
                    <li><a href="register_home.php">Register</a></li>
                    <li><a href="login.php">Login</a></li>

                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
           <i> <h1>Welcome to NewsWave</h1>
            <p>Your ultimate source for news and updates</p>
       </i> </div>
        <div class="filter-buttons">
            <button onclick="filterNews('all')">All</button>
            <button onclick="filterNews('politics')">Politics</button>
            <button onclick="filterNews('technology')">Technology</button>
            <button onclick="filterNews('entertainment')">Entertainment</button>
            <button onclick="filterNews('sports')">Sports</button>
        </div>
    </div>


<div class="container">
    <div class="news-grid">
        <?php foreach ($articles as $article) : ?>
            <div class="card" data-category="<?php echo $article['category']; ?>">
                <h2><?php echo htmlspecialchars($article['title']); ?></h2>
                <p class="card-content"><?php echo htmlspecialchars($article['content']); ?></p>
                <p>Category: <?php echo htmlspecialchars($article['category']); ?></p>
                <p>Author: <?php echo htmlspecialchars($article['author_first_name'] . ' ' . $article['author_last_name']); ?></p>
                <p>Published on: <?php echo htmlspecialchars($article['created_at']); ?></p>
                <?php if (!empty($article['image_path'])) : ?>
                    <img src="<?php echo htmlspecialchars($article['image_path']); ?>" alt="News Image">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    function filterNews(category) {
        const cards = document.querySelectorAll('.card');
        cards.forEach(card => {
            const cardCategory = card.getAttribute('data-category');
            if (category === 'all' || cardCategory === category) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>

</body>
</html>
