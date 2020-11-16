<?

/*
      index.php fuer Hacking-Demos Admin-Bereich
      
      Aufgabe:
      	Der Administrationsbereich
      
		Benutzte Variablen:
			Aufruf:
           aktion   ggf. durchzufuehrende Aktion
           bname    ggf. der gewuenschte Benutzername
           rname    ggf. der Realname
           pass     ggf. das Passwort
           bid      ggf. die Benutzer-ID
						

 */

// Session starten
session_start();

// Include-Datei fuer die Konfigurationsdaten
require("../lib/config.inc");
require("../lib/dbconfig.inc");

// Include-Dateien fuer verschiedene Funktionen
require("../lib/mysqlfunktionen.inc");
   // Datenbankfunktionen
require("../lib/avatar.inc");
   // Funktionen fuer die Avatar-Nutzung

// Werte uebernehmen
$aktion  = $_REQUEST["aktion"];
$bname   = $_REQUEST["bname"];
$rname   = $_REQUEST["rname"];
$pass    = $_REQUEST["pass"];
$bid     = $_REQUEST["bid"];
$sprache = $_REQUEST["sprache"];

// Datei mit den Sprachanpassungen einbinden
if ($sprache != "") {
   include("../lib/sprache/".$sprache.".inc");
} else {
   include("../lib/sprache/".DEFAULTSPRACHE.".inc");
   $sprache = DEFAULTSPRACHE;
}


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <link rel="stylesheet" type="text/css" href="../style.css">
  <title>Hacking-Demos - Administrationsbereich</title>
</head>

<body>

<h1>Hacking-Demos - Administrationsbereich</h1>

<p>
Zum 
<a href="../backend/index.php?sprache=<? echo $sprache; ?>">Backend</a>
oder zur
<a href="../index.php?sprache=<? echo $sprache; ?>">Startseite</a>.
</p>

<p>
Zum 
<a href="../kontakt.php">Kontaktformular</a>,
zum
<a href="../kontakt-html5.php">Kontaktformular mit E-Mail-Prüfung</a> 
und zur
<a href="../empfehle.php">Empfehlungsfunktion</a>.
</p>
  
<p>
Zum
<a href="ping.php">ping-Skript</a>
zur Demonstration der OS-Command-Injection.
</p>


<hr>

<p> 
Dieses Skript ist ziemlich beschr&auml;nkt: Es k&ouml;nnen Benutzer in
der Datenbank gespeichert werden, einzelne Benutzer k&ouml;nnen
gel&ouml;scht und ihr Status (Autor/Leser sowie freigegeben/gesperrt)
gewechselt werden. 
</p>


<?

echo "Ausgew&auml;hlte Sprachdatei: ".$sprache."<br> \n";

echo $sprachtest;

// echo "Eingebundene Datei ist:[./lib/sprache/".$sprache.".inc]<br> \n";

$ok = verbindeMitDB(); 
	// Verbindung zur Datenbank herstellen: Ohne DB geht nichts


