<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$search = "";
$type = "";

$sql = "SELECT * FROM transactions WHERE user_id='$user_id'";

if (isset($_GET['search']) && $_GET['search'] != "") {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $sql .= " AND (category LIKE '%$search%' OR description LIKE '%$search%')";
}

if (isset($_GET['type']) && $_GET['type'] != "") {
    $type = mysqli_real_escape_string($conn, $_GET['type']);
    $sql .= " AND type='$type'";
}

$sql .= " ORDER BY id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>

<head>

    <title>Transaction History</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

</head>

<body class="bg-light">
<div class="main-container">
<div class="container mt-5">

    <h2 class="mb-4">Transaction History</h2>

    <a href="dashboard.php" class="btn btn-primary mb-3">Dashboard</a>
    <form method="GET" class="row mb-3">

<div class="col-md-4">

<input
type="text"
name="search"
class="form-control"
placeholder="Search Category or Description"
value="<?php echo $search; ?>">

</div>

<div class="col-md-3">

<select name="type" class="form-select">

<option value="">All Types</option>

<option value="Income" <?php if($type=="Income") echo "selected"; ?>>Income</option>

<option value="Expense" <?php if($type=="Expense") echo "selected"; ?>>Expense</option>

</select>

</div>

<div class="col-md-2">

<button class="btn btn-primary w-100">
Search
</button>

</div>

<div class="col-md-2">

<a href="transactions.php" class="btn btn-secondary w-100">
Reset
</a>

</div>

</form>
    <table class="table table-bordered table-striped">

        <thead class="table-dark">

            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Type</th>
                <th>Category</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Action</th>
            </tr>

        </thead>

        <tbody>

        <?php
        $serial = 1;

        while($row = mysqli_fetch_assoc($result)){
        ?>

            <tr>

                <td><?php echo $serial++; ?></td>

                <td><?php echo $row['date']; ?></td>

                <td><?php echo $row['type']; ?></td>

                <td><?php echo $row['category']; ?></td>

                <td>₹<?php echo number_format($row['amount'], 2); ?></td>

                <td><?php echo $row['description']; ?></td>

                <td>

                    <a href="edit_transaction.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                        Edit
                    </a>

                    <a href="delete_transaction.php?id=<?php echo $row['id']; ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Are you sure you want to delete this transaction?');">
                        Delete
                    </a>

                </td>

            </tr>

        <?php
        }
        ?>

        </tbody>

    </table>

</div>
</div>
<footer class="mt-5">

Expense Tracker Dashboard © 2026

</footer>
</body>

</html>
