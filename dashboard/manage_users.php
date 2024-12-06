<?php
require '../middleware/middleware.php';
require '../db/connect.php';
require '../models/user.php'; 
checkAdmin(); 
$userModel = new User($conn); 
$users = $userModel->getAllUsers($conn);

include '../includes/dashboard/header.php';
include '../includes/dashboard/sidebar.php';
include '../includes/dashboard/navbar.php';
?>

<section id="content">
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Quản Lý User</h1>
                <ul class="breadcrumb">
                    <li>
                        <a href="#">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li>
                        <a class="active" href="#">Manage Users</a>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Nội dung quản lý user -->
        <div class="table-data">
			<div class="order">
            <table border="1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Password</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Kiểm tra xem có dữ liệu người dùng hay không
                    if (count($users) > 0) {
                        foreach ($users as $user) {
                            echo "<tr>";
                            echo "<td>" . $user['id'] . "</td>";
                            echo "<td>" . $user['email'] . "</td>";
                            echo "<td>" . $user['username'] . "</td>";
                            echo "<td>" . $user['password'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>Không có dữ liệu người dùng</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
			</div>
        </div>
    </main>
</section>

<?php include '../includes/dashboard/footer.php'; ?>
