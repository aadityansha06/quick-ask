<?php
session_start(); // ✅ VERY IMPORTANT

include(__DIR__ . '/../server/db.php');
include('common.php');

if (!isset($_SESSION['user']['user_id'])) {
    header("Location: ../main/index.php");
    exit;
}

$userId = $_SESSION['user']['user_id'];

if (isset($_POST['del-id'])) {
    $questionId = $_POST['del-id'];

    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Verify question belongs to user
        $stmt = $conn->prepare("SELECT user_id FROM question WHERE id = ?");
        $stmt->execute([$questionId]);
        $ownerId = $stmt->fetchColumn();

        if ($ownerId != $userId) {
            die("Unauthorized delete.");
        }

        // Delete replies
        $stmt = $conn->prepare("DELETE FROM replies WHERE question_id = ?");
        $stmt->execute([$questionId]);

        // Delete hashtag mappings
        $stmt = $conn->prepare("DELETE FROM question_hastag WHERE ques_id = ?");
        $stmt->execute([$questionId]);

        // Delete question
        $stmt = $conn->prepare("DELETE FROM question WHERE id = ? AND user_id = ?");
        $stmt->execute([$questionId, $userId]);

    } catch (PDOException $e) {
        echo "Error deleting question: " . $e->getMessage();
        exit;
    }

    // Redirect to account page
  // Redirect to account page
header("Location: /index.php?account=true");
exit;


} else {
    echo "Invalid request.";
    exit;
}
