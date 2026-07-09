<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Total Income
$incomeQuery = mysqli_query($conn, "SELECT SUM(amount) AS total FROM transactions WHERE user_id='$user_id' AND type='Income'");
$income = mysqli_fetch_assoc($incomeQuery);
$totalIncome = $income['total'] ?? 0;

// Total Expense
$expenseQuery = mysqli_query($conn, "SELECT SUM(amount) AS total FROM transactions WHERE user_id='$user_id' AND type='Expense'");
$expense = mysqli_fetch_assoc($expenseQuery);
$totalExpense = $expense['total'] ?? 0;

// Balance
$balance = $totalIncome - $totalExpense;

// Total Transactions
$countQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM transactions WHERE user_id='$user_id'");
$count = mysqli_fetch_assoc($countQuery);
$totalTransactions = $count['total'];

// Expense Category Pie Chart
$chartQuery = mysqli_query($conn,"
SELECT category,SUM(amount) AS total
FROM transactions
WHERE user_id='$user_id'
AND type='Expense'
GROUP BY category
");

$categories = [];
$amounts = [];

while($row = mysqli_fetch_assoc($chartQuery)){
    $categories[] = $row['category'];
    $amounts[] = $row['total'];
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Expense Tracker Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body style="background:#f4f6f9;">

<nav class="navbar navbar-dark bg-primary">

<div class="container">

<span class="navbar-brand fw-bold">
Expense Tracker Dashboard
</span>

<div>

<span class="text-white me-3">
Welcome,
<b><?php echo $_SESSION['username']; ?></b>
</span>

<a href="logout.php" class="btn btn-light">
Logout
</a>

</div>

</div>

</nav>

<div class="container mt-5">

<div class="row g-4">

<div class="col-md-3">

<div class="card bg-success text-white shadow">

<div class="card-body text-center">

<h5>Total Income</h5>

<h3>₹<?php echo number_format($totalIncome,2); ?></h3>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card bg-danger text-white shadow">

<div class="card-body text-center">

<h5>Total Expense</h5>

<h3>₹<?php echo number_format($totalExpense,2); ?></h3>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card bg-warning shadow">

<div class="card-body text-center">

<h5>Balance</h5>

<h3>₹<?php echo number_format($balance,2); ?></h3>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card bg-info text-white shadow">

<div class="card-body text-center">

<h5>Transactions</h5>

<h3><?php echo $totalTransactions; ?></h3>

</div>

</div>

</div>

</div>

<div class="mt-4">

<a href="add_transaction.php" class="btn btn-primary me-2">
+ Add Transaction
</a>

<a href="transactions.php" class="btn btn-dark">
View Transactions
</a>

</div>

<div class="row mt-5">

<div class="col-md-6">

<div class="card shadow">

<div class="card-header bg-dark text-white">

<h5 class="mb-0">Expense by Category</h5>

</div>

<div class="card-body">

<canvas id="expenseChart"></canvas>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card shadow">

<div class="card-header bg-success text-white">

<h5 class="mb-0">Income vs Expense</h5>

</div>

<div class="card-body">

<canvas id="barChart"></canvas>

</div>

</div>

</div>

</div>

</div>

<script>

// Pie Chart

const pieCtx = document.getElementById('expenseChart');

new Chart(pieCtx, {

type:'pie',

data:{

labels: <?php echo json_encode($categories); ?>,

datasets:[{

label:'Expenses',

data: <?php echo json_encode($amounts); ?>

}]

}

});

// Bar Chart

const barCtx=document.getElementById('barChart');

new Chart(barCtx,{

type:'bar',

data:{

labels:['Income','Expense'],

datasets:[{

label:'Amount',

data:[

<?php echo $totalIncome; ?>,

<?php echo $totalExpense; ?>

]

}]

},

options:{

responsive:true,

plugins:{

legend:{

display:false

}

},

scales:{

y:{

beginAtZero:true

}

}

}

});

</script>

</body>

</html>