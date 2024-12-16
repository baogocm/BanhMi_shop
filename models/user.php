<?php
class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Xử lý đăng nhập
    public function login($username, $password) {
        try {
            $sql = "SELECT * FROM users WHERE username = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Nếu tìm thấy user và mật khẩu đúng
            if ($user && md5($password)) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                // Chuyển hướng dựa trên role
                if ($user['role'] == 1) {
                    header("Location: admin/dashboard.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            }
            return false;
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            return false;
        }
    }
    

    // Xử lý đăng ký
    public function register($username, $password, $email) {
        try {
            // Kiểm tra username đã tồn tại chưa
            if ($this->isUsernameExists($username)) {
                return false;
            }
    
            // Mã hóa mật khẩu bằng password_hash
            $hashedPassword = md5($password, PASSWORD_DEFAULT);
    
            // Thêm user mới
            $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$username, $hashedPassword, $email]);
        } catch (PDOException $e) {
            error_log("Register error: " . $e->getMessage());
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

    public function getAllUsers() {
        $sql = "SELECT id, email, username, password FROM users";
        $stm = $this->db->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function updateUser($id, $username, $email, $role) {
        $sql = "UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$username, $email, $role, $id]);
    }
    
    public function deleteUser($id) {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
}
?>
