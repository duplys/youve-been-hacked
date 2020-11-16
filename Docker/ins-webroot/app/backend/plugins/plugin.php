<?

/*
      plugin.php fuer Hacking-Demos Backend
      
      Aufgabe:
      	Dummy-Plugin-Seite
      
 */




?>

<font color="#ff0000">

<h1>Hacking-Demos - Eingebundenes Dummy-Plugin</h1>

<p> 
Dieses Skript dient nur als Beispiel f&uuml;r ein zus&auml;tzliches
Skript, das von index.php nur nach erfolgreicher Authentifizierung
eingebunden wird. 
</p>

<p>
Damit hier &uuml;berhaupt irgend was passiert, werden die Werte der Session-Variable
ausgegeben:
</p>

<?
if ($_SESSION["benutzerStatus"] > 0) {
  echo "<p>\nSession-ID = ".$_COOKIE["PHPSESSID"]." \n</p>\n";
} else {
  echo "<p>\nSie sind nicht eingeloggt - wie sind Sie denn hier her gekommen?\n</p>\n";
} 
?>   

</font>





   
