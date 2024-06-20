<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'newswave');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['employeeID'])) {
    $employeeID = $_GET['employeeID'];
    $sql = "SELECT * FROM employee WHERE employeeID = $employeeID";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Form for editing employee details
        ?>
        <form method="post" action="edit_employee.php" style="width: 300px; margin: 0 auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px;">
            <input type="hidden" name="employeeID" value="<?php echo $row['employeeID']; ?>">
            <label style="display: block; margin-bottom: 10px;">First Name:</label>
            <input type="text" name="firstName" value="<?php echo $row['firstName']; ?>" style="width: 100%; padding: 8px; margin-bottom: 10px;"><br>
            <label style="display: block; margin-bottom: 10px;">Last Name:</label>
            <input type="text" name="lastName" value="<?php echo $row['lastName']; ?>" style="width: 100%; padding: 8px; margin-bottom: 10px;"><br>
            <label style="display: block; margin-bottom: 10px;">Gender:</label>
            <input type="text" name="gender" value="<?php echo $row['gender']; ?>" style="width: 100%; padding: 8px; margin-bottom: 10px;"><br>
            <label style="display: block; margin-bottom: 10px;">Email:</label>
            <input type="email" name="email" value="<?php echo $row['email']; ?>" style="width: 100%; padding: 8px; margin-bottom: 10px;"><br>
            <label style="display: block; margin-bottom: 10px;">Address:</label>
            <input type="text" name="address" value="<?php echo $row['address']; ?>" style="width: 100%; padding: 8px; margin-bottom: 10px;"><br>
            <label style="display: block; margin-bottom: 10px;">DOB:</label>
            <input type="date" name="dob" value="<?php echo $row['dob']; ?>" style="width: 100%; padding: 8px; margin-bottom: 10px;"><br>
            <label style="display: block; margin-bottom: 10px;">NIC:</label>
            <input type="text" name="nic" value="<?php echo $row['nic']; ?>" style="width: 100%; padding: 8px; margin-bottom: 10px;"><br>
            <label style="display: block; margin-bottom: 10px;">Username:</label>
            <input type="text" name="username" value="<?php echo $row['username']; ?>" style="width: 100%; padding: 8px; margin-bottom: 10px;"><br>
            <label style="display: block; margin-bottom: 10px;">Password:</label>
            <input type="password" name="password" value="<?php echo $row['password']; ?>" style="width: 100%; padding: 8px; margin-bottom: 10px;"><br>
            <label style="display: block; margin-bottom: 10px;">Role:</label>
            <input type="text" name="role" value="<?php echo $row['role']; ?>" style="width: 100%; padding: 8px; margin-bottom: 10px;"><br>
            <input type="submit" value="Update" style="width: 100%; padding: 10px; background-color: #4CAF50; color: white; border: none; border-radius: 5px;">
        </form>
        <?php
    } else {
        echo "Employee not found.";
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['employeeID'])) {
    $employeeID = $_POST['employeeID'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];
    $nic = $_POST['nic'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $sql = "UPDATE employee SET firstName = '$firstName', lastName = '$lastName', gender = '$gender', email = '$email', address = '$address', dob = '$dob', nic = '$nic', username = '$username', password = '$password', role = '$role' WHERE employeeID = $employeeID";
    if ($conn->query($sql) === TRUE) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Error updating employee: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
