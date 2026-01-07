<?php
include 'db_connect.php';

$insert_msg = "";

// Handle form submission
if(isset($_POST['insert'])) {
    $experiment_id = $_POST['experiment_id'];
    $name          = $_POST['name'];
    $objective     = $_POST['objective'];
    $start_date    = $_POST['start_date'];
    $end_date      = $_POST['end_date'];

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO Experiment 
        (Experiment_ID, NAME, Objective, Start_Date, End_Date)
        VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $experiment_id, $name, $objective, $start_date, $end_date);

    if($stmt->execute()) {
        $insert_msg = "New experiment inserted successfully!";
    } else {
        $insert_msg = "Error inserting experiment: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Insert New Experiment</title>
    <style>
        input, textarea { padding: 5px; margin: 5px; width: 300px; }
        textarea { height: 80px; }
        form { margin-top: 20px; }
    </style>
</head>
<body>

<h2>Insert New Experiment</h2>
<?php if($insert_msg) echo "<p><b>$insert_msg</b></p>"; ?>

<form method="POST" action="">
    Experiment ID: <input type="number" name="experiment_id" required><br>
    Name: <input type="text" name="name" required><br>
    Objective: <textarea name="objective" required></textarea><br>
    Start Date: <input type="date" name="start_date" required><br>
    End Date: <input type="date" name="end_date" required><br>
    <input type="submit" name="insert" value="Insert Experiment">
</form>

<p><a href="view_experiment.php">Back to Experiment List</a></p>

</body>
</html>
