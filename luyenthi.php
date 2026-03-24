<?php
$conn = new mysqli("localhost", "root", "", "korean_db");
$conn->set_charset("utf8mb4");

// 1. Xác định đề hiện tại (mặc định là đề 1)
$current_exam = isset($_GET['exam']) ? (int)$_GET['exam'] : 1;
if ($current_exam < 1) $current_exam = 1;

$limit = 20;
$offset = ($current_exam - 1) * $limit;

// 2. Lấy danh sách câu hỏi cho đề hiện tại
$sql = "SELECT * FROM questions2 LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// 3. Tính tổng số đề (để hiển thị danh sách bên cạnh)
$total_res = $conn->query("SELECT COUNT(*) as total FROM questions2");
$total_questions = $total_res->fetch_assoc()['total'];
$total_exams = ceil($total_questions / $limit);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luyện Giải Đề EPS-TOPIK</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f2f5; display: flex; margin: 0; height: 100vh; }
        
        /* Thanh bên trái: Danh sách đề */
        .sidebar { width: 250px; background: #2c3e50; color: white; padding: 20px; overflow-y: auto; flex-shrink: 0; }
        .sidebar h2 { font-size: 1.2rem; border-bottom: 1px solid #555; padding-bottom: 10px; }
        .exam-list { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-top: 20px; }
        .exam-item { 
            padding: 10px; background: #34495e; text-align: center; border-radius: 5px; 
            text-decoration: none; color: white; transition: 0.3s;
        }
        .exam-item:hover { background: #3498db; }
        .exam-item.active { background: #e67e22; font-weight: bold; }

        /* Nội dung chính bên phải */
        .main-content { flex-grow: 1; padding: 20px; overflow-y: auto; }
        .container { max-width: 800px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        
        .question-box { margin-bottom: 40px; padding-bottom: 20px; border-bottom: 2px solid #f0f0f0; }
        .requirement { color: #2980b9; font-style: italic; font-weight: bold; margin-bottom: 10px; }
        .question-text { 
            border: 1px solid #888; 
            border-radius: 12px; /* Bo góc nhẹ nhàng như ảnh */
            padding: 20px; 
            text-align: left;
            font-size: 1.4rem; 
            margin: 20px 0;
            background-color: #fff;
        }
        /* Class dùng để căn giữa khi văn bản ngắn */
        .text-center { 
            text-align: center !important; 
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .options { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .opt-btn { 
            padding: 15px; border: 1px solid #ddd; background: #fff; text-align: left;
            border-radius: 8px; cursor: pointer; transition: 0.2s;
            font-size: 1.4rem;

            display: flex;
            flex-direction: column; /* Để ảnh nằm trên, chữ nằm dưới hoặc ngược lại */
            justify-content: center;
            padding: 10px;
        }
        .opt-img {
            max-width: 120px; /* Điều chỉnh kích thước ảnh đáp án */
            height: auto;
            margin-bottom: 8px;
            border-radius: 4px;
            max-width: 120px; /* Hoặc kích thước bạn muốn */
            /* Các dòng quan trọng để căn giữa */
            display: block; 
            margin-left: auto; 
            margin-right: auto;
        }

        .opt-btn:hover { background: #edf2f7; border-color: #3498db; }
        
        /* Màu sắc đáp án */
        .correct { background-color: #2ecc71 !important; color: white; border-color: #27ae60; }
        .wrong { background-color: #e74c3c !important; color: white; border-color: #c0392b; }
        
        img { max-width: 100%; border-radius: 5px; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Danh Sách Đề</h2>
    <div class="exam-list">
        <?php for($i = 1; $i <= $total_exams; $i++): ?>
            <a href="?exam=<?php echo $i; ?>" class="exam-item <?php echo ($i == $current_exam) ? 'active' : ''; ?>">
                Đề số <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </div>
</div>

<div class="main-content">
    <div class="container">
        <h1 style="text-align:center; color: #333;">ĐỀ THI SỐ <?php echo $current_exam; ?></h1>
        <hr>

        <?php if ($result->num_rows > 0): ?>
            <?php $count = $offset + 1; while($row = $result->fetch_assoc()): ?>
                <div class="question-box" data-correct="<?php echo $row['correct_option']; ?>">
                    <div class="requirement">Câu <?php echo $count++; ?>: <?php echo $row['requirement']; ?></div>
                    
                    <?php if (!empty($row['image_url'])): ?>
                        <div style="text-align:center;"><img src="<?php echo $row['image_url']; ?>"></div>
                    <?php else: ?>
                       <div class="question-text dynamic-align"><?php echo $row['question_text']; ?></div>
                    <?php endif; ?>
                   
                    <div class="options">
                        <?php for ($i = 1; $i <= 4; $i++): 
                            $opt_text = $row['option'.$i];
                            $opt_img = $row['option'.$i.'_img']; // Lấy url ảnh từ cột mới
                        ?>
                            <button class="opt-btn" onclick="checkAnswer(this, <?php echo $i; ?>)">
                               

                                <?php if (!empty($opt_img)): ?>
                                     <span><?php $numbers = ['','①','②','③','④'];  echo $numbers[$i];  ?></span>  
                                    <img src="<?php echo $opt_img; ?>" class="opt-img">
                                <?php endif; ?>

                                <?php if (!empty($opt_text)): ?>
                                    <div class="opt-label">  
                                        <span><?php $numbers = ['','①','②','③','④'];  echo $numbers[$i];  ?></span>  
                                        <?php echo $opt_text; ?>  </div>
                                <?php endif; ?>
                            </button>
                    <?php endfor; ?>
</div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center;">Dữ liệu đề thi này đang được cập nhật.</p>
        <?php endif; ?>
    </div>
</div>

<script>
function checkAnswer(btn, selected) {
    const box = btn.closest('.question-box');
    const correct = parseInt(box.getAttribute('data-correct'));
    const allButtons = box.querySelectorAll('.opt-btn');

    // Reset màu đỏ cho các nút khác nếu người dùng chọn lại
    allButtons.forEach(b => b.classList.remove('wrong'));

    if (selected === correct) {
        btn.classList.add('correct');
        // Vô hiệu hóa các nút sau khi đã chọn đúng
        // allButtons.forEach(b => b.style.pointerEvents = 'none');
    } else {
        btn.classList.add('wrong');
    }
}
document.addEventListener("DOMContentLoaded", function() {
    // Tìm tất cả các khung văn bản có class 'dynamic-align'
    const textBoxes = document.querySelectorAll('.dynamic-align');

    textBoxes.forEach(box => {
        // Lấy nội dung văn bản, loại bỏ khoảng trắng thừa
        const text = box.innerText.trim();
        
        // Đếm số lượng từ bằng cách tách chuỗi bởi khoảng trắng
        const wordCount = text.split(/\s+/).filter(word => word.length > 0).length;

        // Nếu số từ từ 1 đến 4 (văn bản ngắn) thì căn giữa
        if (wordCount > 0 && wordCount <= 4) {
            box.classList.add('text-center');
        }
    });
});
</script>

</body>
</html>