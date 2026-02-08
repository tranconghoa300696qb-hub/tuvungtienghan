<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>KOREAN MASTER - TOP NAV</title>
    <style>
        :root {
            --primary: #2f65eb; --green: #10b981; --orange: #f59e0b; --bg: #cbd5e1;
            --content-width: 900px;
        }
        body {
            margin: 0; background: var(--bg); display: flex; flex-direction: column;
            align-items: center; min-height: 100vh; font-family: 'Segoe UI', sans-serif; padding-top: 20px;
        }
        .stage {
            width: 1100px; height: 750px; background: white; display: flex;
            flex-direction: column; box-shadow: 0 40px 100px rgba(0, 0, 0, 0.2);
            border-radius: 20px; overflow: hidden;
        }

        /* --- HEADER & TOP NAV --- */
        .header { 
            background: #fff; border-bottom: 2px solid #f1f5f9;
            display: flex; flex-direction: column; align-items: center;
        }
        .header-top { 
            width: 95%; height: 70px; display: flex; align-items: center; justify-content: space-between; 
        }
        .logo { font-size: 24px; font-weight: 900; color: var(--primary); width: 200px; }
        .nav-title { font-size: 20px; font-weight: 800; color: #1e293b; text-align: center; flex: 1; text-transform: uppercase; }
        .counter { font-size: 22px; font-weight: 800; color: #64748b; width: 200px; text-align: right; }

        /* Vùng chứa Danh mục chính mới ở phía trên */
        .top-menu { 
            width: 100%; background: #f8fafc; border-top: 1px solid #f1f5f9;
            display: flex; justify-content: center; gap: 10px; padding: 10px 0;
        }
        .menu-item {
            padding: 8px 20px; background: white; border-radius: 30px; font-weight: bold;
            cursor: pointer; transition: 0.3s; border: 2px solid #e2e8f0; color: #475569;
        }
        .menu-item:hover { border-color: var(--primary); color: var(--primary); }
        .menu-item.active { background: var(--primary); color: white; border-color: var(--primary); }

        /* --- LAYOUT CHÍNH --- */
        .main-container { display: flex; flex: 1; overflow: hidden; }

        /* Sidebar giờ chỉ chứa danh sách bài học nhỏ */
        .sidebar {
            width: 300px; background: #fafafa; border-right: 2px solid #f1f5f9;
            display: flex; flex-direction: column; padding: 15px; box-sizing: border-box;
        }
        .category-list { list-style: none; padding: 0; overflow-y: auto; flex: 1; }
        .category-item { 
            padding: 12px 15px; background: white; margin-bottom: 8px; border-radius: 10px; 
            cursor: pointer; font-weight: bold; transition: 0.2s; border: 1px solid #e2e8f0;
        }
        .category-item:hover { background: #f1f5f9; }
        .category-item.active { border-left: 5px solid var(--primary); color: var(--primary); background: #eff6ff; }

        /* Vùng Card */
        .card-zone { flex: 1; display: flex; justify-content: center; align-items: center; background: #fff; position: relative; }
        .card { 
            width: 700px; height: 420px; perspective: 1500px; 
            transform-style: preserve-3d; transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; 
        }
        .card.is-flipped { transform: rotateY(180deg); }
        .face { 
            position: absolute; inset: 0; backface-visibility: hidden; border-radius: 40px; 
            display: flex; flex-direction: column; justify-content: center; align-items: center; 
            border: 8px solid var(--primary); background: white; padding: 30px; box-sizing: border-box; text-align: center;
        }
        .back { transform: rotateY(180deg); border-color: var(--green); }

        .img-container { width: 100%; max-width: 400px; height: 200px; margin-bottom: 15px; border-radius: 15px; overflow: hidden; display: none; }
        .img-container img { width: 100%; height: 100%; object-fit: contain; }
        .ko { font-size: 60px; font-weight: 800; color: #1e293b; }
        .vi { font-size: 50px; font-weight: 800; color: var(--green); }

        .footer { height: 100px; display: flex; justify-content: center; align-items: center; gap: 20px; border-top: 2px solid #f1f5f9; }
        .btn { height: 50px; border: none; border-radius: 12px; font-size: 18px; font-weight: bold; cursor: pointer; transition: 0.2s; color: white; display: flex; align-items: center; justify-content: center; }
        .btn-nav { width: 60px; background: var(--primary); }
        .btn-action { min-width: 200px; background: var(--green); }
        .btn-pause { background: var(--orange) !important; }
    </style>
</head>
<body>

    <div class="stage">
        <header class="header">
            <div class="header-top">
                <div class="logo">KOREAN MASTER</div>
                <div id="lesson-title" class="nav-title">CHỌN CHỦ ĐỀ</div>
                <div class="counter" id="counter">0 / 0</div>
            </div>
            <nav class="top-menu" id="top-menu">
                </nav>
        </header>

        <div class="main-container">
            <aside class="sidebar">
                <h4 style="margin: 0 0 10px 0; color: #64748b;">DANH SÁCH BÀI HỌC</h4>
                <div class="category-list" id="category-list">
                    <p style="font-size: 13px; color: #94a3b8;">Hãy chọn danh mục phía trên...</p>
                </div>
            </aside>

            <main class="card-zone">
                <div class="card" id="card" onclick="manualFlip()">
                    <div class="face front">
                        <div class="img-container" id="img-box"><img id="vocab-img" src=""></div>
                        <div class="ko" id="text-ko">?</div>
                    </div>
                    <div class="face back">
                        <div class="vi" id="text-vi">...</div>
                    </div>
                </div>
            </main>
        </div>

        <footer class="footer">
            <button class="btn btn-nav" onclick="prevWord()">❮</button>
            <button class="btn btn-action" id="btnToggle" onclick="toggleAutoPlay()">▶ BẮT ĐẦU HỌC</button>
            <button class="btn btn-nav" onclick="nextWord()">❯</button>
        </footer>
    </div>

    <script>
        // --- GIỮ NGUYÊN PHẦN VOICE VÀ CÁC BIẾN LOGIC CỦA BẠN ---
        let vocabList = [];
        let currentIndex = 0;
        let isPlaying = false;
        const card = document.getElementById('card');
        const koEl = document.getElementById('text-ko');
        const viEl = document.getElementById('text-vi');
        const imgBox = document.getElementById('img-box');
        const imgEl = document.getElementById('vocab-img');
        const counterEl = document.getElementById('counter');
        const btnToggle = document.getElementById('btnToggle');

        // --- CẬP NHẬT CÁC HÀM LOAD DỮ LIỆU ---

        // 1. Nạp danh mục lên THANH MENU TRÊN (Top Nav)
        async function loadCategories() {
            try {
                const res = await fetch('data2.php?type=categories&parent_id=0');
                const data = await res.json();
                let html = '';
                data.forEach(cat => {
                    html += `<div class="menu-item" onclick="selectMainMenu(${cat.id}, '${cat.title}', this)">${cat.title}</div>`;
                });
                document.getElementById('top-menu').innerHTML = html;
            } catch (e) { console.error("Lỗi nạp menu"); }
        }

        // 2. Khi chọn danh mục trên cao -> Nạp bài học vào Sidebar trái
        async function selectMainMenu(parentId, parentTitle, el) {
            // Hiệu ứng active menu
            document.querySelectorAll('.menu-item').forEach(m => m.classList.remove('active'));
            el.classList.add('active');
            
            stopPlay();
            document.getElementById('lesson-title').innerText = parentTitle;
            
            try {
                const res = await fetch(`data2.php?type=categories&parent_id=${parentId}`);
                const data = await res.json();
                
                let html = '';
                data.forEach(sub => {
                    html += `<div class="category-item" onclick="loadVocabDetail(${sub.id}, '${sub.title}', this)">${sub.title}</div>`;
                });
                
                if(data.length === 0) html = `<p style="padding:10px; color:#94a3b8;">Chưa có bài học.</p>`;
                document.getElementById('category-list').innerHTML = html;
            } catch (e) { console.error("Lỗi nạp bài học"); }
        }

        // 3. Nạp Flashcard khi chọn bài học ở Sidebar
        async function loadVocabDetail(id, title, el) {
            stopPlay();
            document.querySelectorAll('.category-item').forEach(i => i.classList.remove('active'));
            el.classList.add('active');
            document.getElementById('lesson-title').innerText = title;

            try {
                const res = await fetch(`data2.php?type=vocab&cat_id=${id}`);
                vocabList = await res.json();
                currentIndex = 0;
                updateCard();
            } catch (e) { alert("Lỗi tải nội dung!"); }
        }

        // --- GIỮ NGUYÊN CÁC HÀM PHÁT ÂM VÀ UPDATE CARD TỪ CODE TRƯỚC CỦA BẠN ---
        function getBestVoice(langCode) {
            const voices = window.speechSynthesis.getVoices();
            if (langCode === 'ko-KR') return voices.find(v => v.name.includes('SunHi')) || voices.find(v => v.lang.includes('ko'));
            if (langCode === 'vi-VN') return voices.find(v => v.name.includes('HoaiMy')) || voices.find(v => v.lang.includes('vi'));
            return voices.find(v => v.lang.includes(langCode));
        }

        function speak(text, langType) {
            return new Promise((resolve) => {
                window.speechSynthesis.cancel();
                setTimeout(() => {
                    const utterance = new SpeechSynthesisUtterance(text);
                    const langCode = langType === 'ko' ? 'ko-KR' : 'vi-VN';
                    utterance.voice = getBestVoice(langCode);
                    utterance.lang = langCode;
                    utterance.rate = 0.9;
                    utterance.onend = () => resolve();
                    utterance.onerror = () => resolve();
                    window.speechSynthesis.speak(utterance);
                    setTimeout(resolve, 5000);
                }, 50);
            });
        }

        async function runLoop() {
            while (isPlaying && currentIndex < vocabList.length) {
                card.classList.remove('is-flipped');
                updateCard('front');
                await new Promise(r => setTimeout(r, 700));
                if (!isPlaying) break;
                await speak(vocabList[currentIndex].ko, 'ko');
                await new Promise(r => setTimeout(r, 800));
                if (!isPlaying) break;
                updateCard('back');
                card.classList.add('is-flipped');
                await new Promise(r => setTimeout(r, 700));
                if (!isPlaying) break;
                await speak(vocabList[currentIndex].vi, 'vi');
                await new Promise(r => setTimeout(r, 1500));
                if (currentIndex < vocabList.length - 1) { currentIndex++; } else { stopPlay(); break; }
            }
        }

        function updateCard(side = 'both') {
            if (!vocabList.length) return;
            const item = vocabList[currentIndex];
            if (side === 'front' || side === 'both') {
                koEl.innerText = item.ko;
                if (item.img) { imgEl.src = item.img; imgBox.style.display = "block"; } 
                else { imgBox.style.display = "none"; }
            }
            if (side === 'back' || side === 'both') viEl.innerText = item.vi;
            else if (side === 'front') viEl.innerText = "";
            counterEl.innerText = `${currentIndex + 1} / ${vocabList.length}`;
        }

        function toggleAutoPlay() {
            if (!vocabList.length) return alert("Vui lòng chọn bài học!");
            if (isPlaying) { stopPlay(); } 
            else {
                if (currentIndex >= vocabList.length - 1) currentIndex = 0;
                isPlaying = true;
                btnToggle.innerText = "⏸ TẠM DỪNG";
                btnToggle.classList.add('btn-pause');
                runLoop();
            }
        }

        function stopPlay() {
            isPlaying = false;
            window.speechSynthesis.cancel();
            btnToggle.innerText = "▶ TIẾP TỤC";
            btnToggle.classList.remove('btn-pause');
        }

        function manualFlip() { if (!isPlaying) card.classList.toggle('is-flipped'); }
        function nextWord() { if (currentIndex < vocabList.length - 1) { currentIndex++; updateCard(); } }
        function prevWord() { if (currentIndex > 0) { currentIndex--; updateCard(); } }

        // Khởi chạy nạp danh mục lên Top Nav
        loadCategories();
        window.speechSynthesis.onvoiceschanged = () => window.speechSynthesis.getVoices();
    </script>
</body>
</html>