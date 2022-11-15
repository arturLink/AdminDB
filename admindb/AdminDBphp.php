<?php
require_once ('connect.php');
global $yhendus;

if(isset($_REQUEST["kustuta"])){
    $paring=$yhendus->prepare("DELETE FROM players WHERE playerId=?");
    $paring->bind_param("i", $_REQUEST["kustuta"]);
    $paring->execute();
}
// andmete lisamine tabelisse
if(isset($_REQUEST['lisamisvorm']) && !empty($_REQUEST["nimi"])){
    $paring=$yhendus->prepare(
        "INSERT INTO players(name, yearsOld, subscription) Values (?,?,?)"
    );
    $paring->bind_param("sis", $_REQUEST["nimi"], $_REQUEST["vanus"], $_REQUEST["subs"]);
    //"s" - string, $_REQUEST["nimi"] - tekstkasti nimega nimi pöördumine
    //sdi, s-string, d-double, i-integer
    $paring->execute();
    //aadressi ribas eemaldatakse php käsk
    header("Location: $_SERVER[PHP_SELF]");

}
// kustutamine tabelist





?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>AdminDB</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<h1>AdminDB</h1>
<div id="meny">
    <ul>
    <?php
    //näitab loomade loetelu tabelist loomad
    $paring=$yhendus->prepare("SELECT playerId, name, subscription FROM players");
    $paring->bind_result($playerId,$name, $subscription);
    $paring->execute();

        while($paring->fetch()) {
        echo "<li><a href='?id=$playerId'>$name</a></li>";
        }
        echo "</ul>";
        echo "<a href='?lisaloom=jah'>Lisa 'Player'</a>";
    ?>

</div>
<div id="sisu">

    <?php


    if(isset($_REQUEST["id"])){
        $paring=$yhendus->prepare("SELECT name, yearsOld, subscription FROM players WHERE playerId=?");
        $paring->bind_param("i", $_REQUEST["id"]);
        //? küsimärki asemel aadressiribalt tuleb id
        $paring->bind_result($name, $yearsOld, $subscription);
        $paring->execute();
        if($paring->fetch()){
            echo "<div><strong>".htmlspecialchars($name).",</strong> ";
            echo htmlspecialchars($yearsOld). " konto aastat vana.";
            echo "<br>Subscription: ".htmlspecialchars($subscription);
            echo "</div>";

        }

        echo "<a href='$_SERVER[PHP_SELF]?kustuta=$playerId'>Kustuta</a>";
    }



        if(isset($_REQUEST["lisaloom"])){
            ?>
    <h2>Uue 'Player' lisamine</h2>
    <form name="uusloom" method="post" action="?">
    <input type="hidden" name="lisamisvorm" value="jah">
    <input type="text" name="nimi" placeholder="Player nimi">
    <br>
    <input type="number" name="vanus"  max="30" placeholder="Konto vanus">
    <br>
    <input type="text" name="subs" placeholder="Subscription">
    <br>
    <input type="submit" value="OK">
    </form>
<?php
        }
        else {
            echo " <h3>Siia tuleb 'Player' info...</h3>";
        }
    $yhendus->close();
    ?>
</div>
</body>
</html>
