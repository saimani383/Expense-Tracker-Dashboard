<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    header("Location: transactions.php");
    exit();
}

$id = $_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM transactions WHERE id='$id'");
$row = mysqli_fetch_assoc($result);

if (!$row) {
    die("Transaction not found.");
}

if (isset($_POST['update'])) {

    $type = $_POST['type'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $date = $_POST['date'];

    $sql = "UPDATE transactions
            SET
            type='$type',
            category='$category',
            amount='$amount',
            description='$description',
            date='$date'
            WHERE id='$id' AND user_id='$user_id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: transactions.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Edit Transaction</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card">

<div class="card-header bg-warning">
<h3>Edit Transaction</h3>
</div>

<div class="card-body">

<form method="POST">

<div class="mb-3">

<label>Type</label>

<select name="type" class="form-control">

<option value="Income" <?php if($row['type']=="Income") echo "selected"; ?>>Income</option>

<option value="Expense" <?php if($row['type']=="Expense") echo "selected"; ?>>Expense</option>

</select>

</div>

<div class="mb-3">

<label>Category</label>

<input
type="text"
name="category"
class="form-control"
value="<?php echo $row['category']; ?>"
required>

</div>

<div class="mb-3">

<label>Amount</label>

<input
type="number"
name="amount"
class="form-control"
value="<?php echo $row['amount']; ?>"
required>

</div>

<div class="mb-3">

<label>Description</label>

<textarea
name="description"
class="form-control"><?php echo $row['description']; ?></textarea>

</div>

<div class="mb-3">

<label>Date</label>

<input
type="date"
name="date"
class="form-control"
value="<?php echo $row['date']; ?>"
required>

</div>

<input
type="submit"
name="update"
value="Update"
class="btn btn-success">

<a href="transactions.php" class="btn btn-secondary">
Cancel
</a>

</form>

</div>

</div>

</div>

</body>

</html>