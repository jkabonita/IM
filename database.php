<?php
// session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information Records</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 12px;
        }

        th {
            background-color: #f2f2f2;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            margin-right: 10px;
        }

        input, select {
            margin-bottom: 10px;
            padding: 8px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        a {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
        }

        span {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

<?php 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_info";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Delete Functions
if (isset($_POST['delete'])) {
    $delete_id = mysqli_real_escape_string($conn, $_POST['delete']);
    $delete_sql = "DELETE FROM student_info WHERE student_id = $delete_id";

    if ($conn->query($delete_sql) === TRUE) {
        header('Location: database.php?notif=rec_delS');
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Handle filter and sorting and assign default value
$filterCourse = isset($_POST['course']) ? $_POST['course'] : '';
$filterYear = isset($_POST['year']) ? $_POST['year'] : '';
$filterSection = isset($_POST['section']) ? $_POST['section'] : '';

$sortBy = isset($_POST['sort']) ? $_POST['sort'] : 'year';
$sortOrder = isset($_POST['order']) ? $_POST['order'] : 'ASC';
$search = isset($_POST['search']) ? $_POST['search'] : '';

// Construct SQL query based on filter and sorting options
$sql = "SELECT * FROM student_info";
$whereClauseAdded = false;

// Add a condition if filter is not Empty
// Search with Filters
if (!empty($filterCourse)) {
    if ($whereClauseAdded) {
        $sql .= " AND course = '$filterCourse'";
    } else {
        $sql .= " WHERE course = '$filterCourse'";
        $whereClauseAdded = true;
    }
}

if (!empty($filterYear)) {
    if ($whereClauseAdded) {
        $sql .= " AND year = $filterYear";
    } else {
        $sql .= " WHERE year = $filterYear";
        $whereClauseAdded = true;
    }
}

if (!empty($filterSection)) {
    if ($whereClauseAdded) {
        $sql .= " AND section = '$filterSection'";
    } else {
        $sql .= " WHERE section = '$filterSection'";
        $whereClauseAdded = true;
    }
}

if (!empty($search)) {
    if ($whereClauseAdded) {
        $sql .= " AND (student_id LIKE '%$search%' OR last_name LIKE '%$search%' OR first_name LIKE '%$search%' OR middle_name LIKE '%$search%')";
    } else {
        $sql .= " WHERE (student_id LIKE '%$search%' OR last_name LIKE '%$search%' OR first_name LIKE '%$search%' OR middle_name LIKE '%$search%')";
        $whereClauseAdded = true;
    }
}

// Add an order by
$sql .= " ORDER BY $sortBy $sortOrder, section ASC";
?>

<form method='POST' action=''>
    <div>
        <label for='course'>Filter by Course:</label>
        <select name='course'>
            <option value=''>All Courses</option>
            <option value='BSIT'<?= ($filterCourse == 'BSIT' ? ' selected' : '') ?>>BSIT</option>
            <option value='BSCS'<?= ($filterCourse == 'BSCS' ? ' selected' : '') ?>>BSCS</option>
            <option value='BSIS'<?= ($filterCourse == 'BSIS' ? ' selected' : '') ?>>BSIS</option>
        </select>

        <label for='year'>Year:</label>
        <select name='year'>
            <option value=''>All Year Level</option>
            <?php
            for ($i = 1; $i <= 4; $i++) {
                echo "<option value='$i'" . ($filterYear == $i ? ' selected' : '') . ">$i</option>";
            }
            ?>
        </select>

        <label for='section'>Section:</label>
        <select name='section'>
            <option value=''>All Section</option>
            <?php
            $sections = ['A', 'B', 'C', 'D']; // Add more sections as needed
            foreach ($sections as $section) {
                echo "<option value='$section'" . ($filterSection == $section ? ' selected' : '') . ">$section</option>";
            }
            ?>
        </select>

        <label for='sort'>Sort by:</label>
        <select name='sort'>
            <option value='year'<?= ($sortBy == 'year' ? ' selected' : '') ?>>Year</option>
            <option value='last_name'<?= ($sortBy == 'last_name' ? ' selected' : '') ?>>Last Name</option>
            <option value='first_name'<?= ($sortBy == 'first_name' ? ' selected' : '') ?>>First Name</option>
            <!-- Add more options as needed -->
        </select>

        <label for='order'>Order:</label>
        <select name='order'>
            <option value='ASC'<?= ($sortOrder == 'ASC' ? ' selected' : '') ?>>Ascending</option>
            <option value='DESC'<?= ($sortOrder == 'DESC' ? ' selected' : '') ?>>Descending</option>
        </select>
    </div>
    <div>
        <label for='name'>Search Student:</label>
        <input type='text' name='search' placeholder='ID, Last name, First name, Middle name' value='<?= $search ?>'>

        <input type='submit' value='Search'>
        <a href='database.php'>Refresh</a>
    </div>
</form>

<?php
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Filter/search bar
    if (isset($_GET["notif"]) && $_GET["notif"] == "rec_delS") {
        echo "<span style='color: red;'>Record Deleted</span>";
    }

    // Show Table
    echo "<table>
        <tr>
            <th>Student ID</th>
            <th>Last Name</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Address</th>
            <th>Email</th>
            <th>Contact No</th>
            <th>Course Y&S</th>
            <th>Action</th>
        </tr>";

    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row["student_id"]}</td>
            <td>{$row["last_name"]}</td>
            <td>{$row["first_name"]}</td>
            <td>{$row["middle_name"]}</td>
            <td>{$row["address"]}</td>
            <td>{$row["email"]}</td>
            <td>{$row["contact_no"]}</td>
            <td>{$row["course"]} - {$row["year"]}{$row["section"]}</td>
            <td>
                <form method='POST' action='edit.php'>
                    <input type='hidden' name='student_id' value='{$row["student_id"]}'>
                    <input type='submit' name='editRec' value='Edit'>
                </form>
                <form method='post' onsubmit='return confirm(\"Are you sure? Related Contribution Record Will Also be Deleted\")'>
                    <input type='hidden' name='delete' value='{$row["student_id"]}'>
                    <input type='submit' value='Delete'>
                </form>
            </td>
        </tr>";
    }

    echo "</table>";

} else {
    echo "0 results";
}

$conn->close();
?>
<a href="index.php">Return</a>
</body>
</html>
