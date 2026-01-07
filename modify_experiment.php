<?php
include 'db_connect.php';
$message = '';
$exp = null;

// Step 1: Handle experiment selection
$experiment_id = isset($_POST['experiment_id']) ? intval($_POST['experiment_id']) : 0;
if($experiment_id > 0) {
    $sql = "SELECT * FROM Experiment WHERE Experiment_ID = $experiment_id";
    $result = $conn->query($sql);
    if($result && $result->num_rows > 0) {
        $exp = $result->fetch_assoc();
    } else {
        $message = "Experiment not found.";
    }
}

// Step 2: Handle update submission
if(isset($_POST['update']) && $exp) {
    $name = $conn->real_escape_string($_POST['name']);
    $objective = $conn->real_escape_string($_POST['objective']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $update_sql = "UPDATE Experiment 
                   SET NAME='$name', Objective='$objective', Start_Date='$start_date', End_Date='$end_date'
                   WHERE Experiment_ID=$experiment_id";

    if($conn->query($update_sql)) {
        $message = "Experiment updated successfully!";
        // Refresh $exp values
        $exp['NAME'] = $name;
        $exp['Objective'] = $objective;
        $exp['Start_Date'] = $start_date;
        $exp['End_Date'] = $end_date;
    } else {
        $message = "Update failed: " . $conn->error;
    }
}

// Fetch all experiments for dropdown
$experiments_res = $conn->query("SELECT Experiment_ID, NAME FROM Experiment ORDER BY Experiment_ID");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Modify Experiment Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        label { display:inline-block; width:150px; margin:5px 0; }
        input, textarea, select { width:300px; padding:5px; margin:5px 0; }
        button { padding:7px 15px; margin-top:10px; }
    </style>
</head>
<body>

<h2>Modify Experiment Dashboard</h2>

<?php if($message) echo "<p><b>$message</b></p>"; ?>

<!-- Step 1: Experiment Selection -->
<form method="POST" action="">
    <label>Select Experiment:</label>
    <select name="experiment_id" onchange="this.form.submit()">
        <option value="">-- Select --</option>
        <?php
        while($row = $experiments_res->fetch_assoc()) {
            $selected = ($row['Experiment_ID'] == $experiment_id) ? 'selected' : '';
            echo "<option value='{$row['Experiment_ID']}' $selected>{$row['NAME']}</option>";
        }
        ?>
    </select>
</form>

<!-- Step 2: Edit Form -->
<?php if($exp): ?>
<form method="POST" action="">
    <input type="hidden" name="experiment_id" value="<?php echo $experiment_id; ?>">

    <label>Experiment Name:</label>
    <input type="text" name="name" value="<?php echo htmlspecialchars($exp['NAME'] ?? ''); ?>" required><br>

    <label>Objective:</label>
    <textarea name="objective" required><?php echo htmlspecialchars($exp['Objective'] ?? ''); ?></textarea><br>

    <label>Start Date:</label>
    <input type="date" name="start_date" value="<?php echo $exp['Start_Date'] ?? ''; ?>" required><br>

    <label>End Date:</label>
    <input type="date" name="end_date" value="<?php echo $exp['End_Date'] ?? ''; ?>" required><br>

    <button type="submit" name="update">Update Experiment</button>
</form>
<?php endif; ?>

</body>
</html>