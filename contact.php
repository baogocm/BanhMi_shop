<?php
require_once 'db/connect.php';
require_once 'includes/header.php';
?>

<div class="contact-container">
    <div class="contact-header">
        <h1>Liên Hệ Với Chúng Tôi</h1>
        <p>Hãy để lại thông tin, chúng tôi sẽ liên hệ với bạn sớm nhất</p>
    </div>

    <div class="contact-content">
        <div class="contact-info">
            <div class="info-item">
                <i class="fas fa-map-marker-alt"></i>
                <div>
                    <h3>Địa Chỉ</h3>
                    <p>180 Cao Lỗ, Quận 8, TP.HCM</p>
                </div>
            </div>
            <div class="info-item">
                <i class="fas fa-phone"></i>
                <div>
                    <h3>Điện Thoại</h3>
                    <p>0918812818</p>
                </div>
            </div>
            <div class="info-item">
                <i class="fas fa-envelope"></i>
                <div>
                    <h3>Email</h3>
                    <p>nguynbao756@gmail.com</p>
                </div>
            </div>
        </div>

        <div class="contact-form">
            <form action="process_contact.php" method="POST">
                <div class="form-group">
                    <input type="text" name="name" placeholder="Họ và tên" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="tel" name="phone" placeholder="Số điện thoại">
                </div>
                <div class="form-group">
                    <textarea name="message" placeholder="Nội dung tin nhắn" required></textarea>
                </div>
                <button type="submit" class="btn-submit">Gửi Tin Nhắn</button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
