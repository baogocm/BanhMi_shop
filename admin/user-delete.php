<?php
session_start();
require_once '../db/connect.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../login.php");
    exit();
}

// Kiểm tra có id người dùng cần xóa
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Lấy thông tin người dùng cần xóa
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Hiển thị thông tin người dùng cần xóa
        ?>
        <!DOCTYPE html>
        <html lang="vi">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Xóa Người Dùng</title>
            <link rel="stylesheet" href="../css/admin/dashboard.css">
        </head>
        <body>
            <div class="admin-container">
                <?php include '../admin/includes/sidebar.php'; ?>

                <div class="main-content">
                    <header class="top-bar">
                        <h1>Xác Nhận Xóa Người Dùng</h1>
                    </header>

                    <div class="content-box">
                        <h2>Bạn có chắc muốn xóa người dùng này?</h2>
                        <p><strong>Tên đăng nhập:</strong> <?php echo $user['username']; ?></p>
                        <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                        <p><strong>Ngày tạo:</strong> <?php echo date('d/m/Y', strtotime($user['created_at'])); ?></p>
                        
                        <form action="user-delete.php?id=<?php echo $user['id']; ?>" method="post">
                            <button type="submit" name="confirm_delete" class="btn-delete">Xóa Người Dùng</button>
                            <a href="user-manage.php" class="btn-cancel">Hủy Bỏ</a>
                        </form>
                    </div>
                </div>
            </div>
        </body>
        </html>
        <?php
    } else {
        // Nếu không tìm thấy người dùng
        header("Location: user-manage.php?message=Không tìm thấy người dùng cần xóa!");
        exit();
    }
}

// Xử lý xóa khi người dùng xác nhận
if (isset($_POST['confirm_delete'])) {
    try {
        // Câu lệnh xóa người dùng khỏi cơ sở dữ liệu
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id]);

        // Chuyển hướng về trang quản lý người dùng với thông báo thành công
        header("Location: users.php?message=Xóa người dùng thành công!");
        exit();
    } catch (PDOException $e) {
        // Xử lý lỗi nếu có
        echo "Lỗi khi xóa người dùng: " . $e->getMessage();
    }
}
?>
