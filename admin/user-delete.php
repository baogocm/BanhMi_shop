<?php
session_start();
require_once '../db/connect.php';
require_once '../models/user.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../login.php");
    exit();
}

// Khởi tạo đối tượng User
$userObj = new User($conn);

// Xử lý xóa khi người dùng xác nhận
if (isset($_POST['confirm_delete']) && isset($_GET['id'])) {
    $user_id = $_GET['id'];
    
    // Thực hiện xóa người dùng
    $result = $userObj->deleteUser($user_id);
    
    if ($result['success']) {
        header("Location: users.php?message=" . urlencode($result['message']));
    } else {
        header("Location: users.php?error=" . urlencode($result['message']));
    }
    exit();
}

// Kiểm tra có id người dùng cần xóa
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $user = $userObj->getUserById($user_id);

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
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
            <style>
                .delete-confirmation {
                    background: white;
                    padding: 30px;
                    border-radius: 10px;
                    box-shadow: 0 0 20px rgba(0,0,0,0.1);
                    max-width: 500px;
                    margin: 50px auto;
                    text-align: center;
                }

                .delete-confirmation h2 {
                    color: #ff4757;
                    margin-bottom: 20px;
                    font-size: 24px;
                }

                .user-info {
                    background: #f8f9fa;
                    padding: 20px;
                    border-radius: 8px;
                    margin: 20px 0;
                    text-align: left;
                }

                .user-info p {
                    margin: 10px 0;
                    color: #333;
                    font-size: 16px;
                }

                .user-info strong {
                    color: #2d3436;
                    display: inline-block;
                    width: 120px;
                }

                .warning-icon {
                    color: #ff4757;
                    font-size: 50px;
                    margin-bottom: 20px;
                }

                .button-group {
                    display: flex;
                    gap: 15px;
                    justify-content: center;
                    margin-top: 30px;
                }

                .btn-delete {
                    background: #ff4757;
                    color: white;
                    border: none;
                    padding: 12px 25px;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 16px;
                    transition: all 0.3s ease;
                }

                .btn-delete:hover {
                    background: #ff2e43;
                    transform: translateY(-2px);
                    box-shadow: 0 2px 5px rgba(255, 71, 87, 0.3);
                }

                .btn-cancel {
                    background: #6c757d;
                    color: white;
                    border: none;
                    padding: 12px 25px;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 16px;
                    text-decoration: none;
                    transition: all 0.3s ease;
                }

                .btn-cancel:hover {
                    background: #5a6268;
                    transform: translateY(-2px);
                    box-shadow: 0 2px 5px rgba(108, 117, 125, 0.3);
                }
            </style>
        </head>
        <body>
            <div class="admin-container">
                <?php include '../admin/includes/sidebar.php'; ?>

                <div class="main-content">
                    <header class="top-bar">
                        <h1>Xác Nhận Xóa Người Dùng</h1>
                    </header>

                    <div class="delete-confirmation">
                        <i class="fas fa-exclamation-triangle warning-icon"></i>
                        <h2>Bạn có chắc muốn xóa người dùng này?</h2>
                        
                        <div class="user-info">
                            <p><strong>Tên đăng nhập:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                            <p><strong>Ngày tạo:</strong> <?php echo date('d/m/Y', strtotime($user['created_at'])); ?></p>
                        </div>
                        
                        <form method="post" action="user-delete.php?id=<?php echo $user['id']; ?>">
                            <div class="button-group">
                                <button type="submit" name="confirm_delete" class="btn-delete">
                                    <i class="fas fa-trash-alt"></i> Xóa Người Dùng
                                </button>
                                <a href="users.php" class="btn-cancel">
                                    <i class="fas fa-times"></i> Hủy Bỏ
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </body>
        </html>
        <?php
    } else {
        // Nếu không tìm thấy người dùng
        header("Location: users.php?error=Không tìm thấy người dùng cần xóa!");
        exit();
    }
}
?>
