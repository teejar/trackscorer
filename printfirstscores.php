       <?php
       include "scorequery.php";

        function printMoreScores($a,$b,$c,$d){
            echo "<table id='top5table'>";
            for ($i=0;$i<count($d);$i++){
                $printScoreKey = $a[$i];
                $convertString = $b[$printScoreKey];
                $printScoreNick = $c->toHTML($convertString, false, true);
                                if ($i==5){
                                        echo "</table>";
                                        echo "<table id='ntoptable'>";
                }
                echo "<tr>";
                echo "<td>".$printScoreNick."</td><td>".$d[$printScoreKey]."</td>";
                echo "</tr>";

            }
            echo "</table>";
        }
        printMoreScores($topScoresArrayKeys, $playerArray, $cp, $topScoresArray);
        ?>