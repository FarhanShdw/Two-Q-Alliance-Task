<?php
session_start();
include 'db.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit;
}

// Fetch companies from the database
$query = $pdo->query("SELECT * FROM companies");
$companies = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Company Management</h1>
            <a href="logout.php" class="logout-btn">Logout</a>
        </header>
        <div class="actions">
            <a href="create.php" class="add-btn">Add New Company</a>
        </div>
        <table class="company-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Logo</th>
                    <th>Website</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($companies as $company): ?>
                <tr>
                    <td><?= htmlspecialchars($company['name']); ?></td>
                    <td><?= htmlspecialchars($company['email']); ?></td>
                    <td>
                        <?php if ($company['logo']): ?>
                            <img src="uploads/<?= htmlspecialchars($company['logo']); ?>" width="50" alt="Logo">
                        <?php else: ?>
                            <span class="no-logo">No Logo</span>
                        <?php endif; ?>
                    </td>
                    <td><a href="http://<?= htmlspecialchars($company['website']); ?>" target="_blank"><?= htmlspecialchars($company['website']); ?></a></td>
                    <td>
                        <div class="button-group">
                            <a href="edit.php?id=<?= $company['id']; ?>" class="edit-btn">Edit</a>
                            <a href="delete.php?id=<?= $company['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this company?');">Delete</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
