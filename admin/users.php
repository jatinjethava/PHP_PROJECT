<?php
session_start();

include("../database.php");

if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
    exit();
}

$users = $conn->query("SELECT * FROM users ORDER BY id DESC LIMIT 10");

if (isset($_GET["delete_user"])) {

    $email = $_GET["delete_user"];

    $delete = $conn->prepare("DELETE FROM users WHERE email = ?");
    $delete->bind_param("s", $email);
    $result = $delete->execute();

    if ($result) {
        echo "<script>alert('✅ user Deleted !'); window.location.href='http://localhost:8081/PHP/PROJECT_PHP/admin/admindashboard.php';</script>";
        exit();
    } else {
        $errorMsg = $update->error;
        echo "<script>alert('❌ Not Deleted: $errorMsg'); window.history.back();</script>";
        exit();
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
        }

        .sidebar {
            height: 100vh;
            background: #111827;
            color: white;
            padding-top: 20px;
        }

        .sidebar a {
            color: white;
            padding: 12px 20px;
            display: block;
            text-decoration: none;
            font-size: 1.1rem;
        }

        .sidebar a:hover {
            background: #1f2937;
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
        <div class="row">
            <div class="col-md-3 sidebar">
                <h3 class="text-center mb-4">User Manage</h3>
                <a href="admindashboard.php"><i class="fa-solid fa-gauge"></i> Admin Dashboard</a>

                <br>
                <?php
                if (isset($_SESSION["admin"]["email"])) { ?>
                    <a href="../request.php?logout=true" class="pos btn btn-danger mt-5"><i
                            class="fa-solid fa-sign-out-alt"></i>
                        Logout</a>
                <?php } ?>

            </div>

            <div class="col-md-9 p-4">

                <div class="card-box">
                    <h4 class="mb-3">🧑‍🤝‍🧑 All Users</h4>

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

                                    <td class="d-flex flex-column gap-2 justify-content-center">
                                        <a href="users.php?delete_user=<?= $row['email'] ?>"
                                            onclick="return confirm('Are you sure you want to delete this User?');">
                                            <button class="btn btn-sm btn-outline-danger" name="User_delete">Delete
                                                User</button>
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