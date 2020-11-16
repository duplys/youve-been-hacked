<?

/*
      plugin2.php fuer Hacking-Demos Backend
      
      Aufgabe:
      	Dummy-Plugin-Seite
      
 */

// Session starten
session_start();


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet" type="text/css" href="../../style.css">
<title>Hacking-Demos - Backend-Plugin</title>
</head>

<body>


<font color="#ff0000">

<h1>Hacking-Demos - Dummy-Plugin</h1>

<p> 
Dieses Skript dient nur als Beispiel f&uuml;r ein zus&auml;tzliches
Skript, zu dem von <code>index.php</code> nur nach erfolgreicher 
Authentifizierung weitergeleitet wird. 
</p>

<p>
Damit hier &uuml;berhaupt irgend was passiert, werden die Werte der Cookies
ausgegeben:
</p>

<?
if ($_SESSION["benutzerStatus"] > 0) {
  echo "<p>\nSession-ID = ".$_COOKIE["PHPSESSID"]." \n</p>\n";
} else {
  echo "<p>\nSie sind nicht eingeloggt - wie sind Sie denn hier her gekommen?\n</p>\n";
}
?>   

<p>
<a href="../index.php">zur&uuml;ck</a>
</p>

</font>

</body>
</html>