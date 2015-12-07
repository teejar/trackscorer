<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title>Trackmania</title>
    <link href="./js/jquery-ui.css" rel="stylesheet">
    <style type="text/css">
    body {
    }
    #main {
        background-color: #565656;
        width : 600px;
        margin: auto;
        padding-left: 10px;
        padding-right: 10px;
        padding-bottom: 10px;
        padding-top: 10px;
    }
    input{
        width:35px;
    }
    #topScores{
        margin: 10px;
        padding: 5px;
        background-color: #383838;
        border-radius: 5px;
    }
    #top5table{
        position: relative;
        width: 100%;
    }
    #ntoptable{
        float: ;
        position: relative;
        width: 100%;
        display: none;
    }
    #trackLists{
        background-color: #565656;
    }
    #navbuttons{
        float: right;
    }
    .navbutton{
        background: linear-gradient(#c2b89d 10px, #8a8169 15px);

    }
    #navcontainer{
        padding: 10px;
    }
    #namedisplay{
        width: 300px;
        float: left;
        background-color: #383838;
        border-radius: 5px;
        padding: 2px;
    }
    .tracknumberdisplay{
        float: right;
    }
    .tracknumberinput{
    }

    span.navbutton{
        padding-bottom: 8px;
        height: 20px;
    }
    #navMore{
        margin-left: 30%;
        width: 200px;
        height: 30px;
        overflow: auto;
    }
    #navbar{
        margin-bottom:2px;
        height:25px;
    }
    #trackScoreTable{
        border-radius: 5px;
        width:100%;
        background-color: #383838;
        padding-bottom: 10px;
    }
    td{
        text-align: center;
    }
    .tralt{
        background-color: #565656;

    }
    .first {
                        background: linear-gradient(#f3c527 10px, #d6a706 15px);
        background-color: #d6a706;
    }
    .second {
                                background: linear-gradient(#c3cad4 10px,  #aeaeae 15px);
        background-color: #aeaeae;
    }
    .third {
                                        background: linear-gradient(#da9550 10px,  #ba5d00 15px);
        background-color: #ba5d00;
    }

    </style>
</head>

<body id="body">
    <div id="main">
<!--
TOP SCORES
-->
<div id="topScores"></div>
<div id="navcontainer">
    <div class="navbutton" id="navMore">Show more scores</div>
</div>
<!--
TRACKLIST
-->

<div id="navbar">
    <div id="namedisplay"></div><div id="navbuttons"><span class="navbutton" id="navPrevious">back</span><span class="navbutton" id="navNext">next</span></div>
</div>



<div id="trackScoreContainer">
    <table id="trackScoreTable">
    </table>
</div>

</div>
<!--
SCRIPT
-->
<script src="jquery-2.1.4.min.js"></script>
<script src="./js/jquery-ui.js"></script>
<script type="text/javascript">
var navnum = 1;
var navmoretoggle = false;
var once = false;
<?php include "getTracksPlayedQuery.php" ?> // var tracksPlayed

getTrackname(navnum);
getTrackScores(navnum);

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

};

function getTrackScores(navnum){
    $.post( "printTrackScores.php", { navnumber: navnum })
    .done(function( data ) {
        $("#trackScoreTable").html(data);
    });
}

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
};

$("#navMore").click(function(){
    if (navmoretoggle == true){
        navmoretoggle = false;
        $("#ntoptable").hide("blind",{direction: "up"});
        $(this).text("Show more scores");
    }
    else{
        navmoretoggle = true;
        $("#ntoptable").show("blind",{direction: "down"});
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
//UI
$( ".navbutton" ).button();
$( "#navMore" ).button();
</script>
</body>
</html>