if ($ok) {
   switch ($aktion) {
   case "eintragen":
      // einen neuen Benutzer eintragen
      $query = "INSERT INTO benutzer_tabelle (Benutzer_Name, Real_Name, Passwort, istAutor, freigegeben)
                            values ('$bname', '$rname', '$pass', 1, 1)";
      if (!mysql_query($query)) {
         // MySQL-Fehler
         echo "Fehler beim Anlegen des neuen Benutzers: <br>\n";
         echo mysql_errno().": ".mysql_error()."<br> \n";
      } else {
         echo "Neuer Benutzer angelegt! <br>\n";
      }
   break;
   case "loeschen":
      // Benutzer loeschen
      if ($bid == 1) {
          echo "<b>Finger weg!</b><br>\n Der Default-Autor darf nicht gel&ouml;scht werden! <br>\n";
      } else { 
          $query = "DELETE FROM benutzer_tabelle WHERE Benutzer_ID=".$bid;   
          if (!mysql_query($query)) {
              // MySQL-Fehler
              echo "Fehler beim L&ouml;schen des Benutzers: <br>\n";
              echo mysql_errno().": ".mysql_error()."<br> \n";
           } else {
              echo "Benutzer gel&ouml;scht! <br>\n";
           }
       }
   break;
   case "wirdAutor":
      // Benutzer zum Autor machen
      $query = "UPDATE benutzer_tabelle SET istAutor=1 WHERE Benutzer_ID=".$bid;   
      if (!mysql_query($query)) {
         // MySQL-Fehler
         echo "Fehler beim Bef&ouml;rdern des Benutzers: <br>\n";
         echo mysql_errno().": ".mysql_error()."<br> \n";
      } else {
         echo "Der Benutzer ist nun Autor! <br>\n";
      }
   break;
   case "wirdBenutzer":
      // Autor zum Benutzer machen
      $query = "UPDATE benutzer_tabelle SET istAutor=0 WHERE Benutzer_ID=".$bid;   
      if (!mysql_query($query)) {
         // MySQL-Fehler
         echo "Fehler beim Degradieren des Autors: <br>\n";
         echo mysql_errno().": ".mysql_error()."<br> \n";
      } else {
         echo "Der Autor ist nun nur noch normale Benutzer! <br>\n";
      }
   break;
   case "freigeben":
      // Benutzer freigeben
      $query = "UPDATE benutzer_tabelle SET freigegeben=1 WHERE Benutzer_ID=".$bid;   
      if (!mysql_query($query)) {
         // MySQL-Fehler
         echo "Fehler beim freigeben des Benutzers: <br>\n";
         echo mysql_errno().": ".mysql_error()."<br> \n";
      } else {
         echo "Der Benutzer ist nun freigeben! <br>\n";
      }
   break;
   case "sperren":
      // Benutzer sperren
      $query = "UPDATE benutzer_tabelle SET freigegeben=0 WHERE Benutzer_ID=".$bid;   
      if (!mysql_query($query)) {
         // MySQL-Fehler
         echo "Fehler beim sperren des Benutzers: <br>\n";
         echo mysql_errno().": ".mysql_error()."<br> \n";
      } else {
         echo "Der Benutzer ist nun gesperrt! <br>\n";
      }
   break;
	default:
   break;
   }

   echo "<hr> \n\n<h2>Benutzer-Liste</h2>\n\n";
   echo "<p> \n";
   // Einfach ohne Ruecksicht auf Verluste alle Benutzer ausgeben
   $query = "SELECT * FROM benutzer_tabelle ORDER BY Benutzer_ID"; 
   $result = mysql_query($query);
   if (!$result) {
      // Fehler beim Ergebnis
      echo "Fehler beim Holen der Benutzertabelle: <br> \n";
      echo mysql_errno().": ".mysql_error()."<br> \n";
   } else {
      // Jetzt kann es losgehen... 
      echo "<table border=\"1\"  cellpadding=\"5\"> \n";
      echo "   <tr> \n";
      echo "      <td>Benutzer-ID</td>\n";
      echo "      <td>Benutzer-Name </td>\n";
      echo "      <td>Real-Name</td>\n";
      echo "      <td>Passwort</td>\n";
      echo "      <td>Autor?</td>\n";
      echo "      <td>Freigegeben?</td>\n";
      echo "      <td>Avatar</td>\n";
      echo "   </tr> \n";
      while ($row = mysql_fetch_array($result)) {
         $avatarBild = avatarFile($row["Benutzer_ID"]);
         if ($avatarBild == "") {
            // es gibt keinen Avatar
            $avatartag = "";
         } else {
            $avatartag = "<img src=\"../avatare/".$avatarBild."\">";
         }
         echo "   <tr> \n";
         echo "      <td>".$row["Benutzer_ID"]."</td> \n";
         echo "      <td>".$row["Benutzer_Name"]."  \n";
         echo "         <form action=\"index.php\" method=\"POST\"> \n";
         echo "            <input type=\"hidden\" name=\"sprache\" value=\"".$sprache."\"> \n";
         echo "            <input type=\"hidden\" name=\"bid\" value=\"".$row["Benutzer_ID"]."\"> \n";
         echo "            <input type=\"submit\" name=\"aktion\" value=\"loeschen\"> \n";
         echo "         </form> \n";
         echo "      </td> \n";
         echo "      <td>".$row["Real_Name"]."</td> \n";
         echo "      <td>".$row["Passwort"]."</td> \n";
         if ($row["istAutor"] == 1) {
             echo "      <td>ja \n";
             echo "         <form action=\"index.php\" method=\"POST\"> \n";
             echo "            <input type=\"hidden\" name=\"sprache\" value=\"".$sprache."\"> \n";
             echo "            <input type=\"hidden\" name=\"bid\" value=\"".$row["Benutzer_ID"]."\"> \n";
             echo "            <input type=\"submit\" name=\"aktion\" value=\"wirdBenutzer\"> \n";
             echo "         </form> \n";
             echo "      </td> \n";
         } else {
             echo "      <td>nein \n";
             echo "         <form action=\"index.php\" method=\"POST\"> \n";
             echo "            <input type=\"hidden\" name=\"sprache\" value=\"".$sprache."\"> \n";
             echo "            <input type=\"hidden\" name=\"bid\" value=\"".$row["Benutzer_ID"]."\"> \n";
             echo "            <input type=\"submit\" name=\"aktion\" value=\"wirdAutor\"> \n";
             echo "         </form> \n";
             echo "      </td> \n";
         }
         if ($row["freigegeben"] == 1) {
             echo "      <td>ja \n";
             echo "         <form action=\"index.php\" method=\"POST\"> \n";
             echo "            <input type=\"hidden\" name=\"sprache\" value=\"".$sprache."\"> \n";
             echo "            <input type=\"hidden\" name=\"bid\" value=\"".$row["Benutzer_ID"]."\"> \n";
             echo "            <input type=\"submit\" name=\"aktion\" value=\"sperren\"> \n";
             echo "         </form> \n";
             echo "      </td> \n";
         } else {
             echo "      <td>nein \n";
             echo "         <form action=\"index.php\" method=\"POST\"> \n";
             echo "            <input type=\"hidden\" name=\"sprache\" value=\"".$sprache."\"> \n";
             echo "            <input type=\"hidden\" name=\"bid\" value=\"".$row["Benutzer_ID"]."\"> \n";
             echo "            <input type=\"submit\" name=\"aktion\" value=\"freigeben\"> \n";
             echo "         </form> \n";
             echo "      </td> \n";
         }
         echo "      <td>".$avatartag."</td> \n";
         echo "   </tr> \n";
      }         
      echo "</table> \n";
   }
}

?>   

<hr> 

<h2>Neuen Autor eintragen</h2>

<form action="index.php" method="POST"> 
   <input type="hidden" name="sprache" value="<? echo $sprache; ?>">
   Benutzername: <br>
   <input type="text" name="bname" value="Benutzername" size="20" maxlength="250"> <br>
   Realname: <br>
   <input type="text" name="rname" value="Realname" size="20" maxlength="250"> <br>
   Passwort: <br>
   <input type="text" name="pass" value="Passwort" size="20" maxlength="250"> <br>
   <input type="submit" name="aktion" value="eintragen">
</form>

</body>
</html>