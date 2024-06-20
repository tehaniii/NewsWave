<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'newswave');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Query pending registration requests
$sql = "SELECT * FROM registration_requests";
$result = $conn->query($sql);
if (!$result) {
    echo "Error: " . $conn->error;
} else {
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
        <nav>
            <ul>
                <li><a href="admin.php">Admin Page</a></li>
                <li><a href="manage_articles.php">Manage Articles</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>
<h2>Pending Registration Requests</h2>
<table>
    <tr>
        <th>Employee ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Gender</th>
        <th>Email</th>
        <th>Address</th>
        <th>DOB</th>
        <th>Contact Number</th>
        <th>NIC</th>
        <th>Username</th>
        <th>Password</th>
        <th>Role</th>
        <th>Action</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['employeeID'] . "</td>";
            echo "<td>" . $row['firstName'] . "</td>";
            echo "<td>" . $row['lastName'] . "</td>";
            echo "<td>" . $row['gender'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['address'] . "</td>";
            echo "<td>" . $row['dob'] . "</td>";
            echo "<td>" . $row['contact'] . "</td>";
            echo "<td>" . $row['nic'] . "</td>";
            echo "<td>" . $row['username'] . "</td>";
            echo "<td>" . $row['password'] . "</td>";
            echo "<td>" . $row['role'] . "</td>";
            echo "<td><a href='approve.php?employeeID=" . $row['employeeID'] . "'>Approve</a> | <a href='reject.php?employeeID=" . $row['employeeID'] . "'>Reject</a></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='13'>No pending requests</td></tr>";
    }
    ?>
</table>

<h2>Pending Signup Requests</h2>
<table>
    <tr>
        <th>Contributor ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Username</th>
        <th>Password</th>
        <th>Action</th>
    </tr>
    <?php
    // Query pending signup requests
    $sql_signup = "SELECT * FROM signup_requests";
    $result_signup = $conn->query($sql_signup);
    if (!$result_signup) {
        echo "Error: " . $conn->error;
    } else {
        if ($result_signup->num_rows > 0) {
            while ($row_signup = $result_signup->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row_signup['id'] . "</td>";
                echo "<td>" . $row_signup['first_name'] . "</td>";
                echo "<td>" . $row_signup['last_name'] . "</td>";
                echo "<td>" . $row_signup['email'] . "</td>";
                echo "<td>" . $row_signup['username'] . "</td>";
                echo "<td>" . $row_signup['password'] . "</td>";
                echo "<td><a href='approve_signup.php?action=approve&id=" . $row_signup['id'] . "'>Approve</a> | <a href='approve_signup.php?action=reject&id=" . $row_signup['id'] . "'>Reject</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No pending signup requests</td></tr>";
        }
    }
    ?>
</table>

<h2>All Contributors</h2>
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
    // Query all contributors
    $sql_contributors = "SELECT * FROM contributer";
    $result_contributors = $conn->query($sql_contributors);
    if (!$result_contributors) {
        echo "Error: " . $conn->error;
    } else {
        if ($result_contributors->num_rows > 0) {
            while ($row_contributors = $result_contributors->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row_contributors['id'] . "</td>";
                echo "<td>" . $row_contributors['first_name'] . "</td>";
                echo "<td>" . $row_contributors['last_name'] . "</td>";
                echo "<td>" . $row_contributors['email'] . "</td>";
                echo "<td>" . $row_contributors['username'] . "</td>";
                echo "<td><a href='edit_contributor.php?id=" . $row_contributors['id'] . "'>Edit</a> | <a href='delete_contributor.php?id=" . $row_contributors['id'] . "'>Delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No contributors found</td></tr>";
        }
    }
    ?>
</table>

<h2>All Employees</h2>
<table>
    <tr>
        <th>Employee ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Gender</th>
        <th>Email</th>
        <th>Address</th>
        <th>DOB</th>
        <th>Contact</th>
        <th>NIC</th>
        <th>Username</th>
        <th>Password</th>
        <th>Role</th>
        <th>Action</th>
    </tr>
    <?php
    // Query all employees
    $sql_employees = "SELECT * FROM employee";
    $result_employees = $conn->query($sql_employees);
    if (!$result_employees) {
        echo "Error: " . $conn->error;
    } else {
        if ($result_employees->num_rows > 0) {
            while ($row_employees = $result_employees->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row_employees['employeeID'] . "</td>";
                echo "<td>" . $row_employees['firstName'] . "</td>";
                echo "<td>" . $row_employees['lastName'] . "</td>";
                echo "<td>" . $row_employees['gender'] . "</td>";
                echo "<td>" . $row_employees['email'] . "</td>";
                echo "<td>" . $row_employees['address'] . "</td>";
                echo "<td>" . $row_employees['dob'] . "</td>";
                echo "<td>" . $row_employees['contact'] . "</td>";
                echo "<td>" . $row_employees['nic'] . "</td>";
                echo "<td>" . $row_employees['username'] . "</td>";
                echo "<td>" . $row_employees['password'] . "</td>";
                echo "<td>" . $row_employees['role'] . "</td>";
                echo "<td><a href='edit_employee.php?employeeID=" . $row_employees['employeeID'] . "'>Edit</a> | <a href='delete_employee.php?employeeID=" . $row_employees['employeeID'] . "'>Delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='13'>No employees found</td></tr>";
        }
    }
    ?>
</table>
</body>
</html>
<?php
}
?>
