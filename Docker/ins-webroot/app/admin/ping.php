<?

/*
      ping.php fuer Hacking-Demos Backend
      
      Aufgabe:
      	Demo fuer OS-Command-Injection
      
		Benutzte Variablen:
			Aufruf:
           host      der Host, der ange'ping't werden soll
           aktion    ping, wenn es losgehen soll
						
 */

// Parameter uebernehmen
$host = $_REQUEST["host"];


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet" type="text/css" href="../style.css">
<title>Hacking-Demos - OS-Command-Injection-Demo</title>
</head>

<body>
  
<h1>Hacking-Demos - OS-Command-Injection-Demo</h1>

<h2>Das Eingabeformular:</h2>

<form action="ping.php" method="post">
   Hostname oder IP-Adresse: <br>
   <input type="text" name="host" value="172.16.177.131" size="50"><br>
   <input type="submit" name="aktion" value="ping">
</form>

<?
if ($_REQUEST["aktion"] == "ping") {
   echo "\n<h2>Der Parameter:</h2> \n";
   echo "\n<p> \n".$host." \n</p>\n";
   echo "\n<h2>Der damit erzeugte system()-Aufruf:</h2>\n";
   echo "\n<pre><code>system(ping -c 5 ".$host.");</code></pre>\n";
   echo "\n<h2>Und das Ergebnis:</h2> \n";
   echo "\n<pre> \n";
   if (!system("ping -c 5 ".$host, $return) ) {
      echo "<p>Es ist ein Fehler aufgetreten!<br> \n";
      echo "R&uuml;ckgabewert: ".$return."</p> \n";
   }
   echo "\n</pre> \n";
}
?>

<p>
<a href="index.php">Zur&uuml;ck</a>
zum Admin-Bereich.
</p>

</body>
</html>