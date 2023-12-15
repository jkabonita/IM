<?php
session_start();

$servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "student_info";

    $conn = new mysqli($servername, $username, $password, $dbname);

                            //Gets the Student_id to be Edited from the database.php
    if (isset($_POST['student_id'])) {
        $studid = $_POST['student_id'];

        // Handles the Update logic
        if (isset($_POST['submit'])) {
            $Lname = $_POST['lastname'];
            $Fname = $_POST['firstname'];
            $Mname = $_POST['middlename'];
            $Address = $_POST['Address'];
            $Email = $_POST['Email'];
            $contact = $_POST['contact'];
            $Course = $_POST['Course'];
            $Year = $_POST['Year'];
            $Section = $_POST['Section'];

            $sql_update = "UPDATE student_info SET last_name = '$Lname', first_name = '$Fname', middle_name = '$Mname', address = '$Address', email = '$Email',
            contact_no = $contact, course = '$Course', year = $Year, section = '$Section' WHERE student_id = '$studid'";

            if ($conn->query($sql_update) === TRUE) {
                header('Location: database.php');
            } else {
                echo "Error updating course: " . $conn->error;
            }
        }

                            // Fetch course details for editing
        $edit_sql = "SELECT * FROM student_info WHERE student_id = '$studid'";
        $edit_result = $conn->query($edit_sql);
        $edit_row = $edit_result->fetch_assoc();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Student Information Form</title>
            <style>
                @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500&display=swap');
                *{
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                    font-family: 'Roboto';
                }
                body {
                    font-family: Arial, sans-serif;
                    display: flex;
                    align-items: start;
                    justify-content: space-evenly;
                }

                .wrapper-1 {
                    height: 700px;
                    width: 550px;
                    background-color: #fff;
                    margin: 50px auto;
                    padding: 15px 30px;
                    border-radius: 5px;
                    box-shadow: 0 1px 10px rgba(0, 0, 0, .5)
                }
                .wrapper-2 {
                    height: 380px;
                    width: 500px;
                    background-color: #fff;
                    margin: 50px auto;
                    padding: 15px 30px;
                    border-radius: 5px;
                    box-shadow: 0 1px 10px rgba(0, 0, 0, .5)
                }

                label {
                    display: block;
                    margin-bottom: 8px;
                }

                input{
                    width: 100%;
                    padding: 10px 15px;
                    border: 2px solid rgba(0, 0, 255, .3);
                    font-family: 'Roboto';
                    font-size: 1rem;
                    outline: none;
                    transition: .5s all ease-in-out;
                    border-radius: 3px;
                }
                input:hover{
                    border: 2px solid rgba(0, 0, 255, .5)
                }

                .submit_btn{
                    background-color: #4CAF50;
                    color: white;
                    cursor: pointer;
                }
            </style>
        </head>
        <body>
    
                                                                <!--Student Information Form for Recroding Inputs -->
        <div class="wrapper-1">
            <h2>Edit Student Information <br>For Student ID: <?php echo "$studid";?></h2>
            <form method="post"  onsubmit="return confirm('Are you sure You Want to Edit Existing Record?')">

                <label for="stud_id">Student ID:</label>
                <input type="text" name="student_id" value="<?php echo $edit_row['student_id']; ?>" readonly>
        
                <label for="lastname">Last Name:</label>
                <input type="text" name="lastname" value="<?php echo $edit_row['last_name']; ?>">  

                <label for="firstname">First Name:</label>
                <input type="text" name="firstname" value="<?php echo $edit_row['first_name']; ?>">

                <label for="middlename">Middle Name:</label>
                <input type="text" name="middlename" value="<?php echo $edit_row['middle_name']; ?>">  

                <label for="Address">Address:</label>
                <input type="text" name="Address" value="<?php echo $edit_row['address']; ?>">

                <label for="Email">Email:</label>
                <input type="text" name="Email" value="<?php echo $edit_row['email']; ?>">

                <label for="contact">Contact Number:</label>
<input type="text" name="contact" value="<?php echo $edit_row['contact_no']; ?>">
                <label for="Course">Course:</label>
                <select name = "Course">
                    <option value="" disabled <?php echo ($edit_row['course'] == '') ? 'selected' : ''; ?>>None</option>
                    <option value="BSIT" <?php echo ($edit_row['course'] == 'BSIT') ? 'selected' : ''; ?>>BSIT</option>
                    <option value="BSCS" <?php echo ($edit_row['course'] == 'BSCS') ? 'selected' : ''; ?>>BSCS</option>
                    <option value="BSIS" <?php echo ($edit_row['course'] == 'BSIS') ? 'selected' : ''; ?>>BSIS</option>
                </select>

                <label for="Year">Year:</label>
                <select name = "Year" >
                    <option value="" disabled <?php echo ($edit_row['year'] == '') ? 'selected' : ''; ?>>None</option>
                    <option value="1" <?php echo ($edit_row['year'] == '1') ? 'selected' : ''; ?>>1</option>
                    <option value="2" <?php echo ($edit_row['year'] == '2') ? 'selected' : ''; ?>>2</option>
                    <option value="3" <?php echo ($edit_row['year'] == '3') ? 'selected' : ''; ?>>3</option>
                    <option value="4" <?php echo ($edit_row['year'] == '4') ? 'selected' : ''; ?>>4</option>
                </select>

                <label for="Section">Section:</label>
                <select name = "Section" >
                    <option value="" disabled <?php echo ($edit_row['section'] == '') ? 'selected' : ''; ?>>None</option>
                    <option value="A" <?php echo ($edit_row['section'] == 'A') ? 'selected' : ''; ?>>A</option>
                    <option value="B" <?php echo ($edit_row['section'] == 'B') ? 'selected' : ''; ?>>B</option>
                    <option value="C" <?php echo ($edit_row['section'] == 'C') ? 'selected' : ''; ?>>C</option>
                    <option value="D" <?php echo ($edit_row['section'] == 'D') ? 'selected' : ''; ?>>D</option>
                </select>  

                <input type="submit" name="submit" value="Submit" class = "submit_btn">
                <a href = "database.php">Return</a>
            </form>
        </div>
        </body>
        </html>
    <?php
    }      
    ?>
  
    
