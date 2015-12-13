<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title>Trackmania tournament scores</title>
    <link href="./js/jquery-ui.css" rel="stylesheet">
</head>
<body id="body">
    <div id="style"></div>
    <div class="parallax">
        <div class="parallax_layer parallax_layer--background">
            <img id="imgone" src="bg.jpg"></img>
        </div>
        <div class="parallax_layer parallax_layer--main">
            <div id="main">
<!--
TOP SCORES
-->
<div id="maintext"><img src="tournament_text.png"></img></div>
<div id="toptext">Tournament Winners</div>
<div id="topScores"></div>
<div id="navcontainer">
    <button class="navbutton" id="navMore">Show more scores</button>
</div>
<!--
TRACKLIST
-->
<div id="tracktext">Track Winners</div>
<div id="navbar">
    <div id="namedisplay"></div><div id="navbuttons"><button class="navbutton" id="navPrevious"><span> back</span></button><button class="navbutton" id="navNext"><span>next </span></button></div>
</div>
<div id="trackScoreContainer">
    <table id="trackScoreTable">
    </table>
</div>
</div>
</div>
</div>
<!--
SCRIPT
-->
<script src="./js/jquery-2.1.4.min.js"></script>
<script src="./js/jquery-ui.js"></script>

<script type="text/javascript">
var navnum = 1;
var navmoretoggle = false;
var once = false;
<?php include "getTracksPlayedQuery.php" ?> // var tracksPlayed

var isChromium = window.chrome,
vendorName = window.navigator.vendor,
isOpera = window.navigator.userAgent.indexOf("OPR") > -1,
isIEedge = window.navigator.userAgent.indexOf("Edge") > -1;
if(isChromium !== null && isChromium !== undefined && vendorName === "Google Inc." && isOpera == false && isIEedge == false) {
    $("#style").load("trackScorerStylechrome.html");
} else { 
   // not Google chrome 
   $("#style").load("trackScorerStylehtml.html");
}
   $.post("printTopScores.php", function(data){
    $("#topScores").append(data);
});

   function getTrackname(navnum){
    $.post( "printTrackName.php", { navnumber: navnum })
    .done(function( data ) {
        $("#namedisplay").html(data);
        if (once !== true);
        trackinputfuntionality();
        once = true;
    });
}
getTrackname(navnum);

function getTrackScores(navnum){
    $.post( "printTrackScores.php", { navnumber: navnum })
    .done(function( data ) {
        $("#trackScoreTable").html(data);
    });
}
getTrackScores(navnum);

//click functionality
function trackinputfuntionality(){
    $(".tracknumberdisplay").change(function(){
        var input = $("input").val();
        if ($("input").val()>tracksPlayed) input=tracksPlayed;
        if ($("input").val()<=0) input=1;
        navnum = input;
        getTrackname(navnum);
        getTrackScores(navnum);

    });
}
$("#navMore").click(function(){
    if (navmoretoggle == true){
        navmoretoggle = false;
        $("#ntoptable").hide();
        $(this).text("Show more scores");
    }
    else{
        navmoretoggle = true;
        $("#ntoptable").show("blind",{direction: "up"},"slow");
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

</script>
</body>
</html>