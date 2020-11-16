<?

/*
      index.php fuer Hacking-Demos
      
      Aufgabe:
      	Startseite
      
		Benutzte Variablen:
			Aufruf:
           aktion   ggf. durchzufuehrende Aktion
           id       ggf. ID, fuer die die Aktion gilt
           titel    ggf. einzutragender Titel
           text     ggf. einzutragender Text
           benutzer ggf. neuer Benutzername
           real     ggf. dessen Realname
           passwort ggf. das Passwort
           nach.    ggf. Suchbegriff
           sprache. ggf. die gewaehlte Sprache

 */

// Session starten
session_start();

// Include-Datei fuer die Konfigurationsdaten
require("./lib/config.inc");
require("./lib/dbconfig.inc");

// Include-Dateien fuer verschiedene Funktionen
require("./lib/mysqlfunktionen.inc");
   // Datenbankfunktionen
require("./lib/avatar.inc");
   // Funktionen fuer die Avatar-Nutzung

// Werte uebernehmen
$aktion   = $_REQUEST["aktion"];
$id       = $_REQUEST["id"];
$titel    = $_REQUEST["titel"];
$text     = $_REQUEST["text"];
$benutzer = $_REQUEST["benutzer"];
$real     = $_REQUEST["real"];
$passwort = $_REQUEST["passwort"];
$sprache  = $_REQUEST["sprache"];
$nach     = $_REQUEST["nach"];

// Datei mit den Sprachanpassungen einbinden
if ($sprache != "") {
   include("./lib/sprache/".$sprache.".inc");
} else {
   include("./lib/sprache/".DEFAULTSPRACHE.".inc");
   $sprache = DEFAULTSPRACHE;
}

if ($_REQUEST["aktion"] == "ausloggen") {
   // Loeschen aller Session-Variablen.
   $_SESSION = array();

   // Session-Cookie loeschen
   if (ini_get("session.use_cookies")) {
       $params = session_get_cookie_params();
       setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
   }
   // Loeschen der Session
   session_destroy();
   $ausgeloggt = true;
}


// Dieser Block wird nur fuer die Demonstration der Remote File Inclusion benoetigt.
// include-Anweisung zur Template-Auswahl
if ( ($_COOKIE["template"]=="template1") OR ($_COOKIE["template"]=="template2")) {
    $rfi = $_COOKIE["template"];
}
if ($rfi != "") {
   include($rfi."/das/ist/dummes.php");
}



?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <link rel="stylesheet" type="text/css" href="style.css">
  <title>Hacking-Demos - Hauptseite</title>
</head>

<body>

<h1>Hacking-Demos - Hauptseite</h1>

<p>
Zum 
<a href="admin/index.php?sprache=<? echo $sprache; ?>">Administrationsbereich</a> 
oder zum 
<a href="backend/index.php?sprache=<? echo $sprache; ?>">Backend</a>.
</p>

<p>
Zum 
<a href="kontakt.php?datei=mail.txt">Kontaktformular</a>,
zum
<a href="kontakt-html5.php">Kontaktformular mit E-Mail-Pr&uuml;fung</a> 
und zur
<a href="empfehle.php">Empfehlungsfunktion</a>.
</p>
  
<p>
Die
<a href="../hacking-index.html">Angriffe</a>.
</p>

<p>
Sprachauswahl:  
<a href="index.php?sprache=de">Deutsch</a> 
oder  
<a href="index.php?sprache=en">Englisch</a>.
</p>

<hr>

<p> 
Dieses Skript ist ziemlich beschr&auml;nkt: Es k&ouml;nnen alle
Eintr&auml;ge als Titelliste angezeigt werden, und einzelne Eintr&auml;ge
k&ouml;nnen komplett wieder ausgegeben werden.  Au&szlig;erdem k&ouml;nnen
sich die Besucher hier als Benutzer registrieren und registrierte Benutzer
sich einloggen.  
</p>

  
<p>
<form action="index.php" method="GET"> 
   <input type="hidden" name="sprache" value="<? echo $sprache ?>">
   <input type="text" name="nach" value="Suchbegriff" size="20" maxlength="250">
   <input name="aktion" value="suchen" type="submit">
</form>
</p>

<p>
Ausgew&auml;hlte Sprachdatei: <? echo $sprache; ?>
</p>

<?  

echo $sprachtest;

//  echo "Eingebundene Datei ist:[./lib/sprache/".$sprache.".inc]<br> \n";

echo "\n<p> \n";

if ($_SESSION["eingeloggt"]) {
    // Der Benutzer ist eingeloggt
    echo "Angemeldet als Benutzer \"".$_SESSION["benutzername"]."\".\n";
    echo "(<a href=\"index.php?aktion=ausloggen&sprache=".$sprache."\">Ausloggen</a>)<br> \n";
} else {
    // Der Benutzer ist nicht eingeloggt
    echo "Sie sind nicht angemeldet! \n";
}
echo "</p> \n\n";

