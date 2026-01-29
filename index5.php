<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>KOREAN MASTER - NATIVE VOICE</title>
    <style>
        :root {
            --primary: #2f65eb; --green: #10b981; --orange: #f59e0b; --bg: #cbd5e1;
            --content-width: 900px;
        }
        body { margin: 0; background: var(--bg); display: flex; justify-content: center; align-items: center; height: 100vh; font-family: 'Segoe UI', sans-serif; gap: 20px; }
        .stage { width: 1100px; height: 700px; background: white; display: flex; flex-direction: column; box-shadow: 0 40px 100px rgba(0, 0, 0, 0.2); border-radius: 20px; overflow: hidden; }
        .header { height: 100px; display: flex; justify-content: center; border-bottom: 2px solid #f1f5f9; }
        .header-content { width: 95%; display: flex; align-items: center; justify-content: space-between; }
        .logo { font-size: 24px; font-weight: 900; color: var(--primary); width: 200px; }
        .nav-title { font-size: 20px; font-weight: 800; color: #1e293b; text-align: center; flex: 1; }
        .counter { font-size: 22px; font-weight: 800; color: #64748b; width: 200px; text-align: right; }
        .card-zone { flex: 1; display: flex; justify-content: center; align-items: center; background: #fafafa; }
        .card { width: var(--content-width); height: 420px; perspective: 1500px; transform-style: preserve-3d; transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; }
        .card.is-flipped { transform: rotateY(180deg); }
        .face { position: absolute; inset: 0; backface-visibility: hidden; border-radius: 40px; display: flex; flex-direction: column; justify-content: center; align-items: center; border: 8px solid var(--primary); background: white; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1); padding: 20px; }
        .back { transform: rotateY(180deg); border-color: var(--green); }
        .img-container { width: 350px; height: 220px; margin-bottom: 15px; border-radius: 15px; overflow: hidden; display: none; border: 1px solid #eee; }
        .img-container img { width: 100%; height: 100%; object-fit: cover; }
        .ko { font-size: 180px; font-weight: 800; color: #1e293b; }
        .vi { font-size: 120px; font-weight: 800; color: var(--green); }
        .footer { height: 110px; display: flex; justify-content: center; align-items: center; gap: 20px; border-top: 2px solid #f1f5f9; }
        .btn { height: 55px; border: none; border-radius: 15px; font-size: 20px; font-weight: bold; cursor: pointer; transition: 0.2s; color: white; display: flex; align-items: center; justify-content: center; }
        .btn-nav { width: 70px; background: var(--primary); }
        .btn-action { min-width: 250px; background: var(--green); }
        .btn-pause { background: var(--orange); }
        .sidebar { width: 300px; height: 700px; background: white; border-radius: 20px; padding: 20px; box-sizing: border-box; }
        .category-list { list-style: none; padding: 0; overflow-y: auto; height: 600px; }
        .category-item { padding: 15px; background: #f8fafc; margin-bottom: 10px; border-radius: 12px; cursor: pointer; font-weight: bold; }
        .category-item.active { background: #eff6ff; border-left: 4px solid var(--primary); color: var(--primary); }
    </style>
</head>
<body>

    <div class="stage">
        <header class="header">
            <div class="header-content">
                <div class="logo">Từ Vựng 한국</div>
                <div id="lesson-title" class="nav-title">CHỌN BÀI HỌC</div>
                <div class="counter" id="counter">0 / 0</div>
            </div>
        </header>

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

        <footer class="footer">
            <button class="btn btn-nav" onclick="prevWord()">❮</button>
            <button class="btn btn-action" id="btnToggle" onclick="toggleAutoPlay()">▶</button>
            <button class="btn btn-nav" onclick="nextWord()">❯</button>
        </footer>
    </div>

    <aside class="sidebar">
        <h3>DANH MỤC</h3>
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

        // Hàm lấy Voice chuẩn bản địa
        function getNativeVoice(langCode) {
            const voices = window.speechSynthesis.getVoices();
            // Ưu tiên tìm giọng Google hoặc Microsoft bản địa (đọc rất chuẩn)
            let filtered = voices.filter(v => v.lang.startsWith(langCode));
            
            // Tìm giọng tốt nhất (ưu tiên Google hoặc Natural)
            return filtered.find(v => v.name.includes('Google')) || 
                   filtered.find(v => v.name.includes('Natural')) || 
                   filtered[0];
        }

        async function speak(text, lang) {
            return new Promise((resolve) => {
                window.speechSynthesis.cancel();
                const ut = new SpeechSynthesisUtterance(text);
                
                // Gán đúng giọng người bản xứ
                const voice = getNativeVoice(lang === 'ko-KR' ? 'ko' : 'vi');
                if (voice) ut.voice = voice;
                
                ut.lang = lang;
                ut.rate = 0.9; 
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
            isPlaying = false; btnToggle.innerText = "▶";
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
        }

        function manualFlip() { if (!isPlaying) card.classList.toggle('is-flipped'); }
        function nextWord() { if (currentIndex < vocabList.length - 1) { currentIndex++; updateCard(); } }
        function prevWord() { if (currentIndex > 0) { currentIndex--; updateCard(); } }

        async function toggleAutoPlay() {
            if (vocabList.length === 0) return;
            if (isPlaying) { isPlaying = false; btnToggle.innerText = "▶"; }
            else { isPlaying = true; btnToggle.innerText = "⏸"; runLoop(); }
        }

        async function runLoop() {
            while (currentIndex < vocabList.length && isPlaying) {
                updateCard();
                await new Promise(r => setTimeout(r, 600));
                await speak(vocabList[currentIndex].ko, 'ko-KR');
                if (!isPlaying) break;
                await new Promise(r => setTimeout(r, 1200));
                card.classList.add('is-flipped');
                await new Promise(r => setTimeout(r, 800));
                await speak(vocabList[currentIndex].vi, 'vi-VN');
                if (!isPlaying) break;
                await new Promise(r => setTimeout(r, 2000));
                if (currentIndex < vocabList.length - 1) currentIndex++;
                else { currentIndex = 0; isPlaying = false; break; }
            }
            btnToggle.innerText = "▶";
        }

        loadCategories();
        // Cần thiết để kích hoạt nạp voice trên Chrome/Edge
        window.speechSynthesis.onvoiceschanged = () => window.speechSynthesis.getVoices();
    </script>
</body>
</html>