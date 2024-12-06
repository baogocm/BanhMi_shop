
<?php
class User {
    private $db;
    private $conn;

    // Constructor nhận đối tượng PDO để kết nối với cơ sở dữ liệu
    public function __construct($db) {
        $this->db = $db;   // Lưu đối tượng PDO vào $db
        $this->conn = $this->db;   // Đặt $conn là đối tượng PDO
    }

    // Hàm đăng nhập
    public function login($username, $password) {
        $sql = "SELECT * FROM users WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
    
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role']; // Lưu quyền vào session
    
                // Chuyển hướng dựa trên role
                if ($user['role'] == 1) {
                    header("Location: dashboard/dashboard.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                return false; // Sai mật khẩu
            }
        } else {
            return false; // Không tìm thấy người dùng
        }
    }
    

    // Hàm đăng xuất
    public function logout() {
        session_unset();
        session_destroy();

        // Đặt thông báo đăng xuất thành công vào session
        $_SESSION['logout_success'] = true;
    }

    // Hàm kiểm tra nếu người dùng đã đăng nhập
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    // Hàm lấy thông tin người dùng
    public function getUser() {
        if ($this->isLoggedIn()) {
            $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return null;
    }

    // Hàm hiển thị thông báo với Toastr
    public function showMessage($message, $type) {
        echo "<script>
            toastr.options = {
                'closeButton': true,
                'progressBar': true,
                'timeOut': '5000',
                'positionClass': 'toast-top-right'
            };
            toastr.$type('$message');
        </script>";
    }


    function getAllUsers($conn) {
        $sql = "SELECT id, email, username, password FROM users";
        $stm = $conn->prepare($sql);  // Sử dụng prepare để tránh SQL Injection
        $stm->execute();  // Thực thi câu lệnh SQL
    
        return $stm->fetchAll(PDO::FETCH_ASSOC);  // Trả về tất cả dữ liệu người dùng dưới dạng mảng kết hợp
    }
}
