<?php
include 'db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM companies WHERE id = ?");
$stmt->execute([$id]);
$company = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $website = $_POST['website'];
    $logo = $company['logo'];

    // Check if a new logo was uploaded
    if ($_FILES['logo']['size'] > 0) {
        // Get image dimensions
        list($width, $height) = getimagesize($_FILES['logo']['tmp_name']);

        // Validate the image dimensions (minimum 100x100 pixels)
        if ($width >= 100 && $height >= 100) {
            // Delete the old logo if it exists
            if ($logo) {
                unlink("uploads/$logo");
            }
            // Save the new logo
            $logo = time() . '_' . $_FILES['logo']['name'];
            move_uploaded_file($_FILES['logo']['tmp_name'], "uploads/$logo");
        } else {
            // If the image is too small, set an error message
            $error = "Logo must be at least 100x100 pixels.";
        }
    }

    if (!isset($error)) { // Only update in the database if there's no error
        $stmt = $pdo->prepare("UPDATE companies SET name = ?, email = ?, logo = ?, website = ? WHERE id = ?");
        $stmt->execute([$name, $email, $logo, $website, $id]);
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Company</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header>Edit Company</header>
        <?php if (isset($error)): ?>
            <p class="error"><?= $error; ?></p> <!-- Display error message -->
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="name" value="<?= htmlspecialchars($company['name']); ?>" required>
            <input type="email" name="email" value="<?= htmlspecialchars($company['email']); ?>">
            <input type="file" name="logo" accept="image/*">
            <input type="url" name="website" value="<?= htmlspecialchars($company['website']); ?>">
            <button type="submit">Update</button>
        </form>
    </div>
</body>
</html>
