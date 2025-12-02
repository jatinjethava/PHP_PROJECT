<?php
session_start();
if (!isset($_SESSION['admin'])) {
    echo "<script>alert('❌ Please login only admin to access admin panel.'); window.location.href='../login.php';</script>";
    exit();
}
include("../database.php");

if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
    exit();
}

$total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()["total"];

$today_log = $conn->query("SELECT COUNT(*) AS total FROM users WHERE DATE(last_login) = CURDATE()")->fetch_assoc()["total"];


$users = $conn->query("SELECT * FROM users ORDER BY id DESC LIMIT 10");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f1f1f1;
            margin: 20px;
        }

        #admin_head {
            color: #000000ff;
            border-bottom: 1px solid #00000033;
            padding-bottom: 10px;
        }

        #side {
            border-right: 3px solid #00000014;
            height: 100vh;
            border-radius: 30px;
            box-shadow: 1px 1px 10px #00000014;
        }

        .sidebar {
            height: 100vh;
            color: black;
            padding-top: 20px;
        }

        .sidebar a {
            color: black;
            padding: 12px 20px;
            display: block;
            text-decoration: none;
            font-size: 1.1rem;
        }

        .sidebar a:hover {
            background: #cacacaff;
        }

        #log {
            margin-top: 100%;
            transition: 0.3s ease;
        }

        #log:hover {
            background: #e16767ff;
        }

        .card-box {
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .1);
        }

        .fixed-btn {
            transition: 0.3s ease;
        }

        .fixed-btn:hover {
            transform: scale(1.1);
        }
    </style>
</head>

<body>

    <!-- <a href="../index.php">
        <div class="fixed-btn position-fixed p-3 rounded-circle bg-primary shadow"
            style="bottom: 20px; right: 20px; cursor: pointer;">
            <div class="d-flex justify-content-center align-items-center">
                <i class="fa-solid fa-arrow-left" style="color:white;"></i>
            </div>
        </div>
    </a> -->

    <div class="container-fluid">
        <div class="row main-container">
            <div id="side" class="col-md-3 sidebar">
                <h3 id="admin_head" class="text-center mb-4">Admin Dashboard</h3>

                <a href="users.php"><i class="fa-solid fa-users"></i> Manage Users</a>

                <a href="user_query.php"><i class="fa-solid fa-user-shield"></i> User Query</a>

                <a href="telegram.php"><i class="fa-brands fa-telegram"></i> Our Group</a>

                <br>
                <?php
                if (isset($_SESSION["admin"]["email"])) { ?>
                    <a href="../request.php?logout=true" id="log" class="text-white btn btn-danger"><i
                            class="fa-solid fa-sign-out-alt"></i>
                        Logout</a>
                <?php } ?>
            </div>

            <div class="col-md-9 p-4">

                <div class="card-box mb-4">
                    <h4>👋 Welcome Admin!</h4>
                    <p>Here is your dashboard overview.</p>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="card-box">
                            <h5>Total Users</h5>
                            <h2><?= $total_users ?></h2>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card-box">
                            <h5>Today Logins</h5>
                            <h2><?= $today_log ?></h2>
                        </div>
                    </div>

                </div>

                <br>

                <div class="card-box">
                    <h4 class="mb-3">🧑‍🤝‍🧑 Recent Users Sign Up</h4>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Mobile no.</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php while ($row = $users->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= $row['name'] ?></td>
                                    <td><?= $row['email'] ?></td>
                                    <td><?= $row['mobile_no'] ?? "N/A" ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>

</body>

</html>