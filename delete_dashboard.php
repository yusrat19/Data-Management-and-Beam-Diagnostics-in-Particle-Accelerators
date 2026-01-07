<?php
include 'db_connect.php';
$message = ''; // stores succesfull/unsuccessful error message
$delete_type = isset($_POST['delete_type']) ? $_POST['delete_type'] : ''; // sttring. identitifies which table to delete from
$record_id = isset($_POST['record_id']) ? intval($_POST['record_id']) : 0; // also string. record id always integer

if(isset($_POST['delete']) && $delete_type && $record_id > 0) {
    switch($delete_type) {
        case 'Employee':
            // Delete from Participates first
            $conn->query("DELETE FROM Participates WHERE Employee_ID=$record_id");
            if($conn->query("DELETE FROM Employee WHERE Employee_ID=$record_id")) {
                $message = "Employee deleted successfully!";
            } else {
                $message = "Error: " . $conn->error;
            }
            break;

        case 'Experiment':
            $conn->query("DELETE FROM Participates WHERE Experiment_ID=$record_id");
            $conn->query("DELETE FROM Apparatus WHERE Experiment_ID=$record_id");
            if($conn->query("DELETE FROM Experiment WHERE Experiment_ID=$record_id")) {
                $message = "Experiment deleted successfully!";
            } else {
                $message = "Error: " . $conn->error;
            }
            break;

        case 'Apparatus':
            if($conn->query("DELETE FROM Apparatus WHERE Machine_ID=$record_id")) {
                $message = "Apparatus deleted successfully!";
            } else {
                $message = "Error: " . $conn->error;
            }
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Delete by Primary Key</title>
<style>
body { font-family: Arial, sans-serif; margin: 20px; }
label { display:inline-block; width:150px; margin:5px 0; }
input, select { width:300px; padding:5px; margin:5px 0; }
button { padding:7px 15px; margin-top:10px; }
</style>
</head>
<body>

<h2>Delete Record by Primary Key</h2>
<?php if($message) echo "<p><b>$message</b></p>"; ?>

<form method="POST" action="">
    <label>Select Type:</label>
    <select name="delete_type" required>
        <option value="">-- Select --</option>
        <option value="Employee" <?php if($delete_type=='Employee') echo 'selected'; ?>>Employee</option>
        <option value="Experiment" <?php if($delete_type=='Experiment') echo 'selected'; ?>>Experiment</option>
        <option value="Apparatus" <?php if($delete_type=='Apparatus') echo 'selected'; ?>>Apparatus</option>
    </select><br>

    <label>Enter ID:</label>
    <input type="number" name="record_id" value="<?php echo htmlspecialchars($record_id); ?>" required><br>

    <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button>
</form>

</body>
</html>
