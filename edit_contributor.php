<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'newswave');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM contributer WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Form for editing contributor details
        ?>
        <form method="post" action="edit_contributor.php" style="width: 300px; margin: 0 auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px;">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <label style="display: block; margin-bottom: 10px;">First Name:</label>
            <input type="text" name="first_name" value="<?php echo $row['first_name']; ?>" style="width: 100%; padding: 8px; margin-bottom: 10px;"><br>
            <label style="display: block; margin-bottom: 10px;">Last Name:</label>
            <input type="text" name="last_name" value="<?php echo $row['last_name']; ?>" style="width: 100%; padding: 8px; margin-bottom: 10px;"><br>
            <label style="display: block; margin-bottom: 10px;">Email:</label>
            <input type="email" name="email" value="<?php echo $row['email']; ?>" style="width: 100%; padding: 8px; margin-bottom: 10px;"><br>
            <label style="display: block; margin-bottom: 10px;">Username:</label>
            <input type="text" name="username" value="<?php echo $row['username']; ?>" style="width: 100%; padding: 8px; margin-bottom: 10px;"><br>
            <label style="display: block; margin-bottom: 10px;">Password:</label>
            <input type="password" name="password" value="<?php echo $row['password']; ?>" style="width: 100%; padding: 8px; margin-bottom: 10px;"><br>
            <input type="submit" value="Update" style="width: 100%; padding: 10px; background-color: #4CAF50; color: white; border: none; border-radius: 5px;">
        </form>
        <?php
    } else {
        echo "Contributor not found.";
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "UPDATE contributer SET first_name = '$first_name', last_name = '$last_name', email = '$email', username = '$username', password = '$password' WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Error updating contributor: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
