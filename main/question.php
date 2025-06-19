<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php
    include('main/common.php');
    include('server/db.php');
    ?>
    <style>
        body{
            color:#fff;
            background:#111;
        }
        h1{
            text-align:center;
            color:cyan;
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
#muted{
  color:#A8A8A8!important;
}
    </style>
</head>
<body>
    <h1> Queries</h1>

<?php


$stmt = $conn->prepare("
    SELECT q.id, q.title, q.date, u.username
    FROM question q
    JOIN users u ON q.user_id = u.id
    ORDER BY q.date DESC
");
$stmt->execute();
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['tag'])) {
    $tag = $_GET['tag'];

    // Fetch only filtered questions
    $stmt = $conn->prepare("
        SELECT q.id, q.title, q.date, u.username
        FROM question q
        JOIN users u ON q.user_id = u.id
        JOIN question_hastag qh ON q.id = qh.ques_id
        JOIN hastag h ON qh.hastag_id = h.id
        WHERE h.tag_name = ?
        ORDER BY q.date DESC
    ");
    $stmt->execute([$tag]);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<div class='container text-cyan mb-3'>
            <h5>Showing results for #".htmlspecialchars($tag)."</h5>
            <a href='index.php' class='text-white'>&larr; Back to all queries</a>
          </div>";

} 

 elseif (isset($_GET['search'])) {
    $search = '%' . $_GET['search'] . '%'; // Add wildcards for LIKE

    $stmt = $conn->prepare("
        SELECT q.id, q.title, q.date, u.username
        FROM question q
        JOIN users u ON q.user_id = u.id
        WHERE q.title LIKE ?
        ORDER BY q.date DESC
    ");
    $stmt->execute([$search]);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<div class='container text-cyan mb-3'><h5>Search results for: " . htmlspecialchars($_GET['search']) . "</h5></div>";
    if (empty($questions)) {
    echo "<div class='container text-warning'>No results found.</div>";
}

}


else {
    // Fetch all questions
    $stmt = $conn->prepare("
        SELECT q.id, q.title, q.date, u.username
        FROM question q
        JOIN users u ON q.user_id = u.id
        ORDER BY q.date DESC
    ");
    $stmt->execute();
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
}




?>
<div class="container mt-5">
  <?php foreach ($questions as $q): ?>
    <div class="card question-card text-white mb-4 mx-auto" style="max-width: 800px;">
      <div class="card-body">
        <h5 class="card-title mb-2">
          <a href="index.php?q-id=<?= $q['id'] ?>" class="text-decoration-none text-cyan">
            <?= htmlspecialchars($q['title']) ?>
          </a>
        </h5>

        <!-- Hashtags -->
        <div class="mb-2">
          <?php
        $stmt = $conn->prepare("
    SELECT h.tag_name FROM question_hastag qh
    JOIN hastag h ON qh.hastag_id = h.id
    WHERE qh.ques_id = ?
");
$stmt->execute([$q['id']]);
$hashtags = $stmt->fetchAll(PDO::FETCH_COLUMN);

          ?>

          <?php foreach ($hashtags as $tag): ?>
            <span class="badge bg-cyan text-dark me-1">#<?= htmlspecialchars($tag) ?></span>
          <?php endforeach; ?>
        </div>

        <p class="card-text">
          <small class="text-muted" id="muted">
            Posted by <strong><?= htmlspecialchars($q['username']) ?></strong>
            on <?= date('M d, Y', strtotime($q['date'])) ?>
          </small>
        </p>
      </div>
    </div>
  <?php endforeach; ?>
</div>


</body>
</html>


