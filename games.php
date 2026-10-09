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

    <h1 style="text-align: center; text-decoration: underline;">Games</h1>
    <div class="row">
        <?php
        // Chesk if the game file exist
        if (file_exists('games.csv')) {

            // opens the game csv as read
            $file = fopen('games.csv', 'r');

            // Makes an array of game info
            $gameInfo = [];
            // while not at the end of the file keep reading lines and adding it to the array
            while (!feof($file)) {
                $line = fgetcsv($file);
                if ($line === false) continue;
                $gameInfo[] = $line;
            }

            // takes each game in the array and makes a card to display all of its info
            foreach ($gameInfo as $game) {
                // I couldn't keep the quotes straight, so I threw it in a string
                $imagestr = '<img src="uploads/' . $game[4] . '" alt="Game Image" class="card-img-top" style="width: 100%; height: 10rem; object-fit: cover;">';
                $price = number_format($game[3], 2);
                echo ('
            <div class="col-md-4 mb-4">
                <div class="card"  style="width: 18rem; height:20rem; margin-left: 6em; margin-bottom: 10em; box-shadow: 10px 5px 5px #13293D;">
                    ' . $imagestr . '
                    <div class="card-body" style="background-color:#13293D;"> 
                        <h5 class="card-title" style="color: #EEE3AB">' . $game[1] . '</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item" style="background-color:#92140C; color: #EEE3AB">' . "ID: $game[0]" . '</li>
                                <li class="list-group-item" style="background-color:#92140C; color: #EEE3AB;">' . "Price: $price" . '</li>
                                <li class="list-group-item" style="background-color:#92140C; color: #EEE3AB;">' . "Console: $game[2]" . '</li>
                            </ul>
                    </div>

                </div>
            </div>');
            }
            fclose($file);
        } else {
            // If the file doesn't exist, it will display a message indicating that there are no games yet.
            echo '<p style="text-align:center;">There are no games yet :(</p>';
        }
        ?>
    </div>
    <!-- I'm actually so happy with how this page turned out. Is it perfect? No, but I worked hard on it and it is probably one of the best looking
     front ends I've made -->
</body>

</html>