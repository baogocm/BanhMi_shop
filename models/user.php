
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
}
