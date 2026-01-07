<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Lab Dashboard</title>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body { background: #506b86ff; }
    h1 { text-align: center; margin: 30px 0; }
    .card-hover:hover { transform: scale(1.05); transition: 0.3s; box-shadow: 0 5px 15px rgba(202, 184, 184, 0.2);}
</style>
</head>
<body>

<div class="container">
    <h1>Particle Lab Management System</h1>
    <div class="row row-cols-1 row-cols-md-3 g-4">

        <!-- Employee Cards -->
        <div class="col">
            <div class="card text-center card-hover h-100">
                <div class="card-body">
                    <h5 class="card-title">View Employees</h5>
                    <p class="card-text">Check all staff members in the lab.</p>
                    <a href="view_employee.php" class="btn btn-primary">View</a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card text-center card-hover h-100">
                <div class="card-body">
                    <h5 class="card-title">Add New Staff</h5>
                    <p class="card-text">Insert new employee into the system.</p>
                    <a href="insert_employee.php" class="btn btn-success">Add</a>
                </div>
            </div>
        </div>

        <!-- Experiments -->
        <div class="col">
            <div class="card text-center card-hover h-100">
                <div class="card-body">
                    <h5 class="card-title">Manage Experiments</h5>
                    <p class="card-text">Modify experiment details and objectives.</p>
                    <a href="modify_Experiment.php" class="btn btn-warning">Manage</a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card text-center card-hover h-100">
                <div class="card-body">
                    <h5 class="card-title">View Experiments</h5>
                    <p class="card-text">Check details of all ongoing and past experiments.</p>
                    <a href="view_experiment.php" class="btn btn-info">View</a>
                </div>
            </div>
        </div>
             <div class="col">
            <div class="card text-center card-hover h-100">
                <div class="card-body">
                    <h5 class="card-title">Add Experiments</h5>
                    <p class="card-text">Add new Experiment to the Database</p>
                    <a href="add_experiment.php" class="btn btn-info">View</a>
                </div>
            </div>
        </div>
             <div class="col">
            <div class="card text-center card-hover h-100">
                <div class="card-body">
                    <h5 class="card-title">Add Measurement Result</h5>
                    <p class="card-text">Add new Measurement Results to the Database</p>
                    <a href="insert_measurement.php" class="btn btn-info">View</a>
                </div>
            </div>
        </div>
        <!-- Facilities / Apparatus -->
        <div class="col">
            <div class="card text-center card-hover h-100">
                <div class="card-body">
                    <h5 class="card-title">Manage Facilities</h5>
                    <p class="card-text">Add laboratories, beamlines, detectors.</p>
                    <a href="insert_facility.php" class="btn btn-secondary">Manage</a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card text-center card-hover h-100">
                <div class="card-body">
                    <h5 class="card-title">Available Apparatuses</h5>
                    <p class="card-text">View apparatuses and their current status.</p>
                    <a href="view_apparatus.php" class="btn btn-dark">View</a>
                </div>
            </div>
        </div>

        <!-- Search & Delete -->
        <div class="col">
            <div class="card text-center card-hover h-100">
                <div class="card-body">
                    <h5 class="card-title">Search</h5>
                    <p class="card-text">Search for employees, experiments, or apparatuses.</p>
                    <a href="search_dashboard.php" class="btn btn-primary">Search</a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card text-center card-hover h-100">
                <div class="card-body">
                    <h5 class="card-title">Delete Records</h5>
                    <p class="card-text">Remove employees, experiments, or apparatuses.</p>
                    <a href="delete_dashboard.php" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>

        <!-- Results / Reports -->
        <div class="col">
            <div class="card text-center card-hover h-100">
                <div class="card-body">
                    <h5 class="card-title">View Experiment Results</h5>
                    <p class="card-text">Check results of all experiments and detected particles.</p>
                    <a href="view_results.php" class="btn btn-success">View</a>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card text-center card-hover h-100">
                <div class="card-body">
                    <h5 class="card-title">Export to CSV</h5>
                    <p class="card-text">Generate summary reports for experiments and resources.</p>
                    <a href="generate_report.php" class="btn btn-info">Export</a>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>
