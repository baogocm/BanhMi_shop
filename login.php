<?php
require_once 'db/connect.php';
session_start();
require_once 'models/user.php';

// Nếu đã đăng nhập thì chuyển về trang chủ
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 1) {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: index.php");
    }
    exit();
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate username
    if (empty($username)) {
        $errors['username'] = "Vui lòng nhập tên đăng nhập";
    }

    // Validate password
    if (empty($password)) {
        $errors['password'] = "Vui lòng nhập mật khẩu";
    }

    // Nếu không có lỗi, tiến hành đăng nhập
    if (empty($errors)) {
        $userModel = new User($conn);
        if ($userModel->login($username, $password)) {
            // Đăng nhập thành công - chuyển hướng được xử lý trong hàm login
            exit();
        } else {
            $errors['general'] = "Tên đăng nhập hoặc mật khẩu không đúng";
        }
    }
}

// Kiểm tra thông báo đăng ký thành công
if (isset($_SESSION['register_success'])) {
    $register_success = true;
    unset($_SESSION['register_success']);
}

require_once 'includes/header.php';
?>

<style>
.register-container {
    max-width: 500px;
    margin: 50px auto;
    padding: 20px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.register-container h1 {
    text-align: center;
    color: #333;
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    color: #555;
}

.form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
}

.form-control:focus {
    border-color: #ff4757;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(255, 71, 87, 0.25);
}

.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    color: #dc3545;
    font-size: 14px;
    margin-top: 5px;
}

.btn-primary {
    width: 100%;
    padding: 12px;
    background: #ff4757;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: #ff2e43;
    transform: translateY(-1px);
    box-shadow: 0 2px 5px rgba(255, 71, 87, 0.3);
}

.social-login {
    margin-top: 20px;
    text-align: center;
}

.btn-google {
    width: 100%;
    padding: 12px;
    background: #fff;
    color: #666;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-block;
}

.btn-google:hover {
    background: #f8f9fa;
    border-color: #ff4757;
    color: #ff4757;
}

.btn-google i {
    margin-right: 10px;
    color: #db4437;
}

.mt-3 {
    margin-top: 15px;
    text-align: center;
}

.mt-3 a {
    color: #ff4757;
    text-decoration: none;
    transition: all 0.3s ease;
}

.mt-3 a:hover {
    color: #ff2e43;
    text-decoration: underline;
}

.mx-2 {
    margin: 0 10px;
    color: #ccc;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}

.form-check {
    margin-bottom: 15px;
}

.form-check-input {
    margin-right: 5px;
}

.form-check-label {
    color: #666;
    cursor: pointer;
}

/* Thêm hiệu ứng hover cho checkbox */
.form-check-input:checked {
    background-color: #ff4757;
    border-color: #ff4757;
}

/* Thêm màu nền gradient nhẹ */
body {
    background: linear-gradient(135deg, #ffdde1 0%, #ff9a9e 100%);
}
</style>

<div class="container">
    <div class="register-container">
        <h1>Đăng Nhập</h1>
        
        <?php if (isset($register_success)): ?>
            <div class="alert alert-success">
                Đăng ký thành công! Vui lòng đăng nhập.
            </div>
        <?php endif; ?>

        <?php if (isset($errors['general'])): ?>
            <div class="alert alert-danger">
                <?php echo $errors['general']; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="login.php" id="loginForm" novalidate>
            <div class="form-group">
                <label for="username">Tên đăng nhập:</label>
                <input type="text" name="username" id="username" 
                       class="form-control <?php echo isset($errors['username']) ? 'is-invalid' : ''; ?>"
                       value="<?php echo htmlspecialchars($username ?? ''); ?>">
                <?php if (isset($errors['username'])): ?>
                    <div class="invalid-feedback"><?php echo $errors['username']; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" name="password" id="password" 
                       class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>">
                <?php if (isset($errors['password'])): ?>
                    <div class="invalid-feedback"><?php echo $errors['password']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Đăng Nhập</button>

            <div class="social-login">
                <a href="google-login.php" class="btn btn-google">
                    <i class="fab fa-google"></i> Đăng nhập với Google
                </a>
            </div>
        </form>

        <p class="mt-3">
            <a href="forgot-password.php">Quên mật khẩu?</a>
            <span class="mx-2">|</span>
            <a href="register.php">Đăng ký tài khoản mới</a>
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    
    // Xử lý validate form
    form.addEventListener('submit', function(e) {
        let isValid = true;
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value;

        // Validate username
        if (username === '') {
            showError('username', 'Vui lòng nhập tên đăng nhập');
            isValid = false;
        } else {
            removeError('username');
        }

        // Validate password
        if (password === '') {
            showError('password', 'Vui lòng nhập mật khẩu');
            isValid = false;
        } else {
            removeError('password');
        }

        if (!isValid) {
            e.preventDefault();
        }
    });

    function showError(fieldId, message) {
        const field = document.getElementById(fieldId);
        field.classList.add('is-invalid');
        
        let feedback = field.nextElementSibling;
        if (!feedback || !feedback.classList.contains('invalid-feedback')) {
            feedback = document.createElement('div');
            feedback.classList.add('invalid-feedback');
            field.parentNode.insertBefore(feedback, field.nextSibling);
        }
        feedback.textContent = message;
    }

    function removeError(fieldId) {
        const field = document.getElementById(fieldId);
        field.classList.remove('is-invalid');
        
        const feedback = field.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.remove();
        }
    }

    // Hiển thị thông báo đăng ký thành công
    <?php if (isset($register_success)): ?>
    toastr.success('Đăng ký thành công! Vui lòng đăng nhập.');
    <?php endif; ?>

    // Hiển thị thông báo lỗi đăng nhập
    <?php if (isset($errors['general'])): ?>
    toastr.error('<?php echo $errors['general']; ?>');
    <?php endif; ?>
});
</script>

<?php require_once 'includes/footer.php'; ?>