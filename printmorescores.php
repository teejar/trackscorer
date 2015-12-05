       <?php
       include "scorequery.php";

        function printMoreScores($a,$b,$c,$d){
            for ($i=5;$i<count($d);$i++){
                $printScoreKey = $a[$i];
                $convertString = $b[$printScoreKey];
                $printScoreNick = $c->toHTML($convertString, false, true);
                echo "<tr>";
                echo "<td>".$printScoreNick."</td><td>".$d[$printScoreKey]."</td>";
                echo "</tr>";
            }
        }
        printMoreScores($topScoresArrayKeys, $playerArray, $cp, $topScoresArray);
        ?>