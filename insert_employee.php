<?php
include 'db_connect.php';

$insert_msg = "";

// Handle form submission
if(isset($_POST['insert'])) {
    $employee_id   = $_POST['employee_id'];
    $name          = $_POST['name'];
    $employee_type = $_POST['employee_type'];
    $joining_date  = $_POST['joining_date'];

    // Insert employee
    $stmt = $conn->prepare("INSERT INTO Employee (Employee_ID, NAME, Employee_Type, Joining_Date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $employee_id, $name, $employee_type, $joining_date);

    if($stmt->execute()) {
        $insert_msg = "New employee inserted successfully!";
    } else {
        $insert_msg = "Error inserting employee: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Insert New Employee</title>
    <style>
        input, select { padding: 5px; margin: 5px; width: 250px; }
        form { margin-top: 20px; }
    </style>
</head>
<body>
<h2>Insert New Employee</h2>
<?php if($insert_msg) echo "<p><b>$insert_msg</b></p>"; ?>

<form method="POST" action="">
    Employee ID: <input type="number" name="employee_id" required><br>
    Name: <input type="text" name="name" required><br>
    Position / Type: <input type="text" name="employee_type" required><br>
    Joining Date: <input type="date" name="joining_date" required><br>
    <input type="submit" name="insert" value="Insert Employee">
</form>

<p><a href="view_employee.php">Back to Employee List</a></p>
</body>
</html>