$dbok = verbindeMitDB(); 
	// Verbindung zur Datenbank herstellen: Ohne DB geht nichts


if ($dbok) {
   switch ($aktion) {
   case "anzeigen":
      // anzeige des Texts mit der ID $id
      $query = "SELECT Titel, Angelegt, Text, AutorID FROM text_tabelle WHERE Text_ID = ".$id; 
      $result = mysql_query($query);
      if (!$result) {
         // Fehler beim Holen des Eintrags:
         echo "Fehler beim Holen des Eintrags: <br>\n";
         echo mysql_errno().": ".mysql_error()."<br> \n";
      } else {
         while(  $row = mysql_fetch_array($result) ) {
            if (is_array($row)) {
               // Jetzt fehlt nur noch der Autorenname...
               $autorquery = "SELECT Benutzer_Name FROM benutzer_tabelle WHERE Benutzer_ID = ".$row["AutorID"];
               $autorresult = mysql_query($autorquery);
               if (!$result) {
                  // Fehler beim Holen des Eintrags, aber da das nur ein Schoenheitsfehler ist, kann er ignoriert werden
                  $autor = "";
               } else {
                  $autorrow = mysql_fetch_array($autorresult);
                  $autor = $autorrow["Benutzer_Name"];
               }    

               $avatarBild = avatarFile($row["AutorID"]);
               if ($avatarBild == "") {
                  // es gibt keinen Avatar
               } else {
                  $avatartag = "<img src=\"avatare/".$avatarBild."\">";
               }
  
               echo "<h2>Der Eintrag:</h2> \n\n";
               echo "<table width=\"80%\" border=\"2\" cellspacing=\"2\" cellpadding=\"2\"> \n ";
               echo "    <tr> \n";
               echo "        <td rowspan=\"2\" width=\"10%\"> ".$autor."<br>".$avatartag." </td> \n";
               echo "        <td> ".$row["Titel"]." </td> \n";
               echo "        <td> ".$row["Angelegt"]." </td> \n";
               echo "    </tr> \n";
               echo "    <tr> \n";
               echo "        <td colspan=\"2\"> ".$row["Text"]." </td> \n";
               echo "    </tr> \n";
               echo "</table> \n";
            } else {
               echo "<h2>Der Eintrag mit der ID ".$id." existiert nicht!</h2>";
            }
         }
      }
   break;
   case "einloggen":
      // Wenn magic_quotes_gpc aktiv ist, muessen die eingefuegten Backslashes entfernt werden:
      if (get_magic_quotes_gpc()) {
         $benutzer = stripslashes($benutzer);
         $passwort = stripslashes($passwort);
      }
      // SQL-Sonderzeichen maskieren, dies ist erst nach der Verbindung mit der DB moeglich
      // $benutzer =  mysql_real_escape_string($benutzer);
      // $passwort =  mysql_real_escape_string($passwort);

      // Nachsehen, ob es die Kombination von Benutzername und Passwort gibt
      $query = "SELECT Benutzer_ID, freigegeben FROM benutzer_tabelle WHERE Benutzer_Name = '".$benutzer."' AND Passwort = '".$passwort."'";
      $result = mysql_query($query);
      if (!$result) {
         // Fehler beim Holen des Eintrags:
         echo "Fehler beim Einloggen:<br>\n";
         echo mysql_errno().": ".mysql_error()."<br> \n";
      } else {
         $row = mysql_fetch_array($result);
         if ($row["freigegeben"] == 1) {
             echo "<p>\n<b>Anmeldung als $benutzer erfolgreich!</b>\n";
             echo "(<a href=\"index.php?aktion=ausloggen&sprache=".$sprache."\">Ausloggen</a>)<br>\n</p>\n";
             $_SESSION["benutzerID"]    = $row["Benutzer_ID"];
             $_SESSION["benutzername"]  = $benutzer;
             $_SESSION["eingeloggt"]    = TRUE;
         } else {
             echo "<p>\n<b>Anmeldung fehlgeschlagen - Pr&uuml;fen Sie Benutzername und Passwort!</b>\n</p>\n";
         }
	   }
   break;
   case "ausloggen":
      // Das eigentlich Ausloggen ist oben bereits passiert (damit der Session-Cookie geloescht werden kann)
      if ($ausgeloggt) {
          echo "<p>\nAusloggen erfolgreich, die Session wurde gel&ouml;scht. \n</p>\n";
      } 
      else {
          echo "<p>\n<b>Fehler beim Ausloggen</b> \n</p>\n";
      }       
   break;
   case "registrieren":
      // Wenn magic_quotes_gpc aktiv ist, muessen die eingefuegten Backslashes entfernt werden:
      if (get_magic_quotes_gpc()) {
         $real = stripslashes($real);
         $benutzer = stripslashes($benutzer);
         $passwort = stripslashes($passwort);
      }
      // SQL-Sonderzeichen maskieren, dies ist erst nach der Verbindung mit der DB moeglich
      // $real     =  mysql_real_escape_string($real);
      // $benutzer =  mysql_real_escape_string($benutzer);
      // $passwort =  mysql_real_escape_string($passwort);

      $query = "INSERT INTO benutzer_tabelle (Benutzer_Name, Real_Name, Passwort, istAutor, freigegeben)
                            values ('$benutzer', '$real', '$passwort', 0, 0)";                  
      if (!mysql_query($query)) {
         // MySQL-Fehler
         echo "Fehler beim Eintragen des neuen Benutzers in die Datenbank:<br>\n";
         echo mysql_errno().": ".mysql_error()." \n";
      } else {
         // Benutzer angelegt
         echo "<p>\n<b>Benutzer $benutzer angelegt!</b><br>\nBevor Sie sich einloggen k&ouml;nnen, muss der Admin Sie freischalten!\n</p>\n";
         // echo "(<a href=\"index.php?aktion=ausloggen&sprache=".$sprache."\">Ausloggen</a>)  \n";
         // $_SESSION["benutzerID"]    = mysql_insert_id();
         // $_SESSION["benutzername"]  = $benutzer;
         // $_SESSION["eingeloggt"]    = FALSE;
      }
   break;
   case "suchen":
      // Eintrag suchen
      echo "<p> \n";
      echo "Ihre Suche nach [".$nach."] ergab leider kein Ergebnis, da die Suchfunktion noch nicht implementiert wurde! \n";
      echo "</p> \n";
   break;
	default:
   break;
   }

   echo "\n<hr> \n\n<h2>Die bisherigen Eintr&auml;ge</h2> \n\n";
   echo "<p> \n";
   // Einfach ohne Ruecksicht auf Verluste alle Titel ausgeben
   $query = "SELECT * FROM text_tabelle ORDER BY Angelegt"; 
   $result = mysql_query($query);
   if (!$result) {
      // Fehler beim Ergebnis
      echo "Fehler beim Holen der Eintr&auml;ge: <br>\n";
      echo mysql_errno().": ".mysql_error()."<br> \n";
   } else {
      // Jetzt kann es losgehen...
      while ($row = mysql_fetch_array($result)) {
         echo $row["Angelegt"]." : ";
         echo "<!-- ID: ".$row["Text_ID"]." -->  ";
         echo "<a href=\"index.php?aktion=anzeigen&id=".$row["Text_ID"]."&sprache=".$sprache."\">".$row["Titel"]."</a>\n";
         echo "<br><br> \n";
      }         
   }
   echo "</p> \n";

}

