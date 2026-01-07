<?php
include 'db_connect.php';

$filter_experiment = isset($_POST['filter_experiment']) ? $_POST['filter_experiment'] : '';

$sql = "
SELECT 
    m.Measurement_ID,
    m.VALUE,
    m.Unit,
    m.ERROR,
    a.Apparatus_Type,
    e.NAME AS Experiment_Name,
    e.Objective,
    GROUP_CONCAT(DISTINCT p.NAME SEPARATOR ', ') AS Particles,
    b.CURRENT AS Beam_Current
FROM Measurement m
LEFT JOIN Apparatus a ON a.Measurement_ID = m.Measurement_ID
INNER JOIN Experiment e ON a.Experiment_ID = e.Experiment_ID
LEFT JOIN Beam b ON a.Beam_ID = b.Beam_ID
LEFT JOIN Composed_Of c ON c.Beam_ID = b.Beam_ID
LEFT JOIN Particle p ON c.Particle_Name = p.NAME
WHERE 1=1
";

// Apply filter
if (!empty($filter_experiment)) {
    $safe_filter = $conn->real_escape_string($filter_experiment);
    $sql .= " AND e.NAME LIKE '%$safe_filter%' ";
}

$sql .= "
GROUP BY m.Measurement_ID
ORDER BY m.Measurement_ID
";

$result = $conn->query($sql);
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Experimental Results</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { border-collapse: collapse; width: 100%; margin-top: 15px; }
        th, td { border: 1px solid #aaa; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        input { padding: 5px; margin: 5px; width: 250px; }
        form { margin-bottom: 15px; }
        a { text-decoration: none; color: blue; }
    </style>
</head>
<body>

<h2>Experimental Results</h2>

<!-- Filter by experiment -->
<form method="POST">
    <label>Experiment Name:</label>
    <input type="text" name="filter_experiment"
           value="<?php echo htmlspecialchars($filter_experiment); ?>">
    <input type="submit" value="Filter">
    <input type="button" value="Reset" onclick="window.location='view_results.php'">
</form>

<table>
    <tr>
        <th>Measurement ID</th>
        <th>Value</th>
        <th>Unit</th>
        <th>Error</th>
        <th>Apparatus Type</th>
        <th>Experiment Name</th>
        <th>Objective</th>
        <th>Detected Particles</th>
        <th>Beam Current</th>
    </tr>

<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['Measurement_ID']}</td>
            <td>{$row['VALUE']}</td>
            <td>{$row['Unit']}</td>
            <td>{$row['ERROR']}</td>
            <td>{$row['Apparatus_Type']}</td>
            <td>{$row['Experiment_Name']}</td>
            <td>{$row['Objective']}</td>
            <td>{$row['Particles']}</td>
            <td>" . ($row['Beam_Current'] ?? '--') . "</td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='9'>No results found</td></tr>";
}

$conn->close();
?>
</table>

</body>
</html>