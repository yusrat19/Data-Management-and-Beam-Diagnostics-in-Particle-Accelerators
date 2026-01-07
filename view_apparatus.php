<?php
include 'db_connect.php';

// Dropdowns for filters
$typeResult = $conn->query("SELECT DISTINCT Apparatus_Type FROM Apparatus ORDER BY Apparatus_Type ASC");
$statusResult = $conn->query("SELECT DISTINCT STATUS FROM Apparatus ORDER BY STATUS ASC");

// Check if form submitted
$filterType   = isset($_POST['apparatus_type']) ? $_POST['apparatus_type'] : '';
$filterStatus = isset($_POST['status']) ? $_POST['status'] : '';
$searchID     = isset($_POST['machine_id']) ? $_POST['machine_id'] : '';

// Build SQL query
$sql = "SELECT a.Machine_ID, a.STATUS, a.Building_Number, a.Block_Number, a.Apparatus_Type, 
               a.Max_Energy_Capacity, a.RAM,
               b.Beam_ID, b.CURRENT, b.Duration,
               m.Measurement_ID, m.VALUE, m.Unit, m.ERROR
        FROM Apparatus a
        LEFT JOIN Beam b ON a.Beam_ID = b.Beam_ID
        LEFT JOIN Measurement m ON a.Measurement_ID = m.Measurement_ID
        WHERE 1=1";

if(!empty($filterType)) $sql .= " AND a.Apparatus_Type = '".$conn->real_escape_string($filterType)."'";
if(!empty($filterStatus)) $sql .= " AND a.STATUS = '".$conn->real_escape_string($filterStatus)."'";
if(!empty($searchID)) $sql .= " AND a.Machine_ID = '".$conn->real_escape_string($searchID)."'";

$sql .= " ORDER BY a.Machine_ID";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Apparatus Information</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { padding: 8px 12px; border: 1px solid #aaa; }
        th { background-color: #f2f2f2; }
        form { margin-bottom: 20px; }
        input, select { padding: 5px; margin-right: 10px; }
    </style>
</head>
<body>

<h2>View Apparatus Information</h2>

<!-- Filter/Search Form -->
<form method="POST" action="">
    Apparatus Type: 
    <select name="apparatus_type">
        <option value="">-- All Types --</option>
        <?php while ($row = $typeResult->fetch_assoc()) {
            $selected = ($filterType == $row['Apparatus_Type']) ? "selected" : "";
            echo "<option value=\"{$row['Apparatus_Type']}\" $selected>{$row['Apparatus_Type']}</option>";
        } ?>
    </select>

    Status: 
    <select name="status">
        <option value="">-- All Statuses --</option>
        <?php while ($row = $statusResult->fetch_assoc()) {
            $selected = ($filterStatus == $row['STATUS']) ? "selected" : "";
            echo "<option value=\"{$row['STATUS']}\" $selected>{$row['STATUS']}</option>";
        } ?>
    </select>

    Machine ID: <input type="number" name="machine_id" value="<?php echo htmlspecialchars($searchID); ?>">

    <input type="submit" value="Search">
    <input type="submit" value="Reset" onclick="window.location='view_apparatus.php'; return false;">
</form>

<!-- Apparatus Table -->
<table>
    <tr>
        <th>Machine ID</th>
        <th>Status</th>
        <th>Building</th>
        <th>Block</th>
        <th>Type</th>
        <th>Max Energy</th>
        <th>RAM</th>
        <th>Beam ID</th>
        <th>Beam Current</th>
        <th>Beam Duration</th>
        <th>Measurement ID</th>
        <th>Value</th>
        <th>Unit</th>
        <th>Error</th>
    </tr>

<?php
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['Machine_ID']}</td>
            <td>{$row['STATUS']}</td>
            <td>{$row['Building_Number']}</td>
            <td>{$row['Block_Number']}</td>
            <td>{$row['Apparatus_Type']}</td>
            <td>{$row['Max_Energy_Capacity']}</td>
            <td>{$row['RAM']}</td>
            <td>{$row['Beam_ID']}</td>
            <td>{$row['CURRENT']}</td>
            <td>{$row['Duration']}</td>
            <td>{$row['Measurement_ID']}</td>
            <td>{$row['VALUE']}</td>
            <td>{$row['Unit']}</td>
            <td>{$row['ERROR']}</td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='14'>No apparatus data found</td></tr>";
}
$conn->close();
?>
</table>
</body>
</html>
