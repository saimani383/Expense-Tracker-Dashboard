<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";

if(isset($_POST['save'])){

    $user_id = $_SESSION['user_id'];
    $type = $_POST['type'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $date = $_POST['date'];

    $sql = "INSERT INTO transactions(user_id,type,category,amount,description,date)
            VALUES('$user_id','$type','$category','$amount','$description','$date')";

    if(mysqli_query($conn,$sql)){
        $message = "Transaction Added Successfully!";
    }else{
        $message = "Error!";
    }

}
?>

<!DOCTYPE html>
<html>

<head>

<title>Add Transaction</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card">

<div class="card-header bg-primary text-white">

<h3>Add Transaction</h3>

</div>

<div class="card-body">

<?php
if($message!=""){
echo "<div class='alert alert-success'>$message</div>";
}
?>

<form method="POST">

<div class="mb-3">

<label>Transaction Type</label>

<select name="type" class="form-control">

<option>Income</option>

<option>Expense</option>

</select>

</div>

<div class="mb-3">

<label>Category</label>

<input
type="text"
name="category"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Amount</label>

<input
type="number"
name="amount"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Description</label>

<textarea
name="description"
class="form-control"></textarea>

</div>

<div class="mb-3">

<label>Date</label>

<input
type="date"
name="date"
class="form-control"
required>

</div>

<input
type="submit"
name="save"
value="Save Transaction"
class="btn btn-success">

<a href="dashboard.php" class="btn btn-secondary">

Back

</a>

</form>

</div>

</div>

</div>

</body>

</html>