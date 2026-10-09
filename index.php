<?php
// regex code for game id
$regex = "GAME-\d{4}$";
// errors array
$errors = [];

// Waits until the submit button has been pressed
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Gathering the info from the form
    $inventoryCode = trim($_POST['inventoryCode'] ?? '');
    $gameName = trim($_POST['gameName'] ?? '');
    $console = trim($_POST['console'] ?? '');
    $price = trim($_POST['price'] ?? '');

    // Checking if inventory code is blank or in a different pattern
    if ($inventoryCode == '') {
        $errors['inventoryCode'] = 'Inventory code is required';
    } elseif (!preg_match("/$regex/", $inventoryCode)) {
        $errors['inventoryCode'] = 'Inventory code must be in the format GAME-1234';
    }

    // checks the name has been filled
    if ($gameName == '') {
        $errors['gameName'] = 'Game name is required';
    }
    // checks console has been slected
    if ($console == '' || $console === "Choose...") {
        $errors['console'] = 'Select a console';
    }
    // price has been entered
    if ($price == '') {
        $errors['price'] = 'Price is required';
    } elseif (!is_numeric($price) || $price < 0) {
        $errors['price'] = 'Price must be a positive number (ex: 3.99)';
    }
    // checks that a game image file has been uploaded
    if (!isset($_FILES['gameImage']) || $_FILES['gameImage']['error'] == UPLOAD_ERR_NO_FILE) {
        $errors['gameImage'] = 'Game image is required';
    } else if ($_FILES['gameImage']['error'] !== UPLOAD_ERR_OK) {
        $errors['gameImage'] = 'the image upload was unsuccessful';
    }
    // Saves the image if there are no errors
    if (empty($errors)) {
        $imageName = $_FILES['gameImage']['name'];
        $imageTmpName = $_FILES['gameImage']['tmp_name'];

        $extension = pathinfo($_FILES['gameImage']['name'], PATHINFO_EXTENSION);

        // creating a unique filename for the image using a timestamp
        $filename = (int)(microtime(true) * 1000) . '.' . $extension;

        // creating the path to the uploads folder
        $uploadPath = 'uploads/' . $filename;

        // moves image to uploads folder
        move_uploaded_file(
            $_FILES['gameImage']['tmp_name'],
            $uploadPath
        );

        // adding the game to the csv file
        $file = fopen('games.csv', 'a');

        fputcsv($file, [$inventoryCode, $gameName, $console, $price, $filename]);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</head>

<body style="background-color: #896A67;">
    <!-- Nav bar -->
    <?php include 'includes/nav.php'; ?>

    <!-- Sweet sweet bottstrap -->
    <div class="container text-center">
        <div class="row align-items-start">
            <div class="col-12">
                <h1>Video Game Store</h1>
                <?php
                // Gives success message if the form was submitted and there were no errors
                if ($_POST) {
                    if (!$errors) {
                        echo "<p style=\"font-weight: bold; color: #92140C; padding-top: 2em; font-size:2.5em;\">Game entered successfully!</p>";
                    }
                }
                ?>
            </div>
        </div>

    </div>
    <div class="col" style="background-color: #EEE3AB; ">

    <!-- Form -->
        <form method="post" enctype="multipart/form-data" class="row g-3" style="margin: 1.5em; padding: 2em;">
        <!-- We <3 bootstrap-->
            <div class="col-md-6">
                <label for="inventoryCode" class="form-label">Inventory Code</label>
                <input type="text" class="form-control" id="inventoryCode" name="inventoryCode" placeholder="GAME-1234" value="<?= htmlspecialchars($_POST['inventoryCode'] ?? '') ?>" />
                <?php // shows any error that have to do with the field its under. I only commenting this once becasue thats too much typing. The pattern contiunes for every feild in the form
                if (isset($errors['inventoryCode'])) { 
                    echo '<p style="color: #92140C; padding-top: 1.5em; font-weight: bold; font-size: 1.2em;">' . $errors['inventoryCode'] . '</p>';
                } ?>
            </div>
            <div class="col-md-6">
                <label for="price" class="form-label">Price</label>
                <input type="text" class="form-control" id="price" name="price" placeholder="Enter price" value="<?= htmlspecialchars($_POST['price'] ?? '') ?>" step="0.01" min="0">
                <?php if (isset($errors['price'])) {
                    echo '<p style="color: #92140C; padding-top:1.5em; font-weight: bold; font-size: 1.2em;">' . $errors['price'] . '</p>';
                } ?>
            </div>
            <div class="col-12">
                <label for="gameName" class="form-label">Video Game Name</label>
                <input type="text" class="form-control" id="gameName" name="gameName" placeholder="Enter game name" value="<?= htmlspecialchars($_POST['gameName'] ?? '') ?>">
                <?php if (isset($errors['gameName'])) {
                    echo '<p style="color: #92140C; padding-top:1.5em; font-weight: bold; font-size: 1.2em;">' . $errors['gameName'] . '</p>';
                } ?>
            </div>
            <div class="col-md-6">
                <label for="gameImage" class="form-label">Game Image</label>
                <input type="file" class="form-control" id="gameImage" name="gameImage">
                <?php if (isset($errors['gameImage'])) {
                    echo '<p style="color: #92140C; padding-top:1.5em; font-weight:bold; font-size: 1.2em;">' . $errors['gameImage'] . '</p>';
                }
                ?>
            </div>
            <div class="col-md-4">
                <label for="console" class="form-label">Console</label>
                <select id="console" class="form-select" name="console">
                    <option>Choose...</option>
                    <option>PlayStation</option>
                    <option>Xbox</option>
                    <option>PC</option>
                </select>
                <?php if (isset($errors['console'])) {
                    echo '<p style="color: #92140C; padding-top:1.5em; font-weight: bold; font-size: 1.2em;">' . $errors['console'] . '</p>';
                } ?>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>

    </div>
    </div>
</body>
<!-- By far not the worst front end design I've ever made. Not great, but I didn't have a whole lot of content to work with. I hope it is up to your standards. Graphic design is not my passion -->
</html>