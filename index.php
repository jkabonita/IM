<?php
// session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 400px;
            margin: 20px auto;
        }

        form {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        .submit_btn {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }
    </style>
</head>

<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_info";

$conn = new mysqli($servername, $username, $password, $dbname);

// Commented-out session and account-related code

?>

<body>
    <!-- Student Information Form for Recording Inputs -->
    <h2>Student Information Form</h2>
    <form action="index.php" method="post">

        <label for="stud_id">Student ID:</label>
        <input type="number" name="stud_id" required>

        <label for="lastname">Last Name:</label>
        <input type="text" name="lastname" required>

        <label for="firstname">First Name:</label>
        <input type="text" name="firstname" required>

        <label for="middlename">Middle Name:</label>
        <input type="text" name="middlename">

        <label for="Address">Address:</label>
        <input type="text" name="Address">

        <label for="Email">Email:</label>
        <input type="text" name="Email">

        <label for="contact">Contact Number:</label>
        <input type="text" name="contact" pattern="\d+" title="Please enter a valid number" required>

        <label for="Course">Course:</label>
        <select name="Course" required>
            <option value="" disabled selected>None</option>
            <option>BSIT</option>
            <option>BSCS</option>
            <option>BSIS</option>
        </select>

        <label for="Year">Year:</label>
        <select name="Year" required>
            <option value="" disabled selected>None</option>
            <option>1</option>
            <option>2</option>
            <option>3</option>
            <option>4</option>
        </select>

        <label for="Section">Section:</label>
        <select name="Section" required>
            <option value="" disabled selected>None</option>
            <option>A</option>
            <option>B</option>
            <option>C</option>
            <option>D</option>
        </select>

        <input type="submit" name="submit" value="Submit" class="submit_btn">
        <?php
        // Functions for submitting Student Information
        if (isset($_POST['submit'])) {
            $studid = $_POST['stud_id'];
            $Lname = $_POST['lastname'];
            $Fname = $_POST['firstname'];
            $Mname = $_POST['middlename'];
            $Address = $_POST['Address'];
            $Email = $_POST['Email'];
            $contact = $_POST['contact'];
            $Course = $_POST['Course'];
            $Year = $_POST['Year'];
            $Section = $_POST['Section'];

            // Check for Existing Student ID
            $sql_check_duplicate = "SELECT student_id FROM student_info WHERE student_id = $studid";
            $result = $conn->query($sql_check_duplicate);

            if ($result->num_rows > 0) {
                // If student ID already exists, ask the user if they want to edit the existing record
                echo "<span class='error'>Student ID Already Exist. Do you Want to Edit Existing Record?</span>";
                // Store inputs that will be used to update existing data
                echo "<form method='post' >
                    <input type='hidden' name='stud_id' value='$studid'>  
                    <input type='hidden' name='lastname' value='$Lname'>  
                    <input type='hidden' name='firstname' value='$Fname'>
                    <input type='hidden' name='middlename' value='$Mname'>  
                    <input type='hidden' name='Address' value='$Address'>
                    <input type='hidden' name='Email' value='$Email'>
                    <input type='hidden' name='contact' value='$contact'>
                    <input type='hidden' name='Course' value='$Course'>
                    <input type='hidden' name='Year' value='$Year'>
                    <input type='hidden' name='Section' value='$Section'>
                    <input type='submit' name='yes' value='Edit'>
                    <input type='submit' name='no' value='Cancel'>
                </form>";
            } else {
                // Insert the user input into the database
                $sql = "INSERT INTO student_info (student_id, last_name, first_name, middle_name, address, email, contact_no, course, year, section)
                    VALUES ($studid, '$Lname', '$Fname', '$Mname', '$Address', '$Email', '$contact', '$Course',$Year, '$Section')";

                if ($conn->query($sql) === TRUE) {
                    // If successful, display success message
                    echo "<span class='success'>Inputs Recorded Successfully</span>";
                } else {
                    echo "<span class='error'>Error: " . $sql . "<br>" . $conn->error . "</span>";
                }
            }
        }

        // Functions for Editing Existing Student Information
        if (isset($_POST['yes'])) {
            $studid = $_POST['stud_id'];
            $Lname = $_POST['lastname'];
            $Fname = $_POST['firstname'];
            $Mname = $_POST['middlename'];
            $Address = $_POST['Address'];
            $Email = $_POST['Email'];
            $contact = $_POST['contact'];
            $Course = $_POST['Course'];
            $Year = $_POST['Year'];
            $Section = $_POST['Section'];

            // Updates the table
            $sql_update = "UPDATE student_info SET last_name = '$Lname', first_name = '$Fname', middle_name = '$Mname', address = '$Address', email = '$Email',
                contact_no = '$contact', course = '$Course', year = $Year, section = '$Section' WHERE student_id = $studid";

            if ($conn->query($sql_update) === TRUE) {
                echo "<span class='success'>Record Updated Successfully</span>";
            } else {
                echo "<span class='error'>Error updating record: " . $conn->error . "</span>";
            }
        } elseif (isset($_POST['no'])) {
            // User chose not to edit
            echo "<span class='error'>Record Update Cancelled</span>";
        }
        ?>
        <a href="database.php">View Database</a>
    </form>
</body>

</html>
