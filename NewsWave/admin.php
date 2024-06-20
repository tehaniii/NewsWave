<?php
// Start session
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'newswave');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle approve/reject requests
if (isset($_GET['action'], $_GET['id'])) {
    $id = intval($_GET['id']);
    if ($_GET['action'] == 'approve') {
        // Approve user - move from signup_requests to contributer
        $sql = "INSERT INTO contributer (first_name, last_name, email, username, password) 
                SELECT first_name, last_name, email, username, password FROM signup_requests WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        
        // Remove from signup_requests
        $sql = "DELETE FROM signup_requests WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    } elseif ($_GET['action'] == 'reject') {
        // Reject user - simply remove from signup_requests
        $sql = "DELETE FROM signup_requests WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle delete contributor requests
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $sql = "DELETE FROM contributer WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Query signup requests
$sql_signup = "SELECT * FROM signup_requests";
$result_signup = $conn->query($sql_signup);

// Query contributors
$sql_contributors = "SELECT * FROM contributer";
$result_contributors = $conn->query($sql_contributors);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
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
        <h2>Welcome Admin</h2>

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
    <h2>Pending Signup Requests</h2>
    <table>
        <tr>
            <th>Request ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Username</th>
            <th>Password</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result_signup->num_rows > 0) {
            while ($row_signup = $result_signup->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row_signup['id'] . "</td>";
                echo "<td>" . $row_signup['first_name'] . "</td>";
                echo "<td>" . $row_signup['last_name'] . "</td>";
                echo "<td>" . $row_signup['email'] . "</td>";
                echo "<td>" . $row_signup['username'] . "</td>";
                echo "<td>" . $row_signup['password'] . "</td>";
                echo "<td><a href='" . $_SERVER['PHP_SELF'] . "?action=approve&id=" . $row_signup['id'] . "'>Approve</a> | <a href='" . $_SERVER['PHP_SELF'] . "?action=reject&id=" . $row_signup['id'] . "'>Reject</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No pending signup requests</td></tr>";
        }
        ?>
    </table>

    <h2>Current Contributors</h2>
    <table>
        <tr>
            <th>Contributor ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Username</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result_contributors->num_rows > 0) {
            while ($row_contributor = $result_contributors->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row_contributor['id'] . "</td>";
                echo "<td>" . $row_contributor['first_name'] . "</td>";
                echo "<td>" . $row_contributor['last_name'] . "</td>";
                echo "<td>" . $row_contributor['email'] . "</td>";
                echo "<td>" . $row_contributor['username'] . "</td>";
                echo "<td><a href='" . $_SERVER['PHP_SELF'] . "?delete_id=" . $row_contributor['id'] . "'>Delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No contributors found</td></tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
<?php
$conn->close();
?>
