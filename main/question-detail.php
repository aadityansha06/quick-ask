<?php
include('common.php');
include('server/db.php'); // make sure $conn is initialized
$qid = isset($_GET['q-id']) ? (int)$_GET['q-id'] : 0;

$stmt = $conn->prepare("
    SELECT q.*, u.username
    FROM question q
    JOIN users u ON q.user_id = u.id
    WHERE q.id = ?
");
$stmt->execute([$qid]);
$question = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$question) {
    echo "<p class='text-warning text-center mt-5'>Question not found.</p>";
    exit;
}

// Fetch hashtags
$tagStmt = $conn->prepare("
    SELECT h.tag_name FROM question_hastag qh
    JOIN hastag h ON qh.hastag_id = h.id
    WHERE qh.ques_id = ?
");
$tagStmt->execute([$qid]);
$hashtags = $tagStmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($question['title']) ?> - QuickAsk</title>
  <
  <style>
    body {
      background: black;
      color: white;
    }

    .text-cyan {
      color: #00ffff;
    }

    .text-cyan:hover {
      color: #00e6e6;
      text-shadow: 0 0 8px #00ffff;
    }

    .question-card {
      background-color: #1a1a1a;
      border: 1px solid #00ffff33;
      box-shadow: 0 0 15px #00ffff33;
      border-radius: 12px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .question-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 0 20px #00ffff99;
    }

    .bg-cyan {
      background-color: #00ffff;
    }

    .btn-outline-cyan {
  color: #00ffff;
  border: 1px solid #00ffff;
  background: transparent;
  transition: 0.3s;
}
.btn-outline-cyan:hover {
  background-color: #00ffff;
  color: #000;
  box-shadow: 0 0 10px #00ffff;
}
.border-cyan {
  border-color: #00ffff !important;
}
#muted{
  color:#A8A8A8!important;
}

  </style>
</head>
<body>

<div class="container mt-5">
  <div class="card question-card mx-auto p-4" style="max-width: 800px;">
    <h2 class="text-cyan"><?= htmlspecialchars($question['title']) ?></h2>

    <div class="mt-3" style="color:white">
      <?= nl2br(htmlspecialchars($question['descriptation'])) ?>
    </div>

    <div class="mt-3">
      <?php foreach ($hashtags as $tag): ?>
        <span class="badge bg-cyan text-dark me-1">#<?= htmlspecialchars($tag) ?></span>
      <?php endforeach; ?>
    </div>

    <p class="mt-3 text-muted" id="muted">
      <small>Posted by <strong><?= htmlspecialchars($question['username']) ?></strong>
      on <?= date('M d, Y', strtotime($question['date'])) ?></small>
    </p>
  </div>
</div>
<?php
// (Assumes session_start() is already called in parent file)

// ✅ Handle reply form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_text'], $_SESSION['user']['user_id'])) {
    $reply = trim($_POST['reply_text']);
    if ($reply !== '') {
        $replyStmt = $conn->prepare("INSERT INTO replies (question_id, user_id, reply_text) VALUES (?, ?, ?)");
        $replyStmt->execute([$qid, $_SESSION['user']['user_id'], $reply]);
    }
}

// ✅ Fetch replies
$replyFetch = $conn->prepare("
    SELECT r.reply_text, r.reply_date, u.username
    FROM replies r
    JOIN users u ON r.user_id = u.id
    WHERE r.question_id = ?
    ORDER BY r.reply_date ASC
");
$replyFetch->execute([$qid]);
$replies = $replyFetch->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- 🟦 Replies Section -->
<div class="container mt-4" style="max-width: 800px;">
  <h4 class="text-cyan">Replies</h4>

  <!-- Show replies -->
  <?php if ($replies): ?>
    <?php foreach ($replies as $r): ?>
      <div class="card bg-dark text-light mb-3 p-3 border border-cyan" style="box-shadow: 0 0 8px #00ffff55;">
        <p><?= nl2br(htmlspecialchars($r['reply_text'])) ?></p>
        <small class="text-muted" id="muted">
           <?= htmlspecialchars($r['username']) ?> on <?= date('M d, Y h:i A', strtotime($r['reply_date'])) ?>
        </small>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p class="text-muted" id="muted">No replies yet. Be the first!</p>
  <?php endif; ?>

  <!-- Show form only if logged in -->
  <?php if (isset($_SESSION['user']['user_id']))
: ?>
    <form method="POST" class="mt-4">
      <div class="mb-3">
        <label for="reply_text" class="form-label text-cyan">Your Reply:</label>
        <textarea class="form-control bg-dark text-white" id="reply_text" name="reply_text" rows="4" required></textarea>
      </div>
      <button type="submit" class="btn btn-outline-cyan">Post Reply</button>
    </form>
  <?php else: ?>
    <p class="text-warning">You must <a href="index.php?login=true" class="text-cyan">log in</a> to reply.</p>
  <?php endif; ?>
</div>


</body>
</html>
