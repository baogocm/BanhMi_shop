<?php
class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Xử lý đăng nhập
    public function login($username, $password) {
        // Tạo câu truy vấn SQL để lấy thông tin người dùng theo username
        $sql = "SELECT * FROM users WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Kiểm tra nếu tìm thấy người dùng
        if ($user) {
            // Kiểm tra mật khẩu với hàm password_verify
            if (password_verify($password, $user['password'])) {
                // Lưu thông tin người dùng vào session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];  // Lưu tên người dùng vào session
                
                // Đặt thông báo đăng nhập thành công vào session
                $_SESSION['login_success'] = true;
                return true;
            } else {
                // Mật khẩu không đúng
                return false;
            }
        } else {
            // Không tìm thấy người dùng với tên đăng nhập đã nhập
            return false;
        }
    }

    // Kiểm tra username đã tồn tại
    private function isUsernameExists($username) {
        $sql = "SELECT COUNT(*) FROM users WHERE username = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username]);
        return $stmt->fetchColumn() > 0;
    }

    // Kiểm tra đăng nhập
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    // Lấy thông tin user
    public function getCurrentUser() {
        if ($this->isLoggedIn()) {
            $sql = "SELECT id, username, email, role FROM users WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$_SESSION['user_id']]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return null;
    }

    // Đăng xuất
    public function logout() {
        session_unset();
        session_destroy();
        return true;
    }


    function getAllUsers($conn) {
        $sql = "SELECT id, email, username, password FROM users";
        $stm = $conn->prepare($sql);  // Sử dụng prepare để tránh SQL Injection
        $stm->execute();  // Thực thi câu lệnh SQL
    
        return $stm->fetchAll(PDO::FETCH_ASSOC);  // Trả về tất cả dữ liệu người dùng dưới dạng mảng kết hợp
    }
}
?>
