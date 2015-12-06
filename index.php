<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title>Trackmania</title>
    <style type="text/css">
    body {
        background-color: #565656;
    }
    #trackLists{
        background-color: #565656;
    }
    #ntoptable {
        display: none;
    }
    </style>
</head>

<body id="body">
<!--
TOP SCORES
-->
<div id="topScores">

</div>
<div id="navMore">Show more scores</div>
<!--
TRACKLIST
-->
<div id="trackLists"></div>
<script src="jquery-2.1.4.min.js"></script>

<span id="trackName"></span><span id="navPrevious">back</span><span id="navNext">next</span>

<table id="trackScoreTable">
</table>
<script type="text/javascript">
var navnum = 1;
var navmoretoggle = false;
<?php include "getTracksPlayedQuery.php" ?> // var tracksPlayed

console.log(tracksPlayed);

getTrackname(navnum);
getTrackScores(navnum);

$.post("printTopScores.php", function(data){
    $("#topScores").append(data);
});

function getTrackname(navnum){
    $.post( "printTrackName.php", { navnumber: navnum })
    .done(function( data ) {
        $("#trackName").html(data);
    });
};

function getTrackScores(navnum){
    $.post( "printTrackScores.php", { navnumber: navnum })
    .done(function( data ) {
        $("#trackScoreTable").html(data);
    });
}



//click functionality
$("#navMore").click(function(){
    if (navmoretoggle == true){
        navmoretoggle = false;
        $("#ntoptable").hide("slow");
        $(this).text("Show more scores");
    }
    else{
        navmoretoggle = true;
        $("#ntoptable").show("slow");
        $(this).text("Hide Scores");
    }

});

$("#navNext").click(function(){
    navnum++;
    if (navnum >tracksPlayed) navnum = 1;
    getTrackname(navnum);
    getTrackScores(navnum);
});

$("#navPrevious").click(function(){
    navnum--;
    if (navnum==0) navnum = tracksPlayed;
    getTrackname(navnum);
    getTrackScores(navnum);
});



/*
var pointArray = [5, 4, 3, 2, 1, 0];
var num = 1;
var numOfRecords = records_array.length;
var numOftracks = tracks_array.length;
            var topScoresArrayByTrack = []; //store scores by track
            var topScoresArrayByTrackNumber = 1;

            function backButton() {
                document.getElementById(
                    "trackLists").innerHTML = "";
                if (num == 1) {
                    num = numOftracks + 1;
                }
                if (num > 1) {
                    num--;
                }

                DisplayRecords();
            }
            function forwardButton()
            {
                document.getElementById(
                    "trackLists").innerHTML = "";
                if (num == numOftracks) {
                    num = 1;
                }
                else num++;

                DisplayRecords();
            }
            function DisplayRecords() {
                var tnum = num - 1;
                document.getElementById(
                    "trackLists").innerHTML += "Track: ";
                document.getElementById(
                    "trackLists").innerHTML += tracks_array[tnum].trackNum + " / " + numOftracks + " - ";
                document.getElementById(
                    "trackLists").innerHTML += tracks_array[tnum].trackName;
                document.getElementById(
                    "trackLists").innerHTML += "  ";
                document.getElementById(
                    "trackLists").innerHTML += "<button type='button' onClick='backButton()'>back</button>";
                document.getElementById(
                    "trackLists").innerHTML += "<button type='button' onClick='forwardButton()'>forward</button>";
                document.getElementById(
                    "trackLists").innerHTML += " <br>";
                for (var i = 0; i < 848; i++)
                {
                    if (records_array[i].trackNum == num)
                    {
                        var ms = records_array[i].playerTime,
                        minute = (ms / 1000 / 60) << 0,
                        second = Math.floor((
                            ms / 1000) % 60),
                        millisecond = ms % 1000;
                        var milsec = millisecond.toString();
                        if (milsec.length < 3) {
                            milsec = "0" + milsec;
                        }
                        var displaytime = minute + ":" + second + "," + milsec;
                        document.getElementById(
                            "trackLists").innerHTML += records_array[i].playerName;
                        document.getElementById(
                            "trackLists").innerHTML += " - Time: ";
                        document.getElementById(
                            "trackLists").innerHTML += displaytime;
                        document.getElementById(
                            "trackLists").innerHTML += "<br>";
                    }
                }
            }
            DisplayRecords();

            */
            </script>

        </body>
        </html>