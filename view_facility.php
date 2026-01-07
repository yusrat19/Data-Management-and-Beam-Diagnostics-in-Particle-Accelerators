<?php
include 'db_connect.php';

// Get filters from POST
$filter_machine = isset($_POST['filter_machine']) ? $_POST['filter_machine'] : '';
$filter_type    = isset($_POST['filter_type']) ? $_POST['filter_type'] : '';
$filter_status  = isset($_POST['filter_status']) ? $_POST['filter_status'] : '';
$filter_energy  = isset($_POST['filter_energy']) ? $_POST['filter_energy'] : '';
$filter_ram     = isset($_POST['filter_ram']) ? $_POST['filter_ram'] : '';

// Build SQL query dynamically
$sql = "SELECT Machine_ID, Apparatus_Type, STATUS, Max_Energy_Capacity, RAM FROM Apparatus WHERE 1=1";

if(!empty($filter_machine)) $sql .= " AND Machine_ID = '".$conn->real_escape_string($filter_machine)."'";
if(!empty($filter_type)) $sql .= " AND Apparatus_Type LIKE '%".$conn->real_escape_string($filter_type)."%' ";
if(!empty($filter_status)) $sql .= " AND STATUS LIKE '%".$conn->real_escape_string($filter_status)."%' ";
if(!empty($filter_energy)) $sql .= " AND Max_Energy_Capacity >= '".$conn->real_escape_string($filter_energy)."' ";
if(!empty($filter_ram)) $sql .= " AND RAM >= '".$conn->real_escape_string($filter_ram)."' ";

$sql .= " ORDER BY Machine_ID";

$result = $conn->query($sql);
if(!$result) die("Query failed: " . $conn->error);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Facilities</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #aaa; padding: 8px; }
        th { background-color: #f2f2f2; }
        input, select { padding: 5px; margin: 5px; width: 150px; }
        form { margin-bottom: 20px; }
    </style>
</head>
<body>

<h2>View Facilities</h2>

<!-- Filter Form -->
<form method="POST" action="">
    Machine ID: <input type="number" name="filter_machine" value="<?php echo htmlspecialchars($filter_machine); ?>">
    Type: 
    <select name="filter_type">
        <option value="">All</option>
        <option value="Laboratory" <?php if($filter_type=="Laboratory") echo "selected"; ?>>Laboratory</option>
        <option value="Beamline" <?php if($filter_type=="Beamline") echo "selected"; ?>>Beamline</option>
        <option value="Detector" <?php if($filter_type=="Detector") echo "selected"; ?>>Detector</option>
    </select>
    Status: <input type="text" name="filter_status" value="<?php echo htmlspecialchars($filter_status); ?>">
    Min Energy: <input type="number" step="0.01" name="filter_energy" value="<?php echo htmlspecialchars($filter_energy); ?>">
    Min RAM: <input type="number" name="filter_ram" value="<?php echo htmlspecialchars($filter_ram); ?>">
    <input type="submit" value="Filter">
    <input type="submit" value="Reset" onclick="window.location='view_facility_custom.php'; return false;">
</form>

<!-- Facilities Table -->
<table>
    <tr>
        <th>Machine ID</th>
        <th>Type</th>
        <th>Status</th>
        <th>Max Energy Capacity</th>
        <th>RAM (GB)</th>
    </tr>

<?php
if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['Machine_ID']}</td>
            <td>{$row['Apparatus_Type']}</td>
            <td>{$row['STATUS']}</td>
            <td>{$row['Max_Energy_Capacity']}</td>
            <td>{$row['RAM']}</td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='5'>No facilities found</td></tr>";
}
$conn->close();
?>
</table>

<p><a href="insert_facility.php">Add New Facility</a></p>

</body>
</html>