?>   

<table>
    <tr>        
<?
if (!$_SESSION["eingeloggt"]) {
    // Der Benutzer ist nicht eingeloggt
    echo "        <th>Als Benutzer einloggen:</th> \n";
    echo "        <th width=10%></th> \n";
}
?>
        <th>Als neuer Benutzer registrieren</th>
    </tr>
    
    <tr>
<?
if (!$_SESSION["eingeloggt"]) {
    // Der Benutzer ist nicht eingeloggt
    echo "        <td valign=top>\n            <form action=\"index.php\" method=\"POST\">  \n";
    echo "                <input type=\"hidden\" name=\"sprache\" value=\"".$sprache."\"><br> \n";
    echo "                Benutzername:<br>  \n";
    echo "                <input type=\"text\" name=\"benutzer\" value=\"gast\" size=\"50\" maxlength=\"100\"><br> \n";
    echo "                Passwort:<br> \n";
    echo "                <input type=\"text\" name=\"passwort\" value=\"gast\" size=\"50\" maxlength=\"100\"><br> \n";
    echo "                <input type=\"submit\" name=\"aktion\" value=\"einloggen\"> \n";
    echo "            </form> \n";
    echo "        </td> \n";
    echo "        <td></td> \n";
}
?>
        <td valign=top>
            <form action="index.php" method="POST"> 
                <input type="hidden" name="sprache" value="<? echo $sprache; ?>">     
                Realname:<br> 
                <input type="text" name="real" value="Realname" size="50" maxlength="100"><br>
                Benutzername:<br> 
                <input type="text" name="benutzer" value="Benutzername" size="50" maxlength="100"><br>
                Passwort:<br>
                <input type="text" name="passwort" value="Passwort" size="50" maxlength="100"><br>
                <input type="submit" name="aktion" value="registrieren">
            </form>
        </td>
    </tr>
</table>

<H3 ALIGN="CENTER">850-Pixel-Linie</H3>
<hr width=850>
<H3 ALIGN="CENTER">850-Pixel-Linie</H3>

</body>
</html>
