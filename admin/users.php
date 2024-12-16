<?php
session_start();
require_once '../db/connect.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../login.php");
    exit();
}

// Lấy danh sách người dùng
$sql = "SELECT * FROM users ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Người Dùng - Admin</title>
    <link rel="stylesheet" href="../css/admin/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .message {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .btn-edit, .btn-delete, .btn-view {
            padding: 6px 12px;
            border-radius: 4px;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        .btn-view {
            background-color: #17a2b8;
        }
        .btn-edit {
            background-color: #ffc107;
        }
        .btn-delete {
            background-color: #dc3545;
        }
        .btn-view:hover {
            background-color: #138496;
        }
        .btn-edit:hover {
            background-color: #e0a800;
        }
        .btn-delete:hover {
            background-color: #c82333;
        }
        .role-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.85em;
            font-weight: bold;
        }
        .role-badge.admin {
            background-color: #dc3545;
            color: white;
        }
        .role-badge.user {
            background-color: #28a745;
            color: white;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include '../admin/includes/sidebar.php'; ?>

        <div class="main-content">
            <header class="top-bar">
                <h1>Quản Lý Người Dùng</h1>
            </header>

            <?php if (isset($_GET['message'])): ?>
            <div class="message success">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
            <div class="message error">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
            <?php endif; ?>

            <div class="content-box">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên đăng nhập</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <span class="role-badge <?php echo $user['role'] == 1 ? 'admin' : 'user'; ?>">
                                    <?php echo $user['role'] == 1 ? 'Admin' : 'User'; ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                            <td class="actions">
                                <a href="user-detail.php?id=<?php echo $user['id']; ?>" class="btn-view" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="user-edit.php?id=<?php echo $user['id']; ?>" class="btn-edit" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                <a href="user-delete.php?id=<?php echo $user['id']; ?>" 
                                   class="btn-delete" 
                                   onclick="return confirm('Bạn có chắc muốn xóa người dùng này?')"
                                   title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
