<?php
include 'db_connect.php';
$message = '';
$delete_type = isset($_POST['search_type']) ? $_POST['search_type'] : '';
$search_id = isset($_POST['search_id']) ? intval($_POST['search_id']) : 0;
$result = null;

if(isset($_POST['search']) && $delete_type && $search_id > 0) {
    switch($delete_type) {
        case 'Employee':
            $sql = "SELECT * FROM Employee WHERE Employee_ID=$search_id";
            break;
        case 'Experiment':
            $sql = "SELECT * FROM Experiment WHERE Experiment_ID=$search_id";
            break;
        case 'Apparatus':
            $sql = "SELECT * FROM Apparatus WHERE Machine_ID=$search_id";
            break;
    }
    $result = $conn->query($sql);
    if(!$result || $result->num_rows==0){
        $message = "No record found with ID: $search_id";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Search by ID</title>
<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    label { display:inline-block; width:150px; margin:5px 0; }
    input, select { width:300px; padding:5px; margin:5px 0; }
    button { padding:7px 15px; margin-top:10px; }
    table { border-collapse: collapse; margin-top: 20px; }
    table, th, td { border: 1px solid black; padding: 5px; }
</style>
</head>
<body>

<h2>Search by Primary Key</h2>

<form method="POST" action="">
    <label>Search Type:</label>
    <select name="search_type">
        <option value="">-- Select --</option>
        <option value="Employee" <?php if($delete_type=='Employee') echo 'selected'; ?>>Employee</option>
        <option value="Experiment" <?php if($delete_type=='Experiment') echo 'selected'; ?>>Experiment</option>
        <option value="Apparatus" <?php if($delete_type=='Apparatus') echo 'selected'; ?>>Apparatus</option>
    </select><br>

    <label>Enter ID:</label>
    <input type="number" name="search_id" value="<?php echo htmlspecialchars($search_id); ?>" required><br>

    <button type="submit" name="search">Search</button>
</form>

<?php if($message) echo "<p><b>$message</b></p>"; ?>

<?php if($result && $result->num_rows > 0): ?>
    <table>
        <tr>
            <?php
            // Display table headers dynamically
            $fields = $result->fetch_fields();
            foreach($fields as $f) echo "<th>{$f->name}</th>";
            ?>
        </tr>
        <?php
        $result->data_seek(0);
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach($row as $v) echo "<td>".htmlspecialchars($v)."</td>";
            echo "</tr>";
        }
        ?>
    </table>
<?php endif; ?>

</body>
</html>

