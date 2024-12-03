<?php
require_once 'db/connect.php';
require_once 'includes/header.php';
?>

<div class="about-container">
    <!-- Banner Section -->
    <div class="about-banner">
        <div class="banner-overlay"></div>
        <h1>Giới Thiệu Về Bánh Mì Shop</h1>
        <p>Nơi Hương Vị Truyền Thống Gặp Gỡ Sự Sáng Tạo</p>
    </div>

    <!-- Story Section -->
    <div class="about-section">
        <div class="section-container">
            <div class="section-header">
                <i class="fas fa-book-open"></i>
                <h2>Câu Chuyện Của Chúng Tôi</h2>
            </div>
            <div class="section-content">
                <img src="images/story.jpg" alt="Our Story" class="section-image">
                <div class="section-text">
                    <p>Bánh Mì Shop được thành lập từ năm 2020, khởi đầu từ một tiệm bánh nhỏ với niềm đam mê về ẩm thực Việt Nam. Trải qua thời gian, chúng tôi không ngừng phát triển và hoàn thiện để mang đến những sản phẩm chất lượng nhất.</p>
                    <p>Với đội ngũ đầu bếp giàu kinh nghiệm và tâm huyết, chúng tôi tự hào mang đến những ổ bánh mì thơm ngon, được làm từ nguyên liệu tươi ngon nhất và công thức độc đáo được truyền từ nhiều thế hệ.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mission & Vision -->
    <div class="about-section bg-light">
        <div class="section-container">
            <div class="mission-vision-grid">
                <div class="mission-box">
                    <i class="fas fa-bullseye"></i>
                    <h2>Sứ Mệnh</h2>
                    <p>Mang đến những sản phẩm bánh mì chất lượng cao, đảm bảo vệ sinh an toàn thực phẩm, và dịch vụ khách hàng tận tâm.</p>
                </div>
                <div class="vision-box">
                    <i class="fas fa-eye"></i>
                    <h2>Tầm Nhìn</h2>
                    <p>Trở thành thương hiệu bánh mì hàng đầu, được khách hàng tin tưởng và lựa chọn trong mọi dịp.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Core Values -->
    <div class="about-section">
        <div class="section-container">
            <div class="section-header">
                <i class="fas fa-star"></i>
                <h2>Giá Trị Cốt Lõi</h2>
            </div>
            <div class="values-grid">
                <div class="value-item">
                    <i class="fas fa-check-circle"></i>
                    <h3>Chất Lượng</h3>
                    <p>Cam kết sử dụng nguyên liệu tươi ngon nhất</p>
                </div>
                <div class="value-item">
                    <i class="fas fa-heart"></i>
                    <h3>Tận Tâm</h3>
                    <p>Phục vụ khách hàng với cả trái tim</p>
                </div>
                <div class="value-item">
                    <i class="fas fa-lightbulb"></i>
                    <h3>Sáng Tạo</h3>
                    <p>Không ngừng đổi mới công thức và sản phẩm</p>
                </div>
                <div class="value-item">
                    <i class="fas fa-shield-alt"></i>
                    <h3>An Toàn</h3>
                    <p>Đảm bảo vệ sinh an toàn thực phẩm</p>
                </div>
                <div class="value-item">
                    <i class="fas fa-handshake"></i>
                    <h3>Trách Nhiệm</h3>
                    <p>Cam kết trách nhiệm với cộng đồng và môi trường</p>
                </div>
                <div class="value-item">
                    <i class="fas fa-users"></i>
                    <h3>Đồng Đội</h3>
                    <p>Xây dựng môi trường làm việc đoàn kết và chuyên nghiệp</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
