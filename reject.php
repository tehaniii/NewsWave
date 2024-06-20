<?php
// Start session
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'newswave');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle approve/reject requests for employee registration
if (isset($_GET['action'], $_GET['id'])) {
    $id = intval($_GET['id']);
    if ($_GET['action'] == 'approve_employee') {
        // Approve user - move from registration_requests to employee table
        $sql = "INSERT INTO employee (firstName, lastName, gender, email, address, dob, contact, nic, username, password, role) 
                SELECT firstName, lastName, gender, email, address, dob, contact, nic, username, password, 'employee' FROM registration_requests WHERE employeeID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        
        // Remove from registration_requests
        $sql = "DELETE FROM registration_requests WHERE employeeID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    } elseif ($_GET['action'] == 'reject_employee') {
        // Reject user - simply remove from registration_requests
        $sql = "DELETE FROM registration_requests WHERE employeeID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle delete employee requests
if (isset($_GET['delete_employee_id'])) {
    $id = intval($_GET['delete_employee_id']);
    $sql = "DELETE FROM employee WHERE employeeID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Query registration requests for employees
$sql_registration_employee = "SELECT * FROM registration_requests WHERE role = 'employee'";
$result_registration_employee = $conn->query($sql_registration_employee);

// Query registration requests for contributors
$sql_registration_contributor = "SELECT * FROM registration_requests WHERE role = 'contributor'";
$result_registration_contributor = $conn->query($sql_registration_contributor);

// Query employees
$sql_employees = "SELECT * FROM employee";
$result_employees = $conn->query($sql_employees);

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
    <h2>Pending Signup Requests from Employees</h2>
    <table>
        <tr>
            <th>Request ID</th>
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
            <th>Action</th>
        </tr>
        <?php
        if ($result_registration_employee->num_rows > 0) {
            while ($row_registration_employee = $result_registration_employee->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row_registration_employee['employeeID'] . "</td>";
                echo "<td>" . $row_registration_employee['firstName'] . "</td>";
                echo "<td>" . $row_registration_employee['lastName'] . "</td>";
                echo "<td>" . $row_registration_employee['gender'] . "</td>";
                echo "<td>" . $row_registration_employee['email'] . "</td>";
                echo "<td>" . $row_registration_employee['address'] . "</td>";
                echo "<td>" . $row_registration_employee['dob'] . "</td>";
                echo "<td>" . $row_registration_employee['contact'] . "</td>";
                echo "<td>" . $row_registration_employee['nic'] . "</td>";
                echo "<td>" . $row_registration_employee['username'] . "</td>";
                echo "<td>" . $row_registration_employee['password'] . "</td>";
                echo "<td><a href='" . $_SERVER['PHP_SELF'] . "?action=approve_employee&id=" . $row_registration_employee['employeeID'] . "'>Approve</a> | <a href='" . $_SERVER['PHP_SELF'] . "?action=reject_employee&id=" . $row_registration_employee['employeeID'] . "'>Reject</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='12'>No pending signup requests from employees</td></tr>";
        }
        ?>
    </table>

    <h2>Pending Signup Requests from Contributors</h2>
    <table>
        <tr>
            <th>Request ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Username</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result_registration_contributor->num_rows > 0) {
            while ($row_registration_contributor = $result_registration_contributor->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row_registration_contributor['employeeID'] . "</td>";
                echo "<td>" . $row_registration_contributor['firstName'] . "</td>";
                echo "<td>" . $row_registration_contributor['lastName'] . "</td>";
                echo "<td>" . $row_registration_contributor['email'] . "</td>";
                echo "<td>" . $row_registration_contributor['username'] . "</td>";
                echo "<td><a href='" . $_SERVER['PHP_SELF'] . "?action=approve&id=" . $row_registration_contributor['employeeID'] . "'>Approve</a> | <a href='" . $_SERVER['PHP_SELF'] . "?action=reject&id=" . $row_registration_contributor['employeeID'] . "'>Reject</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No pending signup requests from contributors</td></tr>";
        }
        ?>
    </table>


</div>
</body>
</html>
<?php
$conn->close();
?>
