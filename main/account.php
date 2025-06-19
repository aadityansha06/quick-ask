<?php
include(__DIR__ . '/../server/db.php');
include(__DIR__ . '/../main/common.php');


$user_id = $_SESSION['user']['user_id'];
$username = $_SESSION['user']['username'];
$email = $_SESSION['user']['email'];

// Fetch user questions
$stmt = $conn->prepare("
    SELECT q.id, q.title, q.date 
    FROM question q 
    WHERE q.user_id = ?
    ORDER BY q.date DESC
");
$stmt->execute([$user_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Account</title>
  <style>
    body {
      background: #000;
      color: #fff;
      font-family: sans-serif;
    }
    .container {
      max-width: 800px;
      margin: auto;
      padding: 2rem;
    }
    h1, h3 {
      color: cyan;
    }
    .question-box {
      background: #1a1a1a;
      padding: 1rem;
      margin-bottom: 1rem;
      border-left: 3px solid cyan;
    }
    a {
      color: #00ffff;
      text-decoration: none !important;
      margin-right: 1rem;
    }
    a:hover {
      text-decoration: underline;
    }
    .actions {
      margin-top: 0.5rem;
    }
  </style>
</head>
<body>
<div class="container">
  <h1>My Account</h1>
  <p><strong>Username:</strong> <?= htmlspecialchars($username) ?></p>
  <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>

  <hr>

  <h3>Your Posted Questions</h3>

  <?php if ($questions): ?>
    <?php foreach ($questions as $q): ?>
      <div class="question-box">
        <h4><?= htmlspecialchars($q['title']) ?></h4>
        <p><small>Posted on <?= date('M d, Y', strtotime($q['date'])) ?></small></p>
        <div class="actions">
          <a href="index.php?q-id=<?= $q['id'] ?>">View</a>
          
     <form method="POST" action="/main/delete.php" onsubmit="return confirm('Are you sure you want to delete this question?');" style="display:inline;">

    <input type="hidden" name="del-id" value="<?= $q['id'] ?>">
    <button type="submit" style="background:none;border:none;color:#00ffff;cursor:pointer;">Delete</button>
</form>


        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <p>You haven't posted any questions yet.</p>
  <?php endif; ?>
</div>
</body>
</html>
