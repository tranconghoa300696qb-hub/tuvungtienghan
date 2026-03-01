<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Học Ngữ Pháp Tiếng Hàn cùng Sun-Hi</title>
    <style>
        :root {
            --primary-color: #2d3436;
            --accent-color: #0984e3;
            --bg-color: #f5f6fa;
            --card-bg: #ffffff;
            --scroll-thumb: #bdc3c7;
            --scroll-track: #f1f1f1;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        /* Container chuẩn 16:9 để quay OBS */
        #video-canvas {
            width: 1280px;
            height: 720px;
            background: var(--card-bg);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
            display: flex;
            flex-direction: column;
            padding: 40px 60px;
            box-sizing: border-box;
            border-radius: 8px;
        }

        header {
            border-bottom: 4px solid var(--accent-color);
            padding-bottom: 15px;
            margin-bottom: 20px;
            flex-shrink: 0; /* Không cho phép header bị co lại */
        }

        h1 {
            margin: 0;
            color: var(--primary-color);
            font-size: 48px;
        }

        /* Khu vực nội dung có thanh cuộn */
        .lesson-content {
            flex-grow: 1;
            overflow-y: auto; /* Cho phép cuộn dọc */
            padding-right: 15px; /* Tạo khoảng trống cho thanh cuộn */
            margin-bottom: 80px; /* Chừa chỗ cho nút điều hướng */
        }

        /* Tùy chỉnh thanh cuộn cho đẹp trên video */
        .lesson-content::-webkit-scrollbar {
            width: 10px;
        }

        .lesson-content::-webkit-scrollbar-track {
            background: var(--scroll-track);
            border-radius: 10px;
        }

        .lesson-content::-webkit-scrollbar-thumb {
            background: var(--scroll-thumb);
            border-radius: 10px;
            border: 2px solid var(--scroll-track);
        }

        .lesson-content::-webkit-scrollbar-thumb:hover {
            background: #95a5a6;
        }

        .grammar-title {
            font-size: 36px;
            color: var(--accent-color);
            margin-bottom: 15px;
            font-weight: bold;
        }

        .explanation-text {
            font-size: 26px;
            line-height: 1.5;
            margin-bottom: 20px;
            color: #444;
        }

        .example-box {
            background: #e1f5fe;
            padding: 25px;
            border-radius: 15px;
            border-left: 8px solid var(--accent-color);
        }

        .korean-text {
            font-size: 42px;
            color: #d63031;
            margin-bottom: 10px;
            display: block;
            white-space: pre-line; /* Để hiển thị xuống dòng từ mảng dữ liệu */
            line-height: 1.4;
        }

        .vietnamese-text {
            font-size: 24px;
            color: #636e72;
            font-style: italic;
            display: block;
            margin-top: 10px;
        }

        footer {
            position: absolute;
            bottom: 20px;
            right: 60px;
            font-size: 18px;
            color: #b2bec3;
        }

        .controls {
            position: absolute;
            bottom: 20px;
            left: 60px;
            display: flex;
            gap: 10px;
        }

        button {
            padding: 10px 25px;
            font-size: 18px;
            cursor: pointer;
            background: var(--accent-color);
            color: white;
            border: none;
            border-radius: 5px;
            transition: opacity 0.2s;
        }

        button:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>

    <div id="video-canvas">
        <header>
            <h1 id="main-title">Ngữ pháp Tiếng Hàn</h1>
        </header>

        <div class="lesson-content">
            <div id="grammar-name" class="grammar-title">Đang tải...</div>
            <div id="explanation" class="explanation-text"></div>

            <div class="example-box">
                <span id="kr-ex" class="korean-text"></span>
                <span id="vi-ex" class="vietnamese-text"></span>
            </div>
            
            <div style="height: 20px;"></div>
        </div>

        <div class="controls">
            <button onclick="prevLesson()">Bài trước</button>
            <button onclick="nextLesson()">Bài tiếp theo</button>
        </div>

        <footer>
            Giọng đọc: <strong>Sun-Hi</strong> | Học cùng <strong>Hoài My</strong>
        </footer>
    </div>

    <script>
        const data = [
            {
                title: "Bất quy tắc 으",
                grammar: "Cách chia đuôi -아/어 요 với '으'",
                detail: "Nguyên tắc: Khi gặp nguyên âm, '으' sẽ bị lược bỏ. Nếu trước '으' là '아/오' thì + '아요', trường hợp còn lại + '어요'.",
                kr: [
                    "1. 키가 크다 → 키가 커요 (Cao)",
                    "2. 바쁘다 → 바빠요 (Bận)",
                    "3. 아프다 → 아파요 (Đau/Ốm)",
                    "4. 배가 고프다 → 고파요 (Đói)",
                    "5. 쓰다 → 써요 (Viết/Đắng/Dùng)",
                    "6. 슬프다 → 슬퍼요 (Buồn)",
                    "7. 기쁘다 → 기뻐요 (Vui)"
                ],
                vi: "Ghi chú: Đây là bất quy tắc rất thường gặp trong sơ cấp."
            },
            {
                title: "Ngữ pháp 2: Định ngữ (으)ㄹ",
                grammar: "Định ngữ thì Tương lai: V + -(으)ㄹ",
                detail: "Dùng để bổ nghĩa cho danh từ, diễn tả một hành động sẽ xảy ra hoặc một ý định.",
                kr: [
                    "• 먹을 음식 (Món ăn sẽ ăn)",
                    "• 마실 물 (Nước sẽ uống)",
                    "• 할 일 (Việc sẽ làm)",
                    "• 만날 사람 (Người sẽ gặp)"
                ],
                vi: "Ví dụ: 'Cuốn sách sẽ đọc' nói là 읽을 책."
            }
        ];

        let currentIndex = 0;

        function updateContent() {
            const lesson = data[currentIndex];
            document.getElementById('main-title').innerText = lesson.title;
            document.getElementById('grammar-name').innerText = lesson.grammar;
            document.getElementById('explanation').innerText = lesson.detail;
            
            // Xử lý hiển thị danh sách ví dụ
            const krContent = Array.isArray(lesson.kr) ? lesson.kr.join('\n') : lesson.kr;
            document.getElementById('kr-ex').innerText = krContent;
            document.getElementById('vi-ex').innerText = lesson.vi;

            // Mỗi khi đổi bài, tự động cuộn lên đầu nội dung
            document.querySelector('.lesson-content').scrollTop = 0;
        }

        function nextLesson() {
            if (currentIndex < data.length - 1) {
                currentIndex++;
                updateContent();
            }
        }

        function prevLesson() {
            if (currentIndex > 0) {
                currentIndex--;
                updateContent();
            }
        }

        updateContent();
    </script>

</body>

</html>