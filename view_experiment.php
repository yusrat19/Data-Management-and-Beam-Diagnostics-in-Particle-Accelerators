<?php
include 'db_connect.php';

// ---------- Get filter values ----------
$filter_name       = isset($_POST['filter_name']) ? $_POST['filter_name'] : '';
$filter_objective  = isset($_POST['filter_objective']) ? $_POST['filter_objective'] : '';
$filter_start_date = isset($_POST['filter_start_date']) ? $_POST['filter_start_date'] : '';
$filter_end_date   = isset($_POST['filter_end_date']) ? $_POST['filter_end_date'] : '';
$search_id         = isset($_POST['search_id']) ? $_POST['search_id'] : '';

// ---------- Build SQL query ----------
$sql = "SELECT 
            e.Experiment_ID,
            e.NAME AS Experiment_Name,
            e.Objective,
            e.Start_Date,
            e.End_Date,
            GROUP_CONCAT(
                DISTINCT CONCAT(
                    'ID:', m.Measurement_ID, 
                    ' | Value:', m.VALUE, 
                    ' ', m.Unit, 
                    ' | Error:', m.ERROR,
                    ' | Apparatus:', IFNULL(a.Apparatus_Type,'--'),
                    ' | Beam Current:', IFNULL(b.CURRENT,'--'),
                    ' | Particles:', IFNULL(p.NAME,'--')
                )
                SEPARATOR '<br>'
            ) AS Measurements
        FROM Experiment e
        LEFT JOIN Apparatus a ON e.Experiment_ID = a.Experiment_ID
        LEFT JOIN Measurement m ON a.Measurement_ID = m.Measurement_ID
        LEFT JOIN Beam b ON a.Beam_ID = b.Beam_ID
        LEFT JOIN Composed_Of c ON b.Beam_ID = c.Beam_ID
        LEFT JOIN Particle p ON c.Particle_Name = p.NAME
        WHERE 1=1";

if(!empty($search_id)) {
    $sql .= " AND e.Experiment_ID = '".$conn->real_escape_string($search_id)."'";
}
if(!empty($filter_name)) {
    $sql .= " AND e.NAME LIKE '%".$conn->real_escape_string($filter_name)."%' ";
}
if(!empty($filter_objective)) {
    $sql .= " AND e.Objective LIKE '%".$conn->real_escape_string($filter_objective)."%' ";
}
if(!empty($filter_start_date)) {
    $sql .= " AND e.Start_Date >= '".$conn->real_escape_string($filter_start_date)."'";
}
if(!empty($filter_end_date)) {
    $sql .= " AND e.End_Date <= '".$conn->real_escape_string($filter_end_date)."'";
}

$sql .= " GROUP BY e.Experiment_ID ORDER BY e.Experiment_ID";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Experiments</title>
<style>
    body { font-family: Arial, sans-serif; }
    table { border-collapse: collapse; width: 100%; margin-top: 20px; }
    th, td { padding: 8px 12px; border: 1px solid #aaa; text-align: left; vertical-align: top; }
    th { background-color: #f2f2f2; }
    input { padding: 5px; margin: 5px; }
    form { margin-bottom: 20px; }
</style>
</head>
<body>

<h2>View Experiments</h2>

<!-- Filter Form -->
<form method="POST" action="">
    Experiment ID: <input type="number" name="search_id" value="<?php echo htmlspecialchars($search_id); ?>">
    Name contains: <input type="text" name="filter_name" value="<?php echo htmlspecialchars($filter_name); ?>">
    Objective contains: <input type="text" name="filter_objective" value="<?php echo htmlspecialchars($filter_objective); ?>">
    Start Date from: <input type="date" name="filter_start_date" value="<?php echo htmlspecialchars($filter_start_date); ?>">
    End Date to: <input type="date" name="filter_end_date" value="<?php echo htmlspecialchars($filter_end_date); ?>">
    <input type="submit" value="Filter">
    <input type="submit" value="Reset" onclick="window.location='view_experiment.php'; return false;">
</form>

<!-- Experiments Table -->
<table>
    <tr>
        <th>Experiment ID</th>
        <th>Name</th>
        <th>Objective</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Measurements / Apparatus / Particles</th>
    </tr>

<?php
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $measurements = !empty($row['Measurements']) ? $row['Measurements'] : '--';
        echo "<tr>
            <td>{$row['Experiment_ID']}</td>
            <td>{$row['Experiment_Name']}</td>
            <td>{$row['Objective']}</td>
            <td>{$row['Start_Date']}</td>
            <td>{$row['End_Date']}</td>
            <td>{$measurements}</td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='6'>No experiments found</td></tr>";
}
$conn->close();
?>
</table>
</body>
</html>
