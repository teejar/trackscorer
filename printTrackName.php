    <?php
    include "trackquery.php";

    $x = $_POST['navnumber'] -1;
    $y = $_POST['navnumber'];

    echo "<span class='trackNameDisplay'>".$tracksArray[$x]["trackName"]."</span><span class='tracknumberdisplay'><input type='number' size='4' maxlength='3' value='".$y."' min='1' max='".count($tracksArray)."'>/ ".count($tracksArray)."</span>";
    ?>