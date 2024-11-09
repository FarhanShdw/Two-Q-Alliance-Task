<?php
include 'db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT logo FROM companies WHERE id = ?");
$stmt->execute([$id]);
$company = $stmt->fetch();

if ($company && $company['logo']) {
    unlink("uploads/" . $company['logo']);
}

$stmt = $pdo->prepare("DELETE FROM companies WHERE id = ?");
$stmt->execute([$id]);

header("Location: index.php");
?>
    