<!-- 🟦 Trending Hashtags Sidebar -->

<?php
include('server/db.php');
include('main/common.php');

?>
<div class="container mb-4">
  <h4 class="text-cyan">Trending Categories</h4>
  <div class="d-flex flex-wrap">
    <?php
    $trendStmt = $conn->prepare("
        SELECT h.tag_name, COUNT(qh.hastag_id) AS total
        FROM question_hastag qh
        JOIN hastag h ON qh.hastag_id = h.id
        GROUP BY qh.hastag_id
        ORDER BY total DESC
        LIMIT 10
    ");
    $trendStmt->execute();
    $trendingTags = $trendStmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <?php foreach ($trendingTags as $tag): ?>
      <a href="../index.php?tag=<?= urlencode($tag['tag_name']) ?>" class="badge bg-cyan text-dark me-2 mb-2 p-2" style="text-decoration:none;">
        #<?= htmlspecialchars($tag['tag_name']) ?> (<?= $tag['total'] ?>)
      </a>
    <?php endforeach; ?>
  </div>
</div>
