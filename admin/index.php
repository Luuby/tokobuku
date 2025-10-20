<?php
session_start();
require_once '../config.php';

// Cek apakah user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Ambil statistik
$total_books = $conn->query("SELECT COUNT(*) FROM books")->fetch_row()[0];
$total_users = $conn->query("SELECT COUNT(*) FROM users WHERE role='user'")->fetch_row()[0];
$total_orders = $conn->query("SELECT COUNT(*) FROM orders")->fetch_row()[0];
$pending_orders = $conn->query("SELECT COUNT(*) FROM orders WHERE status='pending'")->fetch_row()[0];

// Grafik harian bulan ini
$days_in_month = date('t'); // jumlah hari di bulan ini
$daily_orders = [];
$daily_income = [];
$labels = [];

for($d=1; $d<=$days_in_month; $d++){
    $date = date('Y-m-').str_pad($d,2,'0',STR_PAD_LEFT);
    $labels[] = $d; // label hari

    // Jumlah pesanan per hari
    $count = $conn->query("SELECT COUNT(*) FROM orders WHERE DATE(created_at) = '$date'")->fetch_row()[0];
    $daily_orders[] = $count;

    // Total pemasukan per hari
    $income = $conn->query("SELECT IFNULL(SUM(total_price),0) FROM orders WHERE DATE(created_at) = '$date'")->fetch_row()[0];
    $daily_income[] = (float)$income;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Bookstore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card { border-radius: 12px; transition: transform 0.2s, box-shadow 0.2s; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
        .card .card-icon { font-size: 2.5rem; opacity: 0.7; }
        .dashboard-title { margin-bottom: 30px; font-weight: 600; }
        canvas { background-color: #fff; border-radius: 12px; padding: 15px; }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <h2 class="dashboard-title">Admin Dashboard</h2>
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-2"><?php echo $total_books; ?></h5>
                        <p class="card-text">Total Buku</p>
                    </div>
                    <i class="fas fa-book card-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-2"><?php echo $total_users; ?></h5>
                        <p class="card-text">Total User</p>
                    </div>
                    <i class="fas fa-users card-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-2"><?php echo $total_orders; ?></h5>
                        <p class="card-text">Total Pesanan</p>
                    </div>
                    <i class="fas fa-shopping-cart card-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-2"><?php echo $pending_orders; ?></h5>
                        <p class="card-text">Pesanan Pending</p>
                    </div>
                    <i class="fas fa-clock card-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="row">
        <div class="col-md-12">
            <canvas id="ordersChart" height="100"></canvas>
        </div>
    </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('ordersChart').getContext('2d');
const ordersChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [
            {
                label: 'Jumlah Pesanan',
                data: <?php echo json_encode($daily_orders); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3,
                yAxisID: 'y'
            },
            {
                label: 'Total Pemasukan (Rp)',
                data: <?php echo json_encode($daily_income); ?>,
                backgroundColor: 'rgba(255, 206, 86, 0.2)',
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3,
                yAxisID: 'y1'
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: true },
            tooltip: { mode: 'index', intersect: false }
        },
        scales: {
            y: {
                type: 'linear',
                position: 'left',
                beginAtZero: true,
                title: { display: true, text: 'Jumlah Pesanan' },
                ticks: { stepSize: 1 }
            },
            y1: {
                type: 'linear',
                position: 'right',
                beginAtZero: true,
                title: { display: true, text: 'Pemasukan (Rp)' },
                grid: { drawOnChartArea: false }
            }
        }
    }
});
</script>
</body>
</html>
