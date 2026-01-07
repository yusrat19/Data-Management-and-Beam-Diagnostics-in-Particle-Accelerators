<?php
include 'db_connect.php';

// Filter values
// If a filter field is empty, it is assigned an empty string
$filter_id       = isset($_POST['filter_id']) ? $_POST['filter_id'] : '';
$filter_name     = isset($_POST['filter_name']) ? $_POST['filter_name'] : '';
$filter_position = isset($_POST['filter_position']) ? $_POST['filter_position'] : '';
$filter_start    = isset($_POST['filter_start']) ? $_POST['filter_start'] : '';
$filter_end      = isset($_POST['filter_end']) ? $_POST['filter_end'] : '';
$filter_experiment = isset($_POST['filter_experiment']) ? $_POST['filter_experiment'] : '';

// Build SQL query with LEFT JOIN to Participates & Experiment
// GROUP_CONCAT is an SQL aggregate function that combines multiple values from different rows into one single string.
// SEPARATOR is an optional keyword used with GROUP_CONCAT to define how the values are separated.
$sql = "SELECT e.Employee_ID, e.NAME, e.Employee_Type, e.Joining_Date,
               GROUP_CONCAT(ex.NAME SEPARATOR ', ') AS Experiments
        FROM Employee e
        LEFT JOIN Participates p ON e.Employee_ID = p.Employee_ID
        LEFT JOIN Experiment ex ON p.Experiment_ID = ex.Experiment_ID
        WHERE 1=1";

// real_escape_string prevent SQL injection by escaping special characters such as: . " \ NULL
if(!empty($filter_id)) $sql .= " AND e.Employee_ID = '".$conn->real_escape_string($filter_id)."'";
if(!empty($filter_name)) $sql .= " AND e.NAME LIKE '%".$conn->real_escape_string($filter_name)."%' ";
if(!empty($filter_position)) $sql .= " AND e.Employee_Type LIKE '%".$conn->real_escape_string($filter_position)."%' ";
if(!empty($filter_start)) $sql .= " AND e.Joining_Date >= '".$conn->real_escape_string($filter_start)."' ";
if(!empty($filter_end)) $sql .= " AND e.Joining_Date <= '".$conn->real_escape_string($filter_end)."' ";
if(!empty($filter_experiment)) $sql .= " AND ex.NAME LIKE '%".$conn->real_escape_string($filter_experiment)."%' ";

// adding new clause to existing sql query
$sql .= " GROUP BY e.Employee_ID ORDER BY e.Employee_ID";

$result = $conn->query($sql);   // excute complete quert stored in $sql
if(!$result) {  // if not successful, A database error message is displayed
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Employees</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { padding: 8px 12px; border: 1px solid #aaa; }
        th { background-color: #f2f2f2; }
        input { padding: 5px; margin: 5px; width: 200px; }
        form { margin-bottom: 20px; }
    </style>
</head>
<body>

<h2>View Employees</h2>

<!-- Filter/Search Form -->
<form method="POST" action="">
    ID: <input type="number" name="filter_id" value="<?php echo htmlspecialchars($filter_id); ?>">
    Name: <input type="text" name="filter_name" value="<?php echo htmlspecialchars($filter_name); ?>">
    Position: <input type="text" name="filter_position" value="<?php echo htmlspecialchars($filter_position); ?>">
    Joining From: <input type="date" name="filter_start" value="<?php echo htmlspecialchars($filter_start); ?>">
    To: <input type="date" name="filter_end" value="<?php echo htmlspecialchars($filter_end); ?>">
    Experiment: <input type="text" name="filter_experiment" value="<?php echo htmlspecialchars($filter_experiment); ?>">
    <input type="submit" value="Filter">
    <input type="submit" value="Reset" onclick="window.location='view_employee.php'; return false;">
</form>

<!-- Employees Table -->
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Position</th>
        <th>Joining Date</th>
        <th>Experiments</th>
    </tr>

<?php
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $joining_date = !empty($row['Joining_Date']) ? $row['Joining_Date'] : '--';
        echo "<tr>
            <td>{$row['Employee_ID']}</td>
            <td>{$row['NAME']}</td>
            <td>{$row['Employee_Type']}</td>
            <td>{$joining_date}</td>
            <td>{$row['Experiments']}</td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='5'>No employees found</td></tr>";
}
$conn->close();
?>
</table>

<p><a href="insert_employee.php">Insert New Employee</a></p>

</body>
</html>
