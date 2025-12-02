<?php
session_start();

include("../database.php");

if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
    exit();
}

$contact = $conn->query("SELECT * FROM contact ORDER BY subject");
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
            padding: 10px;
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
            margin-top: 130%;
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

    <div class="container-fluid">
        <div class="row">
            <div id="side" class="col-md-3 sidebar">
                <h3 id="admin_head" class="text-center mb-4">User Query</h3>
                <a href="admindashboard.php"><i class="fa-solid fa-gauge"></i> Admin Dashboard</a>

                <br>
                <?php
                if (isset($_SESSION["admin"]["email"])) { ?>
                    <a href="../request.php?logout=true" id="log" class="text-white btn btn-danger"><i
                            class="fa-solid fa-sign-out-alt"></i>
                        Logout</a>
                <?php } ?>

            </div>

            <div class="col-md-9 p-4">

                <div class="card-box">
                    <h4 class="mb-3">🧐 All Users Query</h4>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Query</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php while ($row = $contact->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= $row['name'] ?></td>
                                    <td><?= $row['email'] ?></td>
                                    <td><?= $row['subject'] ?></td>
                                    <td><?= $row['query'] ?? "not any issuse." ?></td>

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