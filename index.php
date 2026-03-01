<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>KOREAN MASTER - OPTIMIZED</title>
    <style>
        :root {
            --primary: #2f65eb; --green: #10b981; --orange: #f59e0b; --bg: #cbd5e1;
            --content-width: 900px;
        }
        body {
            margin: 0; background: var(--bg); display: flex; justify-content: center;
            align-items: center; height: 100vh; font-family: 'Segoe UI', sans-serif; gap: 20px;
        }
        .stage {
            width: 1100px; height: 700px; background: white; display: flex;
            flex-direction: column; box-shadow: 0 40px 100px rgba(0, 0, 0, 0.2);
            border-radius: 20px; overflow: hidden;
        }
        .header { height: 100px; display: flex; justify-content: center; border-bottom: 2px solid #f1f5f9; }
        .header-content { width: 95%; display: flex; align-items: center; justify-content: space-between; }
        .logo { font-size: 24px; font-weight: 900; color: var(--primary); width: 200px; }
        .nav-title { font-size: 20px; font-weight: 800; color: #1e293b; text-align: center; flex: 1; }
        .counter { font-size: 22px; font-weight: 800; color: #64748b; width: 200px; text-align: right; }

        .card-zone { flex: 1; display: flex; justify-content: center; align-items: center; background: #fafafa; }
        .card { 
            width: var(--content-width); min-height: 420px; perspective: 1500px; 
            transform-style: preserve-3d; transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; 
        }
        .card.is-flipped { transform: rotateY(180deg); }
        .face { 
            position: absolute; inset: 0; backface-visibility: hidden; border-radius: 40px; 
            display: flex; flex-direction: column; justify-content: center; align-items: center; 
            border: 8px solid var(--primary); background: white; padding: 30px; box-sizing: border-box; text-align: center;
        }
        .back { transform: rotateY(180deg); border-color: var(--green); }

        .img-container { 
            width: 100%; max-width: 500px; height: 300px; margin-bottom: 15px; 
            border-radius: 15px; overflow: hidden; display: none; background: #f0f0f0; 
        }
        .img-container img { width: 100%; height: 100%;  } 
        /* object-fit: contain; */
        .ko { font-size: 80px; font-weight: 800; color: #1e293b; line-height: 1.2; word-break: break-word; }
        .vi { font-size: 60px; font-weight: 800; color: var(--green); line-height: 1.2; word-break: break-word; }

        .footer { height: 110px; display: flex; justify-content: center; align-items: center; gap: 20px; border-top: 2px solid #f1f5f9; }
        .btn { height: 55px; border: none; border-radius: 15px; font-size: 20px; font-weight: bold; cursor: pointer; transition: 0.2s; color: white; display: flex; align-items: center; justify-content: center; }
        .btn-nav { width: 70px; background: var(--primary); }
        .btn-action { min-width: 250px; background: var(--green); }
        .btn-pause { background: var(--orange) !important; }

        .sidebar {
            width: 300px; height: 700px; background: white; border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1); display: flex; flex-direction: column; padding: 20px; box-sizing: border-box;
        }
        .category-list { list-style: none; padding: 0; overflow-y: auto; flex: 1; }
        .category-item { 
            padding: 15px; background: #f8fafc; margin-bottom: 10px; border-radius: 12px; 
            cursor: pointer; font-weight: bold; transition: 0.3s; border-left: 4px solid transparent;
        }
        .category-item.active { background: #eff6ff; border-left-color: var(--primary); color: var(--primary); }
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
        <h3>DANH MỤC <a href="nguphap.php">Ngu phap</a></h3>
        <div class="category-list" id="category-list"></div>
    </aside>

    <script>
        // Ép trình duyệt nạp voices ngay khi vào trang
window.speechSynthesis.getVoices();
if (speechSynthesis.onvoiceschanged !== undefined) {
    speechSynthesis.onvoiceschanged = () => {
        speechSynthesis.getVoices();
        console.log("Voices loaded");
    };
}
let vocabList = [];
let currentIndex = 0;
let isPlaying = false;
let currentUtterance = null; 

const card = document.getElementById('card');
const koEl = document.getElementById('text-ko');
const viEl = document.getElementById('text-vi');
const imgBox = document.getElementById('img-box');
const imgEl = document.getElementById('vocab-img');
const counterEl = document.getElementById('counter');
const btnToggle = document.getElementById('btnToggle');

function getBestVoice(langCode) {
    const voices = window.speechSynthesis.getVoices();
    
    // 1. Xử lý ưu tiên cho Tiếng Hàn (SunHi)
    if (langCode === 'ko-KR') {
        let sunHi = voices.find(v => v.name.includes('SunHi'));
        if (sunHi) return sunHi;
    }
    
    // 2. Xử lý ưu tiên cho Tiếng Việt (HoaiMy)
    if (langCode === 'vi-VN') {
        let hoaiMy = voices.find(v => v.name.includes('HoaiMy'));
        if (hoaiMy) return hoaiMy;
    }

    // 3. Phương án dự phòng: Nếu không tìm thấy giọng đích danh, tìm giọng theo ngôn ngữ
    // Ưu tiên giọng "Natural" (giọng đọc tự nhiên của Edge/Chrome)
    let naturalVoice = voices.find(v => v.lang.includes(langCode) && v.name.includes('Natural'));
    if (naturalVoice) return naturalVoice;

    // Cuối cùng: Lấy bất kỳ giọng nào thuộc ngôn ngữ đó
    return voices.find(v => v.lang.includes(langCode));
}

// 2. Hàm Speak "Bọc Thép"
function speak(text, langType) {
    const cleanText = text.replace(/[,/]/g, ' ');
    text = cleanText;
    return new Promise((resolve) => {
        // Giải phóng hàng chờ cũ ngay lập tức
        window.speechSynthesis.cancel();

        // Delay ngắn để trình duyệt dọn dẹp bộ nhớ TTS
        setTimeout(() => {
            const utterance = new SpeechSynthesisUtterance(text);
            const langCode = langType === 'ko' ? 'ko-KR' : 'vi-VN';
            
            utterance.voice = getBestVoice(langCode);
            utterance.lang = langCode;
            utterance.rate = 0.9;
            utterance.pitch = 1;

            // Xử lý lỗi
            utterance.onerror = (event) => {
                console.warn("Phát âm lỗi, đang thử giọng nói dự phòng...", event.error);
                // Nếu lỗi synthesis-failed, thử nói lại bằng giọng mặc định của hệ thống
                if (event.error === 'synthesis-failed') {
                    utterance.voice = null; 
                    window.speechSynthesis.speak(utterance);
                }
                resolve(); 
            };

            utterance.onend = () => resolve();

            // Kích hoạt thủ công cho Edge
            window.speechSynthesis.resume();
            window.speechSynthesis.speak(utterance);
            
            // Bảo vệ: Nếu sau 5 giây không xong thì tự bỏ qua để không treo loop
            setTimeout(resolve, 5000);
        }, 50);
    });
}

// 3. Logic vòng lặp tự động (Đã tối ưu)

// --- SỬA LẠI VÒNG LẶP ĐỂ ĐẢM BẢO THỨ TỰ HIỆN CHỮ ---
async function runLoop() {
    while (isPlaying && currentIndex < vocabList.length) {
        
        // 1. Lật về mặt trước và xóa chữ cũ
        card.classList.remove('is-flipped');
        updateCard('front'); // Chỉ hiện tiếng Hàn, tiếng Việt để "..."
        
        // Đợi hiệu ứng xoay (0.6s) hoàn tất rồi mới làm bước tiếp theo
        await new Promise(r => setTimeout(r, 700));
        if (!isPlaying) break;

        // 2. Đọc tiếng Hàn (Sun-Hi)
        await speak(vocabList[currentIndex].ko, 'ko');

        // Chờ một chút sau khi đọc xong
        await new Promise(r => setTimeout(r, 800));
        if (!isPlaying) break;
        
        // 3. Chuẩn bị chữ mặt sau và Lật
        updateCard('back'); // Lúc này mới nạp chữ tiếng Việt vào
        card.classList.add('is-flipped');

        // Đợi lật xong (0.6s) rồi mới đọc tiếng Việt
        await new Promise(r => setTimeout(r, 700));
        if (!isPlaying) break;
        
        // 4. Đọc tiếng Việt (Hoài My)
        await speak(vocabList[currentIndex].vi, 'vi');

        // Nghỉ giữa các từ
        await new Promise(r => setTimeout(r, 1500));
        
        if (currentIndex < vocabList.length - 1) {
            currentIndex++;
        } else {
            stopPlay();
            break;
        }
    }
}

// --- CÁC HÀM BỔ TRỢ (GIỮ NGUYÊN HOẶC TỐI ƯU NHẸ) ---

// 1. Hàm nạp Danh mục lớn (Giao diện ban đầu)
async function loadCategories() {
    try {
        const res = await fetch('data2.php?type=categories&parent_id=0');
        const data = await res.json();
        let html = ``;
        data.forEach(cat => {
            html += `<div class="category-item" onclick="loadSubCategories(${cat.id}, '${cat.title}')">${cat.title}</div>`;
        });
        document.getElementById('category-list').innerHTML = html;
    } catch (e) { console.error("Lỗi kết nối"); }
}
// 2. Hàm nạp Bài học khi nhấn vào Danh mục lớn
async function loadSubCategories(parentId, parentTitle) {
    stopPlay();
    document.getElementById('lesson-title').innerText = parentTitle;
    try {
        const res = await fetch(`data2.php?type=categories&parent_id=${parentId}`);
        const data = await res.json();
        
        let html = `<div onclick="loadCategories()" style="cursor:pointer; color:var(--primary); padding:10px; font-weight:bold">⬅ QUAY LẠI</div>`;
        data.forEach(sub => {
            html += `<div class="category-item" onclick="loadVocabDetail(${sub.id}, '${sub.title}', this)">${sub.title}</div>`;
        });
        
        if(data.length === 0) html += `<div style="padding:10px">Chưa có bài học nào trong mục này.</div>`;
        document.getElementById('category-list').innerHTML = html;
    } catch (e) { console.error("Lỗi nạp bài học"); }
}

// 3. Hàm nạp Flashcard (Giữ nguyên logic của bạn nhưng sửa tên hàm)
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
    } catch (e) { alert("Lỗi tải từ vựng!"); }
}


// --- SỬA LẠI HÀM UPDATE CARD ĐỂ KIỂM SOÁT NỘI DUNG ---
function updateCard(side = 'both') {
    if (!vocabList || vocabList.length === 0) return;
    const item = vocabList[currentIndex];
    
    if (side === 'front' || side === 'both') {
        koEl.innerText = item.ko;
        // Xử lý ảnh ở mặt trước
        if (item.img && item.img.trim() !== "") {
            imgEl.src = item.img;
            imgBox.style.display = "block";
        } else {
            imgBox.style.display = "none";
        }
    }
    
    if (side === 'back' || side === 'both') {
        viEl.innerText = item.vi;
    } else if (side === 'front') {
        viEl.innerText = ""; // Xóa chữ tiếng Việt khi đang ở mặt trước
    }
    
    counterEl.innerText = `${currentIndex + 1} / ${vocabList.length}`;
}

function toggleAutoPlay() {
    if (!vocabList.length) return alert("Vui lòng chọn bài học!");
    
    if (isPlaying) { 
        stopPlay(); 
    } else {
        // MỚI: Nếu đang ở cuối bài (nút đang là 🔄), reset về đầu
        if (currentIndex >= vocabList.length - 1) {
            currentIndex = 0;
            updateCard();
        }
        
        isPlaying = true; 
        btnToggle.innerText = "⏸"; 
        btnToggle.classList.add('btn-pause'); 
        runLoop(); 
    }
}

function stopPlay() {
    isPlaying = false;
    window.speechSynthesis.cancel();
    
    // Nếu đã ở cuối danh sách thì đổi icon thành 'Chạy lại'
    if (currentIndex >= vocabList.length - 1) {
        btnToggle.innerText = "▶"; 
    } else {
        btnToggle.innerText = "▶";
    }
    
    btnToggle.classList.remove('btn-pause');
}

function manualFlip() { if (!isPlaying) card.classList.toggle('is-flipped'); }
function nextWord() { if (currentIndex < vocabList.length - 1) { currentIndex++; updateCard(); } }
function prevWord() { if (currentIndex > 0) { currentIndex--; updateCard(); } }

// Cuối file gọi hàm khởi chạy
loadCategories();
window.speechSynthesis.onvoiceschanged = () => window.speechSynthesis.getVoices();
    </script>
</body>
</html>