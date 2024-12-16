<?php
session_start();
require_once 'db/connect.php';
require_once 'models/user.php';

// Tạo đối tượng User
$userModel = new User($conn);

// Xử lý đăng ký
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $email = trim($_POST['email']);

    // Validate username
    if (empty($username)) {
        $errors['username'] = "Vui lòng nhập tên đăng nhập";
    } elseif (strlen($username) < 3) {
        $errors['username'] = "Tên đăng nhập phải có ít nhất 3 ký tự";
    } elseif (strlen($username) > 20) {
        $errors['username'] = "Tên đăng nhập không được quá 20 ký tự";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors['username'] = "Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới";
    }

    // Validate email
    if (empty($email)) {
        $errors['email'] = "Vui lòng nhập email";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Email không hợp lệ";
    }

    // Validate password
    if (empty($password)) {
        $errors['password'] = "Vui lòng nhập mật khẩu";
    } elseif (strlen($password) < 3) {
        $errors['password'] = "Mật khẩu phải có ít nhất 3 ký tự";
    }

    // Validate confirm password
    if (empty($confirm_password)) {
        $errors['confirm_password'] = "Vui lòng xác nhận mật khẩu";
    } elseif ($password !== $confirm_password) {
        $errors['confirm_password'] = "Mật khẩu xác nhận không khớp";
    }

    // Nếu không có lỗi, tiến hành đăng ký
    if (empty($errors)) {
        if ($userModel->register($username, $password, $email)) {
            $_SESSION['register_success'] = true;
            header("Location: login.php");
            exit();
        } else {
            $errors['general'] = "Tên đăng nhập hoặc email đã tồn tại";
        }
    }
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

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
}

.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}

/* Thêm màu nền gradient nhẹ */
body {
    background: linear-gradient(135deg, #ffdde1 0%, #ff9a9e 100%);
}
</style>

<div class="container">
    <div class="register-container">
        <h1>Đăng Ký</h1>

        <?php if (isset($errors['general'])): ?>
            <div class="alert alert-danger">
                <?php echo $errors['general']; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="register.php" id="registerForm" novalidate>
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
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" 
                       class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>"
                       value="<?php echo htmlspecialchars($email ?? ''); ?>">
                <?php if (isset($errors['email'])): ?>
                    <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
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
                <label for="confirm_password">Xác nhận mật khẩu:</label>
                <input type="password" name="confirm_password" id="confirm_password" 
                       class="form-control <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>">
                <?php if (isset($errors['confirm_password'])): ?>
                    <div class="invalid-feedback"><?php echo $errors['confirm_password']; ?></div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary">Đăng Ký</button>
        </form>

        <p class="mt-3">
            Đã có tài khoản? <a href="login.php">Đăng nhập</a>
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registerForm');
    
    form.addEventListener('submit', function(e) {
        let isValid = true;
        const username = document.getElementById('username').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;

        // Validate username
        if (username === '') {
            showError('username', 'Vui lòng nhập tên đăng nhập');
            isValid = false;
        } else if (username.length < 3) {
            showError('username', 'Tên đăng nhập phải có ít nhất 3 ký tự');
            isValid = false;
        } else if (username.length > 20) {
            showError('username', 'Tên đăng nhập không được quá 20 ký tự');
            isValid = false;
        } else if (!/^[a-zA-Z0-9_]+$/.test(username)) {
            showError('username', 'Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới');
            isValid = false;
        } else {
            removeError('username');
        }

        // Validate email
        if (email === '') {
            showError('email', 'Vui lòng nhập email');
            isValid = false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            showError('email', 'Email không hợp lệ');
            isValid = false;
        } else {
            removeError('email');
        }

        // Validate password
        if (password === '') {
            showError('password', 'Vui lòng nhập mật khẩu');
            isValid = false;
        } else if (password.length < 6) {
            showError('password', 'Mật khẩu phải có ít nhất 6 ký tự');
            isValid = false;
        } else {
            removeError('password');
        }

        // Validate confirm password
        if (confirmPassword === '') {
            showError('confirm_password', 'Vui lòng xác nhận mật khẩu');
            isValid = false;
        } else if (confirmPassword !== password) {
            showError('confirm_password', 'Mật khẩu xác nhận không khớp');
            isValid = false;
        } else {
            removeError('confirm_password');
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

    <?php if (isset($errors['general'])): ?>
    toastr.error('<?php echo $errors['general']; ?>');
    <?php endif; ?>
});
</script>

<?php require_once 'includes/footer.php'; ?>
