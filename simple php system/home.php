<?php
// Start the session at the beginning
session_start();

// Include the database configuration file
include("php/config.php");

// Ensure the user is logged in by checking the session
if (!isset($_SESSION['valid'])) {
    header("Location: index.php");
    exit();
}

// Initialize the $id from the session
$id = $_SESSION['id'] ?? null;
if (!$id) {
    echo "Error: User ID not found.";
    exit();
}

// Prepare the SQL statement to prevent SQL injection
$stmt = $con->prepare("SELECT * FROM admin_users WHERE ID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $res_Uname = $user['username'];
    $res_Email = $user['email'];
} else {
    echo "Error: User not found.";
    exit();
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css">
    <title>Admin Panel</title>
</head>
<body>
    <div class="nav">
        <div class="logo">
            <p><a href="home.php">PLMUN Alumni</a></p>
        </div>
        <div class="right-links">
            <?php echo "<a href='edit.php?Id=$id'>Change Profile</a>"; ?>
            <form action="php/logout.php" method="post" style="display:inline;">
                <button type="submit" class="btn">Log Out</button>
            </form>
        </div>
    </div>

    <main>
        <div class="main-box top">
            <div class="box" style="text-align: center;">
                <p>Hello <b><?php echo htmlspecialchars($res_Uname); ?></b>, Welcome!</p>
            </div>
        </div>

        <div class="container">
            <?php if (isset($_GET["msg"])): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_GET["msg"]); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Add New Graduate button aligned to the top left of the table -->
            <div class="actions-container">
                <a href="addgrad.php" class="btn btn-dark mb-3">Add New Graduate</a>
            </div>

            <!-- Table starts here -->
            <div class="table">
                <table class="table table-hover text-center">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Alumni ID</th>
                            <th scope="col">Student Number</th>
                            <th scope="col">First Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">Department</th>
                            <th scope="col">Program</th>
                            <th scope="col">Year Graduated</th>
                            <th scope="col">Contact Number</th>
                            <th scope="col">Personal Email</th>
                            <th scope="col">Working Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "
                        SELECT g.*, ws.Working_Status 
                        FROM `2024-2025` g
                        LEFT JOIN `2024-2025_ws` ws ON g.Alumni_ID_Number = ws.Alumni_ID_Number
                        ";
                        $result = mysqli_query($con, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['ID']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Alumni_ID_Number']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Student_Number']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['First_Name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Last_Name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Department']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Program']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Year_Graduated']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Contact_Number']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Personal_Email']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Working_Status'] ?? 'N/A') . "</td>";
                            echo "<td>
                                <a href='editgrad.php?id=" . $row["ID"] . "' class='link-dark me-3'>
                                    <i class='fa-solid fa-pen-to-square fs-5'></i>
                                </a>
                                <a href='deletegrad.php?id=" . $row["ID"] . "' class='link-dark'>
                                    <i class='fa-solid fa-trash fs-5'></i>
                                </a>
                            </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
