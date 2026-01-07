<?php
include 'db_connect.php';

$insert_msg = "";

// Handle form submission
if(isset($_POST['insert'])) {
    $machine_id = $_POST['machine_id'];
    $status = $_POST['status'];
    $type = $_POST['apparatus_type'];
    $max_energy = $_POST['max_energy'];
    $ram = $_POST['ram'];

    $stmt = $conn->prepare("INSERT INTO Apparatus (Machine_ID, STATUS, Apparatus_Type, Max_Energy_Capacity, RAM) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issdi", $machine_id, $status, $type, $max_energy, $ram);

    if($stmt->execute()) {
        $insert_msg = "Facility added successfully!";
    } else {
        $insert_msg = "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Facility</title>
    <style>
        input, select { padding: 5px; margin: 5px; width: 250px; }
        form { margin-top: 20px; }
    </style>
</head>
<body>

<h2>Add New Facility</h2>
<?php if($insert_msg) echo "<p><b>$insert_msg</b></p>"; ?>

<form method="POST" action="">
    Machine ID: <input type="number" name="machine_id" required><br>
    Status: <input type="text" name="status" required><br>
    Apparatus Type: 
    <select name="apparatus_type" required>
        <option value="">Select Type</option>
        <option value="Laboratory">Laboratory</option>
        <option value="Beamline">Beamline</option>
        <option value="Detector">Detector</option>
        <option value="Computer">Computer</option>
        <option value="Spectrometer">Spectrometer</option>
    </select><br>
    Max Energy Capacity: <input type="number" step="0.01" name="max_energy" required><br>
    RAM (GB): <input type="number" name="ram"><br>
    <input type="submit" name="insert" value="Add Facility">
</form>

<p><a href="view_facility.php">View Facilities</a></p>

</body>
</html>
