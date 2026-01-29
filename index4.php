<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>KOREAN MASTER - YOUTUBE RECORDING MODE</title>
    <style>
        :root {
            --primary: #2f65eb; --green: #10b981; --orange: #f59e0b; --bg: #1a1a1b; /* Đổi nền tối để nổi bật stage khi quay */
            --stage-width: 1280px;
            --stage-height: 720px;
        }
        body { 
            margin: 0; 
            background: var(--bg); 
            display: flex; 
            flex-direction: column;
            align-items: center; 
            justify-content: center;
            min-height: 100vh; 
            font-family: 'Segoe UI', sans-serif; 
        }

        /* --- STAGE CHUẨN 16:9 CHO YOUTUBE --- */
        .stage { 
            width: var(--stage-width); 
            height: var(--stage-height); 
            background: white; 
            display: flex; 
            flex-direction: column; 
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.5);
            position: relative;
            overflow: hidden;
        }

        .header { height: 80px; display: flex; justify-content: center; border-bottom: 3px solid #f1f5f9; background: #fff; }
        .header-content { width: 92%; display: flex; align-items: center; justify-content: space-between; }
        .logo { font-size: 28px; font-weight: 900; color: var(--primary); letter-spacing: -1px; }
        .nav-title { font-size: 26px; font-weight: 800; color: #1e293b; text-transform: uppercase; }
        .counter { font-size: 24px; font-weight: 700; color: #94a3b8; }

        .card-zone { flex: 1; display: flex; justify-content: center; align-items: center; background: #f8fafc; }
        .card { 
            width: 80%; 
            height: 480px; 
            perspective: 2000px; 
            cursor: pointer; 
        }
        .card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            transform-style: preserve-3d;
        }
        .card.is-flipped .card-inner { transform: rotateY(180deg); }

        .face { 
            position: absolute; 
            inset: 0; 
            backface-visibility: hidden; 
            border-radius: 50px; 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
            align-items: center; 
            border: 10px solid var(--primary); 
            background: white; 
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        .back { transform: rotateY(180deg); border-color: var(--green); }

        .img-container { width: 400px; height: 260px; margin-bottom: 20px; border-radius: 20px; overflow: hidden; display: none; border: 2px solid #eee; }
        .img-container img { width: 100%; height: 100%; object-fit: cover; }
        
        /* Cỡ chữ to hơn để lên video Youtube rõ nét */
        .ko { font-size: 160px; font-weight: 800; color: #1e293b; line-height: 1; }
        .vi { font-size: 110px; font-weight: 800; color: var(--green); line-height: 1; text-align: center; padding: 0 20px; }

        .footer { height: 100px; display: flex; justify-content: center; align-items: center; gap: 30px; background: white; }
        .btn { height: 60px; border: none; border-radius: 20px; font-size: 24px; font-weight: bold; cursor: pointer; transition: 0.3s; color: white; display: flex; align-items: center; justify-content: center; }
        .btn-nav { width: 80px; background: var(--primary); }
        .btn-action { min-width: 300px; background: var(--green); font-size: 28px; }
        .btn:hover { transform: scale(1.05); opacity: 0.9; }

        /* Sidebar đẩy xuống dưới, không làm méo khung Stage */
        .sidebar { width: var(--stage-width); margin-top: 30px; background: white; border-radius: 20px; padding: 20px; box-sizing: border-box; }
        .category-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; max-height: 300px; overflow-y: auto; }
        .category-item { padding: 15px; background: #f1f5f9; border-radius: 12px; cursor: pointer; font-weight: bold; text-align: center; transition: 0.2s; }
        .category-item.active { background: var(--primary); color: white; }

        /* Ẩn sidebar khi quay phim (Tùy chọn) */
        @media print { .sidebar { display: none; } }
    </style>
</head>
<body>

    <div class="stage" id="capture-zone">
        <header class="header">
            <div class="header-content">
                <div class="logo">KOREAN MASTER</div>
                <div id="lesson-title" class="nav-title">CHỌN BÀI HỌC</div>
                <div class="counter" id="counter">0 / 0</div>
            </div>
        </header>

        <main class="card-zone">
            <div class="card" id="card" onclick="manualFlip()">
                <div class="card-inner">
                    <div class="face front">
                        <div class="img-container" id="img-box"><img id="vocab-img" src=""></div>
                        <div class="ko" id="text-ko">?</div>
                    </div>
                    <div class="face back">
                        <div class="vi" id="text-vi">...</div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="footer">
            <button class="btn btn-nav" onclick="prevWord()">❮</button>
            <button class="btn btn-action" id="btnToggle" onclick="toggleAutoPlay()">▶ PHÁT TỰ ĐỘNG</button>
            <button class="btn btn-nav" onclick="nextWord()">❯</button>
        </footer>
    </div>

    <aside class="sidebar">
        <h3 style="margin-top:0">BẢNG ĐIỀU KHIỂN DANH MỤC</h3>
        <div class="category-list" id="category-list"></div>
    </aside>

    <script>
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

        function getNativeVoice(langCode) {
            const voices = window.speechSynthesis.getVoices();
            let filtered = voices.filter(v => v.lang.startsWith(langCode));
            return filtered.find(v => v.name.includes('Google')) || 
                   filtered.find(v => v.name.includes('Natural')) || 
                   filtered[0];
        }

        async function speak(text, lang) {
            return new Promise((resolve) => {
                window.speechSynthesis.cancel();
                const ut = new SpeechSynthesisUtterance(text);
                const voice = getNativeVoice(lang === 'ko-KR' ? 'ko' : 'vi');
                if (voice) ut.voice = voice;
                ut.lang = lang;
                ut.rate = 0.85; 
                ut.onend = resolve;
                window.speechSynthesis.speak(ut);
            });
        }

        async function loadCategories() {
            try {
                const res = await fetch('data.php?type=categories');
                const data = await res.json();
                document.getElementById('category-list').innerHTML = data.map(cat => `
                    <div class="category-item" onclick="loadVocab(${cat.id}, '${cat.title}', this)">
                        ${cat.title}
                    </div>
                `).join('');
            } catch (e) {}
        }

        async function loadVocab(id, title, el) {
            isPlaying = false; btnToggle.innerText = "▶ PHÁT TỰ ĐỘNG";
            document.querySelectorAll('.category-item').forEach(i => i.classList.remove('active'));
            el.classList.add('active');
            document.getElementById('lesson-title').innerText = title;
            const res = await fetch(`data.php?type=vocab&cat_id=${id}`);
            vocabList = await res.json();
            currentIndex = 0;
            updateCard();
        }

        function updateCard() {
            if (vocabList.length === 0) return;
            const item = vocabList[currentIndex];
            imgBox.style.display = (item.img && item.img.trim()) ? "block" : "none";
            if (item.img) imgEl.src = item.img;
            koEl.innerText = item.ko;
            viEl.innerText = item.vi;
            counterEl.innerText = `${currentIndex + 1} / ${vocabList.length}`;
            card.classList.remove('is-flipped');
            
            // Tự động điều chỉnh cỡ chữ nếu từ quá dài
            koEl.style.fontSize = item.ko.length > 5 ? "100px" : "160px";
            viEl.style.fontSize = item.vi.length > 15 ? "70px" : "110px";
        }

        function manualFlip() { if (!isPlaying) card.classList.toggle('is-flipped'); }
        function nextWord() { if (currentIndex < vocabList.length - 1) { currentIndex++; updateCard(); } }
        function prevWord() { if (currentIndex > 0) { currentIndex--; updateCard(); } }

        async function toggleAutoPlay() {
            if (vocabList.length === 0) return;
            if (isPlaying) { isPlaying = false; btnToggle.innerText = "▶ PHÁT TỰ ĐỘNG"; }
            else { isPlaying = true; btnToggle.innerText = "⏸ ĐANG CHẠY..."; runLoop(); }
        }

        async function runLoop() {
            while (currentIndex < vocabList.length && isPlaying) {
                updateCard();
                await new Promise(r => setTimeout(r, 800));
                await speak(vocabList[currentIndex].ko, 'ko-KR');
                if (!isPlaying) break;
                await new Promise(r => setTimeout(r, 1500));
                card.classList.add('is-flipped');
                await new Promise(r => setTimeout(r, 1000));
                await speak(vocabList[currentIndex].vi, 'vi-VN');
                if (!isPlaying) break;
                await new Promise(r => setTimeout(r, 2500));
                if (currentIndex < vocabList.length - 1) currentIndex++;
                else { currentIndex = 0; isPlaying = false; break; }
            }
            btnToggle.innerText = "▶ PHÁT TỰ ĐỘNG";
        }

        loadCategories();
        window.speechSynthesis.onvoiceschanged = () => window.speechSynthesis.getVoices();
    </script>
</body>
</html>