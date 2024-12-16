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

            // Mã hóa mật khẩu
            $hashedPassword = md5($password);

            // Thêm user mới
            $sql = "INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, 0)";
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

    public function getUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function registerGoogleUser($data) {
        $stmt = $this->db->prepare("INSERT INTO users (username, email, name, password, role) VALUES (?, ?, ?, ?, 0)");
        return $stmt->execute([
            $data['username'],
            $data['email'],
            $data['name'],
            $data['password']
        ]);
    }

    // Lấy thông tin user theo ID
    public function getUserById($id) {
        try {
            $sql = "SELECT * FROM users WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Get user by ID error: " . $e->getMessage());
            return false;
        }
    }

    // Xóa người dùng và đơn hàng liên quan
    public function deleteUser($userId) {
        try {
            // Kiểm tra xem người dùng có đơn hàng không
            $sql = "SELECT COUNT(*) FROM orders WHERE user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            $orderCount = $stmt->fetchColumn();

            // Nếu người dùng còn đơn hàng, không cho phép xóa
            if ($orderCount > 0) {
                return ['success' => false, 'message' => 'Không thể xóa người dùng này vì còn ' . $orderCount . ' đơn hàng! Vui lòng xóa đơn hàng trước.'];
            }

            // Bắt đầu transaction
            $this->db->beginTransaction();

            // Xóa người dùng
            $sql = "DELETE FROM users WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);

            // Commit transaction
            $this->db->commit();
            return ['success' => true, 'message' => 'Xóa người dùng thành công!'];
        } catch (PDOException $e) {
            // Rollback nếu có lỗi
            $this->db->rollBack();
            error_log("Delete user error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Lỗi khi xóa người dùng: ' . $e->getMessage()];
        }
    }

    // Cập nhật thông tin người dùng
    public function updateUser($userId, $data) {
        try {
            $updates = [];
            $params = [];
            
            // Kiểm tra và thêm các trường cần update
            if (isset($data['username'])) {
                $updates[] = "username = ?";
                $params[] = $data['username'];
            }
            
            if (isset($data['email'])) {
                $updates[] = "email = ?";
                $params[] = $data['email'];
            }
            
            if (isset($data['role'])) {
                $updates[] = "role = ?";
                $params[] = $data['role'];
            }
            
            if (isset($data['password']) && !empty($data['password'])) {
                $updates[] = "password = ?";
                $params[] = md5($data['password']);
            }
            
            // Nếu không có dữ liệu cập nhật
            if (empty($updates)) {
                return false;
            }
            
            // Thêm user_id vào cuối mảng params
            $params[] = $userId;
            
            // Tạo câu SQL update
            $sql = "UPDATE users SET " . implode(", ", $updates) . " WHERE id = ?";
            
            // Thực hiện câu lệnh
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute($params);
            
            return $result;
        } catch (PDOException $e) {
            error_log("Update user error: " . $e->getMessage());
            return false;
        }
    }
}
?>