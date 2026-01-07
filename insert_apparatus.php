<?php
include 'db_connect.php';

$insert_msg = "";

// Handle form submission
if(isset($_POST['insert'])) {
    $machine_id = $_POST['machine_id'];
    $status     = $_POST['status'];
    $building   = $_POST['building'];
    $block      = $_POST['block'];
    $type       = $_POST['type'];
    $max_energy = $_POST['max_energy'];
    $ram        = $_POST['ram'];
    $beam_id    = $_POST['beam_id'] ?: NULL;
    $measurement_id = $_POST['measurement_id'] ?: NULL;

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO Apparatus 
        (Machine_ID, STATUS, Building_Number, Block_Number, Apparatus_Type, Max_Energy_Capacity, RAM, Beam_ID, Measurement_ID)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssdiis", $machine_id, $status, $building, $block, $type, $max_energy, $ram, $beam_id, $measurement_id);

    if($stmt->execute()) {
        $insert_msg = "New apparatus inserted successfully!";
    } else {
        $insert_msg = "Error inserting apparatus: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Insert New Apparatus</title>
    <style>
        input, select { padding: 5px; margin: 5px; width: 200px; }
        form { margin-top: 20px; }
    </style>
</head>
<body>

<h2>Insert New Apparatus</h2>
<?php if($insert_msg) echo "<p><b>$insert_msg</b></p>"; ?>

<form method="POST" action="">
    Machine ID: <input type="number" name="machine_id" required><br>
    Status: <input type="text" name="status" required><br>
    Building Number: <input type="number" name="building" required><br>
    Block Number: <input type="text" name="block" required><br>
    Type: <input type="text" name="type" required><br>
    Max Energy: <input type="number" step="0.01" name="max_energy" required><br>
    RAM: <input type="number" name="ram" required><br>
    Beam ID (optional): <input type="number" name="beam_id"><br>
    Measurement ID (optional): <input type="number" name="measurement_id"><br>
    <input type="submit" name="insert" value="Insert Apparatus">
</form>

<p><a href="view_apparatus.php">Back to Apparatus List</a></p>

</body>
</html>
