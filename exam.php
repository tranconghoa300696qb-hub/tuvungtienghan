<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=korean_db;charset=utf8mb4", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) { die("DB Error"); }

$stmtExams = $pdo->query("SELECT * FROM exams ORDER BY id DESC");
$allExams = $stmtExams->fetchAll();
$exam_id = isset($_GET['id']) ? (int)$_GET['id'] : ($allExams[0]['id'] ?? 1);

$stmtQuestions = $pdo->prepare("SELECT * FROM questions WHERE exam_id = ?");
$stmtQuestions->execute([$exam_id]);
$questions = $stmtQuestions->fetchAll();
$jsonQuestions = json_encode($questions, JSON_UNESCAPED_UNICODE);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>EPS-TOPIK 실전 시험</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #2563eb; --success: #16a34a; --danger: #dc2626; --bg: #0f172a; --sidebar-w: 280px; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Noto Sans KR', sans-serif; background: var(--bg); color: #fff; height: 100vh; display: flex; overflow: hidden; }

        /* Sidebar */
        .sidebar { width: var(--sidebar-w); background: #1e293b; border-right: 1px solid #334155; display: flex; flex-direction: column; transition: 0.3s; z-index: 100; }
        .sidebar.hidden { margin-left: calc(-1 * var(--sidebar-w)); }
        .sidebar-header { padding: 20px; font-size: 18px; font-weight: bold; background: #0f172a; border-bottom: 1px solid #334155; }
        .exam-item { display: block; padding: 12px 15px; color: #cbd5e1; text-decoration: none; border-bottom: 1px solid #334155; font-size: 14px; }
        .exam-item.active { background: var(--primary); color: #fff; }

        /* Main Canvas 1280x720 */
        .main-wrapper { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #000; position: relative; }
        .obs-canvas { width: 1280px; height: 720px; background: #f8fafc; color: #1e293b; display: flex; flex-direction: column; padding: 40px; position: relative; }

        /* Header UI */
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; border-bottom: 3px solid #3b82f6; padding-bottom: 15px; }
        .q-number { font-size: 32px; font-weight: 800; color: #2563eb; }
        .instruction-text { font-size: 22px; font-weight: 500; color: #475569; margin-top: 5px; max-width: 850px; }
        .timer { font-size: 30px; font-weight: bold; color: #ea580c; background: #ffedd5; padding: 5px 15px; border-radius: 10px; }

        /* Content Area */
        .content-area { display: flex; gap: 40px; flex: 1; overflow: hidden; }
        .left-panel { flex: 1.2; display: flex; flex-direction: column; gap: 20px; }
        .question-content { font-size: 28px; font-weight: 700; color: #1e293b; line-height: 1.4; }
        .q-image { width: 100%; max-height: 320px; object-fit: contain; border: 2px solid #e2e8f0; border-radius: 12px; background: #fff; }

        .right-panel { flex: 0.8; display: flex; flex-direction: column; gap: 12px; justify-content: center; }
        .opt-btn { background: #fff; border: 2px solid #cbd5e1; padding: 20px; border-radius: 15px; font-size: 24px; cursor: pointer; display: flex; align-items: center; transition: 0.2s; }
        .opt-btn:hover { border-color: var(--primary); background: #eff6ff; }
        .opt-btn.correct { background: #dcfce7 !important; border-color: var(--success) !important; color: #166534; font-weight: bold; }
        .opt-btn.wrong { background: #fee2e2 !important; border-color: var(--danger) !important; color: #991b1b; }
        .opt-idx { background: #e2e8f0; color: #475569; width: 40px; height: 40px; border-radius: 50%; display: inline-flex; justify-content: center; align-items: center; margin-right: 20px; font-weight: bold; }
        .correct .opt-idx { background: var(--success); color: #fff; }

        /* Explanation & Controls */
        .explanation { background: #fef9c3; border-left: 6px solid #eab308; padding: 15px; border-radius: 5px; font-size: 20px; display: none; margin-top: 10px; color: #854d0e; }
        .controls { display: flex; justify-content: space-between; align-items: center; margin-top: auto; padding-top: 20px; border-top: 2px solid #e2e8f0; }
        .btn { padding: 12px 25px; border: none; border-radius: 10px; font-size: 18px; font-weight: bold; cursor: pointer; color: #fff; display: flex; align-items: center; gap: 10px; }
        .btn-blue { background: var(--primary); }
        .btn-gray { background: #64748b; }
        .btn-purple { background: #8b5cf6; }

        .toggle-menu { position: absolute; left: 10px; top: 10px; z-index: 101; padding: 5px 10px; background: #334155; color: white; border: none; cursor: pointer; border-radius: 4px; }
    </style>
</head>
<body>

    <button class="toggle-menu" onclick="toggleSidebar()"><i class="fas fa-bars"></i> 메뉴</button>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">시험 목록 (Danh sách đề)</div>
        <div style="overflow-y: auto;">
            <?php foreach($allExams as $exam): ?>
                <a href="?id=<?= $exam['id'] ?>" class="exam-item <?= ($exam['id'] == $exam_id) ? 'active' : '' ?>">
                    <?= htmlspecialchars($exam['title']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="main-wrapper">
        <div class="obs-canvas">
            <div class="header">
                <div>
                    <div class="q-number" id="q-label">1번</div>
                    <div class="instruction-text" id="q-instruction">...</div>
                </div>
                <div class="timer" id="timer">⏳ 00:30</div>
            </div>

            <div class="content-area">
                <div class="left-panel">
                    <div class="question-content" id="q-content">...</div>
                    <div id="audio-box" style="display:none; margin-bottom:10px;">
                        <audio id="audio-player" controls style="width:100%"></audio>
                    </div>
                    <img id="q-img" class="q-image" src="" style="display:none">
                    <div id="q-explain" class="explanation"></div>
                </div>
                <div class="right-panel" id="options-grid"></div>
            </div>

            <div class="controls">
                <div style="color: #94a3b8; font-size: 14px;">Shortcut: [←] 이전 | [→] 다음 | [Space] 듣기</div>
                <div style="display:flex; gap:10px;">
                    <button class="btn btn-purple" onclick="speak()"><i class="fas fa-volume-up"></i> 듣기 (Nghe)</button>
                    <button class="btn btn-gray" onclick="prev()"><i class="fas fa-chevron-left"></i> 이전 (Lùi)</button>
                    <button class="btn btn-blue" onclick="next()">다음 (Tiếp) <i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </div>

<script>
    const questions = <?php echo $jsonQuestions; ?>;
    let currentIdx = 0;
    let answered = false;

    function loadQuestion(idx) {
        if (!questions[idx]) return;
        const q = questions[idx];
        answered = false;

        document.getElementById('q-label').innerText = (idx + 1) + '번';
        document.getElementById('q-instruction').innerText = q.instruction_kr;
        document.getElementById('q-content').innerHTML = q.question_content_kr.replace(/\n/g, "<br>");
        document.getElementById('q-explain').style.display = 'none';
        document.getElementById('q-explain').innerHTML = `<strong>💡 해설:</strong> ${q.explanation}`;

        const img = document.getElementById('q-img');
        if (q.image_url) { img.src = q.image_url; img.style.display = 'block'; }
        else { img.style.display = 'none'; }

        const audioBox = document.getElementById('audio-box');
        const audioPlayer = document.getElementById('audio-player');
        if (q.type === 'listening') { audioPlayer.src = q.audio_url; audioBox.style.display = 'block'; }
        else { audioBox.style.display = 'none'; }

        const grid = document.getElementById('options-grid');
        grid.innerHTML = '';
        for (let i = 1; i <= 4; i++) {
            const btn = document.createElement('div');
            btn.className = 'opt-btn';
            btn.innerHTML = `<span class="opt-idx">${i}</span> ${q['option_' + i]}`;
            btn.onclick = () => check(i, btn, q.correct_index);
            grid.appendChild(btn);
        }
    }

    function check(selected, el, correct) {
        if (answered) return;
        answered = true;
        const all = document.querySelectorAll('.opt-btn');
        if (selected == correct) el.classList.add('correct');
        else { el.classList.add('wrong'); all[correct - 1].classList.add('correct'); }
        document.getElementById('q-explain').style.display = 'block';
    }

    function next() { if (currentIdx < questions.length - 1) { currentIdx++; loadQuestion(currentIdx); } }
    function prev() { if (currentIdx > 0) { currentIdx--; loadQuestion(currentIdx); } }
    function speak() {
        window.speechSynthesis.cancel();
        const msg = new SpeechSynthesisUtterance(questions[currentIdx].question_korean || questions[currentIdx].question_content_kr);
        msg.lang = 'ko-KR';
        window.speechSynthesis.speak(msg);
    }
    function toggleSidebar() { document.getElementById('sidebar').classList.toggle('hidden'); }

    document.addEventListener('keydown', (e) => {
        if (e.key === "ArrowRight") next();
        if (e.key === "ArrowLeft") prev();
        if (e.key === " ") { e.preventDefault(); speak(); }
    });

    loadQuestion(0);
</script>
</body>
</html>