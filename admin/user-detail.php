<?php
session_start();
require_once '../db/connect.php';
require_once '../models/user.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../login.php");
    exit();
}


$userModel = new User($conn);


if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: users.php");
    exit();
}
$user_id = $_GET['id'];


$user = $userModel->getUserById($user_id);

if (!$user) {
    echo "<p>Không tìm thấy người dùng!</p>";
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_user'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        if (!empty($username) && !empty($email) && in_array($role, [0, 1])) {
            $userModel->updateUser($user_id, $username, $email, $role);
            header("Location: users.php?id=$user_id");
            exit();
        } else {
            echo "<p>Vui lòng nhập đầy đủ thông tin và chọn vai trò hợp lệ!</p>";
        }
    }

   
    if (isset($_POST['delete_user'])) {
        $userModel->deleteUser($user_id);
        header("Location: users.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Người Dùng</title>
    <link rel="stylesheet" href="../css/admin/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .content-box {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76,175,80,0.2);
        }

        .form-actions {
            margin-top: 30px;
            margin-bottom: 20px;
        }

        .btn-save {
            background-color: #4CAF50;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .btn-save:hover {
            background-color: #45a049;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        h2 {
            color: #333;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include '../admin/includes/sidebar.php'; ?>

        <div class="main-content">
            <header class="top-bar">
                <h1>Chi Tiết Người Dùng #<?php echo $user['id']; ?></h1>
            </header>

            <div class="content-box">
                <h2>Thông Tin Người Dùng</h2>
                <form method="POST">
                    <div class="form-group">
                        <label for="username">Tên đăng nhập:</label>
                        <input type="text" name="username" id="username" value="<?php echo $user['username']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" value="<?php echo $user['email']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Vai trò:</label>
                        <select name="role" id="role" required>
                            <option value="0" <?php echo $user['role'] == 0 ? 'selected' : ''; ?>>User</option>
                            <option value="1" <?php echo $user['role'] == 1 ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="submit" name="update_user" class="btn-save">Lưu</button>
                    </div>
                </form>

                <form method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa người dùng này?')">
                    <button type="submit" name="delete_user" class="btn-delete">Xóa Người Dùng</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
