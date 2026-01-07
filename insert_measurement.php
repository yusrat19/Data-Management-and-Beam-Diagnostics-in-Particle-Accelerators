<?php
include 'db_connect.php';

/* ---------- FETCH DATA FOR DROPDOWNS ---------- */

// Experiments
$experiments = $conn->query("SELECT Experiment_ID, NAME FROM Experiment ORDER BY NAME");

// Beams
$beams = $conn->query("SELECT Beam_ID, CURRENT FROM Beam ORDER BY Beam_ID");

// Particles
$particles = $conn->query("SELECT NAME FROM Particle ORDER BY NAME");

/* ---------- HANDLE FORM SUBMISSION ---------- */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $experiment_id = $_POST['experiment_id'];
    $beam_id       = !empty($_POST['beam_id']) ? $_POST['beam_id'] : NULL;
    $apparatus_type = $_POST['apparatus_type'];
    $value          = $_POST['value'];
    $unit           = $_POST['unit'];
    $error          = $_POST['error'];
    $detected_particles = $_POST['particles'] ?? [];

    // 1️⃣ Insert into Measurement
    $stmt1 = $conn->prepare("INSERT INTO Measurement (VALUE, Unit, ERROR) VALUES (?, ?, ?)");
    $stmt1->bind_param("dss", $value, $unit, $error);
    $stmt1->execute();
    $measurement_id = $stmt1->insert_id;
    $stmt1->close();

    // 2️⃣ Insert into Apparatus
    $stmt2 = $conn->prepare("INSERT INTO Apparatus (Apparatus_Type, Measurement_ID, Experiment_ID, Beam_ID) VALUES (?, ?, ?, ?)");
    $stmt2->bind_param("siii", $apparatus_type, $measurement_id, $experiment_id, $beam_id);
    $stmt2->execute();
    $apparatus_id = $stmt2->insert_id;
    $stmt2->close();

    // 3️⃣ Link particles to beam via Composed_Of
    if (!empty($detected_particles) && !empty($beam_id)) {
        $stmt3 = $conn->prepare("INSERT INTO Composed_Of (Particle_Name, Beam_ID) VALUES (?, ?)");
        foreach ($detected_particles as $particle_name) {
            $stmt3->bind_param("si", $particle_name, $beam_id);
            $stmt3->execute();
        }
        $stmt3->close();
    }

    echo "<p style='color:green;'>✔ Measurement recorded successfully</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Record Measurement</title>
<style>
    body { font-family: Arial, sans-serif; background: #f9f9f9; }
    form {
        width: 480px;
        margin: auto;
        background: #fff;
        padding: 20px;
        border-radius: 6px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    label { display: block; margin-top: 12px; }
    input, select { width: 100%; padding: 8px; margin-top: 4px; }
    select[multiple] { height: 140px; }
    button {
        margin-top: 15px;
        padding: 10px;
        width: 100%;
        background: #2c7be5;
        color: white;
        border: none;
        cursor: pointer;
    }
    button:hover { background: #1a5dc9; }
</style>
</head>
<body>

<h2 style="text-align:center;">Record New Measurement</h2>

<form method="POST">

    <label>Experiment</label>
    <select name="experiment_id" required>
        <option value="">-- Select Experiment --</option>
        <?php while($e = $experiments->fetch_assoc()) { ?>
            <option value="<?= $e['Experiment_ID'] ?>"><?= htmlspecialchars($e['NAME']) ?></option>
        <?php } ?>
    </select>

    <label>Beam (optional)</label>
    <select name="beam_id">
        <option value="">-- None --</option>
        <?php while($b = $beams->fetch_assoc()) { ?>
            <option value="<?= $b['Beam_ID'] ?>">Beam <?= $b['Beam_ID'] ?> (<?= $b['CURRENT'] ?> A)</option>
        <?php } ?>
    </select>

    <label>Apparatus Type</label>
    <input type="text" name="apparatus_type" required>

    <label>Measurement Value</label>
    <input type="number" step="any" name="value" required>

    <label>Unit</label>
    <input type="text" name="unit" required>

    <label>Error</label>
    <input type="text" name="error">

    <label>Detected Particles (Ctrl + Click for multiple)</label>
    <select name="particles[]" multiple>
        <?php while($p = $particles->fetch_assoc()) { ?>
            <option value="<?= $p['NAME'] ?>"><?= htmlspecialchars($p['NAME']) ?></option>
        <?php } ?>
    </select>

    <button type="submit">Record Measurement</button>
</form>

<p style="text-align:center;">
    <a href="view_results.php">⬅ Back to Results</a>
</p>

</body>
</html>
