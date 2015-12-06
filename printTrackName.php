    <?php
    include "trackquery.php";

    $x = $_POST['navnumber'] -1;
    $y = $_POST['navnumber'];

    echo $tracksArray[$x]["trackName"]. $y. " / ". count($tracksArray);
    ?>