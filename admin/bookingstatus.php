<?php
session_start();

include("../database.php");

if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
    exit();
}

$booking = $conn->query("SELECT * FROM booking ORDER BY id DESC");

if (isset($_GET["Confirm"])) {

    $id = $_GET["Confirm"];

    $bookingCheck = $conn->query("SELECT status FROM booking WHERE id = $id");
    $bookingRow = $bookingCheck->fetch_assoc();

    if ($bookingRow["status"] === 'Confirmed') {
        echo "<script>alert('✅ Booking already confirmed!'); window.history.back();</script>";
        exit();
    } else {
        $update = $conn->prepare("UPDATE booking SET status = 'Confirmed' WHERE id = ?");
        $update->bind_param("i", $id);
        $result = $update->execute();

        if ($result) {
            echo "<script>alert('✅ Booking Confirmed !'); window.location.href='http://localhost:8081/PHP/PROJECT_PHP/admin/bookingstatus.php';</script>";
            exit();
        } else {
            $errorMsg = $update->error;
            echo "<script>alert('❌ Not Confirmed: $errorMsg'); window.history.back();</script>";
            exit();
        }
    }


}

if (isset($_GET["reject"])) {

    $id = $_GET["reject"];

    $bookingCheck = $conn->query("SELECT status FROM booking WHERE id = $id");
    $bookingRow = $bookingCheck->fetch_assoc();

    if ($bookingRow["status"] === 'Rejected') {
        echo "<script>alert('✅ Booking already Rejected!'); window.history.back();</script>";
        exit();
    } else {
        $update = $conn->prepare("UPDATE booking SET status = 'Rejected' WHERE id = ?");
        $update->bind_param("i", $id);
        $result = $update->execute();

        if ($result) {
            echo "<script>alert('✅ Booking Rejected !'); window.location.href='http://localhost:8081/PHP/PROJECT_PHP/admin/bookingstatus.php';</script>";
            exit();
        } else {
            $errorMsg = $update->error;
            echo "<script>alert('❌ Not Rejected: $errorMsg'); window.history.back();</script>";
            exit();
        }
    }


}
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
            padding-left: 200px;
        }

        #side {
            height: 25vh;
            width: 100%;
            display: flex;
            padding: 10px;
            border-bottom: 2px solid #00000033;
        }

        #side .div1 {
            width: 85%;
            padding-right: 20px;
        }

        #side .div2 {
            width: 15%;
            padding-top: 65px;
            padding-left: 20px;
        }

        .sidebar {
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

        /* #log {
            position: fixed;
            top: 95%;
            left: 50%;
            transform: translate(-50%, -50%);
            transition: 0.3s ease;
        } */

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

        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }

        .badge-confirmed {
            background: rgba(34, 197, 94, 0.15);
            color: #074e21bb;
        }

        .badge-pending {
            background: rgba(234, 179, 8, 0.15);
            color: #ffa600ff;
        }

        .badge-cancelled {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
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
        <div class="row">
            <div id="side" class="col-md-3 sidebar">
                <div class="div1">
                    <h3 id="admin_head" class="text-center mb-4">Booking Confirmation</h3>

                    <a href="admindashboard.php"><i class="fa-solid fa-gauge"></i> Admin Dashboard</a>
                </div>
                <div class="div2">
                    <?php
                    if (isset($_SESSION["admin"]["email"])) { ?>
                        <a href="../request.php?logout=true" id="log" class="text-white btn btn-danger"><i
                                class="fa-solid fa-sign-out-alt"></i>
                            Logout</a>
                    <?php } ?>
                </div>
                <br>

            </div>

            <div class="col-md-12 p-4">

                <div class="card-box">
                    <h4 class="mb-3">🧑‍🤝‍🧑 All Users Status</h4>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No .</th>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Mobile no.</th>
                                <th>date</th>
                                <th>time</th>
                                <th>events</th>
                                <th>status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php while ($row = $booking->fetch_assoc()) { ?>
                                <?php $stat = $row['status']; ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= $row['name'] ?></td>
                                    <td><?= $row['email'] ?></td>
                                    <td><?= $row['number'] ?? "N/A" ?></td>
                                    <td><?= $row['date'] ?></td>
                                    <td><?= $row['time'] ?></td>
                                    <td><?= $row['event'] ?></td>
                                    <td>
                                        <span class="badge-status <?php
                                        if ($stat == 'Pending') {
                                            echo 'badge-pending';
                                        } elseif ($stat == 'Rejected') {
                                            echo 'badge-cancelled';
                                        } elseif ($stat == 'Confirmed') {
                                            echo 'badge-confirmed';
                                        } else {
                                            echo 'badge-pending';
                                        } ?>"><?= $row['status'] ?></span>

                                    </td>



                                    <td class="d-flex flex-column gap-2 justify-content-center">
                                        <a href="bookingstatus.php?Confirm=<?= $row['id'] ?>">
                                            <button class="btn btn-sm btn-outline-success" name="Confirm">Comform</button>
                                        </a>

                                        <a href="bookingstatus.php?reject=<?= $row['id'] ?>">
                                            <button class="btn btn-sm btn-outline-danger" name="reject">Reject</button>
                                        </a>
                                    </td>

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