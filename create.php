<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $website = $_POST['website'];
    $logo = null;

    // Check if a file was uploaded
    if ($_FILES['logo']['size'] > 0) {
        // Get image dimensions
        list($width, $height) = getimagesize($_FILES['logo']['tmp_name']);

        // Validate the image dimensions (minimum 100x100 pixels)
        if ($width >= 100 && $height >= 100) {
            // Generate a unique file name and save the file
            $logo = time() . '_' . $_FILES['logo']['name'];
            move_uploaded_file($_FILES['logo']['tmp_name'], "uploads/$logo");
        } else {
            // If the image is too small, set an error message
            $error = "Logo must be at least 100x100 pixels.";
        }
    }

    if (!isset($error)) { // Only save to the database if there's no error
        $stmt = $pdo->prepare("INSERT INTO companies (name, email, logo, website) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $logo, $website]);
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Company</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header>Add Company</header>
        <?php if (isset($error)): ?>
            <p class="error"><?= $error; ?></p> <!-- Display error message -->
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email">
            <input type="file" name="logo" accept="image/*">
            <input type="url" name="website" placeholder="Website">
            <button type="submit">Save</button>
        </form>
    </div>
</body>
</html>
