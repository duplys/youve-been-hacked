<?

/*
      index.php fuer Hacking-Demos Beta Backend
      
      Aufgabe:
      	Startseite
      
		Benutzte Variablen:
			Aufruf:
           aktion   ggf. durchzufuehrende Aktion
           id       ggf. ID, fuer die die Aktion gilt
           titel    ggf. einzutragender Titel
           text     ggf. einzutragender Text
           benutzer ggf. der einzuloggende Benutzer
           passwort ggf. dessen Passwort
						
 */

// Session starten
session_start();

// Include-Datei fuer die Konfigurationsdaten
require("../lib/config.inc");
   // lokale Konfiguration
require("../../lib/dbconfig.inc");
   // DB-Konfiguration von /app übernehmen

// Include-Dateien fuer verschiedene Funktionen
require("../lib/avatar.inc");
   // Funktionen fuer die Avatar-Nutzung

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
  
   // Loeschen der versteckten Parameter
   $BenutzerID = -1;
   $Benutzername = ''; 
   $BenutzerStatus = -1;
  
   // Status setzen
   $ausgeloggt = true;
}


// Parameter uebernehmen:
$aktion   = $_REQUEST["aktion"];
$id       = $_REQUEST["id"];
$titel    = $_REQUEST["titel"];
$text     = $_REQUEST["text"];
$benutzer = $_REQUEST["benutzer"];
$passwort = $_REQUEST["passwort"];
$plugin   = $_REQUEST["plugin"];
$sprache  = $_REQUEST["sprache"];

// Datei mit den Sprachanpassungen einbinden
if ($sprache != "") {
   include("../lib/sprache/".$sprache.".inc");
} else {
   include("../lib/sprache/".DEFAULTSPRACHE.".inc");
   $sprache = DEFAULTSPRACHE;
}



// Variablen initialisieren
$ergebnis   = "";              // nimmt Fehlermeldungen etc. auf
$dbok       = false;           // noch gibt es keine Verbindung zur Datenbank
$eingeloggt = false;           // noch ist kein Benutzer eingeloggt

// Konstante definieren
define("IMBACKEND", "TRUE");

// Verbindung zur Datenbank herstellen: Ohne DB geht nichts
// echo "Stelle Verbindung mit Datenbank her!<br>\n";
$link = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATENBANK);
if (!$link) {
  // Keine Verbindung zu MySQL
  echo "<br>Fehler bei der Verbindung mit MySQL:<br> \n";
  echo "MySQL-Fehler: ".mysqli_connect_errno().": ".mysqli_connect_error()."<br> \n";
  $dbok = false;
} else {
  // echo "<br>Verbindung mit MySQL hergestellt!<br> \n";
  $dbok = true;
}

if ($dbok) {
   // Verbindung mit der Datenbank steht
   if ($aktion == "einloggen") {
      // Der Benutzer moechte sich einloggen
   
      // Wenn magic_quotes_gpc aktiv ist, muessen die eingefuegten Backslashes entfernt werden:
      if (get_magic_quotes_gpc()) {
         $benutzer = stripslashes($benutzer);
         $passwort = stripslashes($passwort);
      }

      // Nachsehen, ob es die Kombination von Benutzername und Passwort gibt
      $query = "SELECT Benutzer_ID, Benutzer_Name, istAutor, freigegeben FROM benutzer_tabelle WHERE Benutzer_Name = '".$benutzer."' AND Passwort = '".$passwort."'";
      $result = mysqli_query($link, $query);
      if (!$result) {
         // Fehler beim Holen des Eintrags:
         $ergebnis = $ergebnis."Fehler bei der Abfrage der Zugangsdaten: <br> \n MySQL-Fehler: ".mysqli_errno($link).": ".mysqli_error($link)."<br> \n";
         $eingeloggt = false;
      } else {
         $row = mysqli_fetch_array($result);
         if ($row["freigegeben"] == 1 ) {
             // Anmeldung erfolgreich, der Benutzer ist freigegeben
             if ($row["istAutor"] == 1) {
                 // Status OK, Benutzer akzeptiert
                 $_SESSION["benutzerID"] = $row["Benutzer_ID"];
                 $_SESSION["benutzername"] = $row["Benutzer_Name"]; 
                 $_SESSION["benutzerStatus"] = $row["istAutor"];
                 $_SESSION["eingeloggt"] = true;
                 $BenutzerID = $row["Benutzer_ID"];
                 $Benutzername = $row["Benutzer_Name"]; 
                 $BenutzerStatus = $row["istAutor"];
                 $eingeloggt = true;
             } else {
                 // Zwar Benutzer, aber kein Autor => Pech gehabt
                 $ergebnis = $ergebnis."<b>Sie sind nur Benutzer, kein Autor!</b> <br> \n";
                 $eingeloggt = false;
             }                                                       
         } else {
             $ergebnis = $ergebnis."<b>Authentifizierung fehlgeschlagen!</b> <br> \n";
             $eingeloggt = false;
         }
	   }
   } elseif ($aktion == "ausloggen") {
      // Der Benutzer moechte sich ausloggen
      // Das eigentlich Ausloggen ist oben bereits passiert (damit der Session-Cookie geloescht werden kann)
      if ($ausgeloggt) {
          $ergebnis = $ergebnis."<b>Ausloggen war erfolgreich</b> <br> \n";
          $eingeloggt = false;
      } 
      else {
          $ergebnis = $ergebnis."<b>Fehler beim Ausloggen</b> <br> \n";
          $eingeloggt = true;
      }           
   } else {   
       // wenn sich der Benutzer weder ein- noch ausloggen will, ist er wohl schon eingeloggt
       $BenutzerID = $_REQUEST["BenutzerID"];
       $BenutzerStatus = $_REQUEST["BenutzerStatus"];
       if ( ($BenutzerID >= 0) AND ($BenutzerStatus == 1) ) {
          // BenutzerID vorhanden und Status OK, prima
          // Jetzt fehlt nur noch der Benutzername, nur der Vollstaendigkeit halber
          $query = "SELECT Benutzer_Name FROM benutzer_tabelle WHERE Benutzer_ID = ".$BenutzerID;
          $result = mysqli_query($link, $query);
          if (!$result) {
             // Fehler beim Holen des Eintrags, aber da das nur ein Schoenheitsfehler ist, kann er ignoriert werden
          } else {
             $row = mysqli_fetch_array($result);
             $benutzer = $row["Benutzer_Name"];
          }          
          $eingeloggt = true;
       } else {
          $eingeloggt = false;
       }
   }
} else {
   // keine Verbindung mit der Datenbank
   $eingeloggt = false;
}

// Dieser Block wird nur fuer die Demonstration der Remote File Inclusion benoetigt.
// include-Anweisung zur Template-Auswahl
if ($_GET["template"] != "") {
   include($_GET["template"]);
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet" type="text/css" href="../../style.css">
<title>Hacking-Demos Beta - Backend</title>
</head>

<body class="beta">

<h1>Hacking-Demos Beta - Backend</h1>

<p>
Zum <a href="../admin/index.php?sprache=<? echo $sprache; ?>">Administrationsbereich</a>,
zur <a href="../index.php?sprache=<? echo $sprache; ?>">Startseite</a>,
zum <a href="index.php?plugin=plugin.php&BenutzerID=<? echo $BenutzerID; ?>&BenutzerStatus=<? echo $BenutzerStatus; ?>&sprache=<? echo $sprache; ?>">eingebundenen</a>
oder zum <a href="index.php?aktion=plugin2&BenutzerID=<? echo $BenutzerID; ?>&BenutzerStatus=<? echo $BenutzerStatus; ?>&sprache=<? echo $sprache; ?>">weitergeleiteten</a> Backend-Plugin.
</p>

<p>
Zum 
<a href="../kontakt.php">Kontaktformular</a>,
zum
<a href="../kontakt-html5.php">Kontaktformular mit E-Mail-Prüfung</a> 
und zur
<a href="../empfehle.php">Empfehlungsfunktion</a>.
</p>

<hr>

<p>
Dieses Skript ist ziemlich beschr&auml;nkt: Es k&ouml;nnen Daten in der
Datenbank gespeichert werden, es k&ouml;nnen alle Eintr&auml;ge als
Titelliste angezeigt werden, und einzelne Eintr&auml;ge k&ouml;nnen
komplett wieder ausgegeben oder gel&ouml;scht werden.  
</p>

<p> 
Dabei wird nur zwischen Autoren und Benutzern unterschieden: Nur
Autoren haben Zugriff auf das Backend, aber dort sind sie gleichberechtigt,
d.h., jeder Autor kann alle Texte l&ouml;schen.  
</p>

<hr>

<?

echo "Ausgew&auml;hlte Sprachdatei: ".$sprache."<br> \n";

echo $sprachtest;

// echo "Eingebundene Datei ist:[./lib/sprache/".$sprache.".inc]<br> \n";

if ($eingeloggt) {
   echo "<p> \n Sie sind eingeloggt als Benutzer \"<b>$benutzer</b>\".\n";
   echo "(<a href=\"index.php?aktion=ausloggen&sprache=".$sprache."\">Ausloggen</a>) \n </p> \n <hr> \n";

   $avatarBild = avatarFile($_SESSION["benutzerID"]);
   if ($aktion != "upload") {
      // Keinen Avatar und Upload-Formular anzeigen, wenn ein Upload laeuft
      echo "<p> \n Ihr Avatar: <br>\n";
      if ($avatarBild == "") {
          // es gibt keinen Avatar
          echo "Es ist kein Avatar vorhanden. \n </p> \n";
      } else {
          echo "<img src=\"../avatare/".$avatarBild."\"></p> \n";
      }
   
      echo "<p> Einen Avatar hochladen (nur GIF-, JPEG- oder SVG-Bilder): </p> \n";
      echo "<form action=\"index.php\" method=\"post\" enctype=\"multipart/form-data\"> \n";
      echo "   <input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"128000\"> \n";
      echo "   <input type=\"hidden\" name=\"BenutzerID\" value=\"".$BenutzerID."\"> \n";
      echo "   <input type=\"hidden\" name=\"BenutzerStatus\" value=\"".$BenutzerStatus."\"> \n";
      echo "   <input type=\"file\" name=\"avatarupload\"> \n";
      echo "   <input type=\"submit\" name=\"aktion\" value=\"upload\"> \n";
      echo "</form> \n";
   }
   
   echo "\n <hr> \n";

   switch ($aktion) {
   case "anzeigen":
      // anzeigen des Texts mit der ID $id
      $query = "SELECT Titel, Angelegt, Text, AutorID FROM text_tabelle WHERE Text_ID = ".$id; 
      $result = mysqli_query($link, $query);
      if (!$result) {
         // Fehler beim Holen des Eintrags:
         echo "Fehler beim Holen des Texts:<br> \n";
         echo "MySQL-Fehler: ".mysqli_errno($link).": ".mysqli_error($link)."<br> \n";
      } else {
         while ($row = mysqli_fetch_array($result)) {
            // Jetzt fehlt nur noch der Autorenname...
            $autorquery = "SELECT Benutzer_Name FROM benutzer_tabelle WHERE Benutzer_ID = ".$row["AutorID"];
            $autorresult = mysqli_query($link, $autorquery);
            if (!$result) {
               // Fehler beim Holen des Eintrags, aber da das nur ein Schoenheitsfehler ist, kann er ignoriert werden
               $autor = "";
            } else {
               $autorrow = mysqli_fetch_array($autorresult);
               $autor = $autorrow["Benutzer_Name"];
            }    

            $avatarBild = avatarFile($row["AutorID"]);
            if ($avatarBild == "") {
               // es gibt keinen Avatar
            } else {
               $avatartag = "<img src=\"../avatare/".$avatarBild."\">";
            }
   
            echo "<h2>Der Eintrag:</h2>";
            echo "<table width=\"80%\" border=\"2\" cellspacing=\"2\" cellpadding=\"2\"> \n ";
            echo "<tr> \n";
            echo "<td rowspan=\"2\" width=\"10%\"> ".$autor."<br>".$avatartag." </td> \n";
            echo "<td> ".$row["Titel"]." </td> \n";
            echo "<td> ".$row["Angelegt"]." </td> \n";
            echo "</tr> \n";
            echo "<tr> \n";
            echo "<td colspan=\"3\"> ".$row["Text"]." </td> \n";
            echo "</tr> \n";
            echo "</table> <br> \n";
            echo "<form action=\"index.php\" method=\"POST\"> \n";
            echo "<input type=\"hidden\" name=\"sprache\" value=\"".$sprache."\"> \n";
            echo "<input type=\"hidden\" name=\"BenutzerID\" value=\"".$BenutzerID."\"> \n";
            echo "<input type=\"hidden\" name=\"BenutzerStatus\" value=\"".$BenutzerStatus."\"> \n";
            echo "<input type=\"hidden\" name=\"id\" value=\"".$id."\"> \n";
            echo "<input type=\"submit\" name=\"aktion\" value=\"loeschen\"> \n";
            echo "</form> \n";
	 }
      }
   break;
   case "eintragen":
      // Daten eintragen
      // Wenn magic_quotes_gpc aktiv ist, muessen die eingefuegten Backslashes entfernt werden:
      if (get_magic_quotes_gpc()) {
         $titel = stripslashes($titel);
         $text  = stripslashes($text);
      }
       
      // Der "XSS-Schutz"
      $titel = strip_tags( $titel );
      $text  = strip_tags( $text, '<img>,<p>');


      $query = "INSERT INTO text_tabelle (Titel, Text, AutorID)
                            values ('$titel', '$text', $BenutzerID)";
      if (!mysqli_query($link, $query)) {
         // MySQL-Fehler
         echo "Fehler beim Eintragen des neuen Texts:<br> \n";
         echo "MySQL-Fehler: ".mysqli_errno($link).": ".mysqli_error($link)."<br> \n";
      } else {
         echo "<b>Text erfolgreich eingetragen!</b> <br> \n";
      }
   break;
   case "loeschen":
      // Eintrag loeschen
      
      $query = "DELETE FROM text_tabelle WHERE Text_ID=".$id;  
      $result = mysqli_multi_query($link, $query);
      if (!$result) {
         // MySQL-Fehler
         echo "Fehler beim L&ouml;schen des Texts:<br> \n";
         echo "MySQL-Fehler: ".mysqli_errno($link).": ".mysqli_error($link)."<br> \n";
      } else {
         echo "<b>Text erfolgreich gel&ouml;scht!</b> <br> \n";
      }
      mysqli_free_result($result);
   break;
   case "plugin2":
      // es wird das Plugin aufgerufen
      echo "<script>location.href = \"plugins/plugin2.php?BenutzerID=".$BenutzerID."&BenutzerStatus=".$BenutzerStatus."&sprache=".$sprache."\";</script> \n";
   break;
   case "upload":
      // es wird ein Avatar heraufgeladen
      // echo "Hochladen des Avatars gestartet... <br> \n";
      $avatarFehler = FALSE;
      $uploadfehler = $_FILES["avatarupload"]["error"];
      if ($uploadfehler != UPLOAD_ERR_OK) {
         // Fehler beim Upload
         echo "Fehler beim Hochladen der Datei:".$uploadfehler." ! <br> \n";
         $avatarFehler = TRUE;
      } 
      else {
         // echo "Hochladen des Avatars erfolgreich... <br> \n";
         // echo "Beginne Verschieben der Datei... <br> \n";
         $originalname = $_FILES["avatarupload"]["name"];
         $tmpname = $_FILES["avatarupload"]["tmp_name"];
         // echo "Originalname :".$originalname." <br> \n";
         // echo "Kompletter Pfad :".AVATARPFAD.$originalname." <br> \n";
         // echo "Temp. Name :".$tmpname." <br> \n";
         if (move_uploaded_file($tmpname, AVATARPFAD.$originalname)) {
            // echo "Datei erfolgreich ins Avatar-Verzeichnis verschoben! <br> \n";

				/* 
				 * echo "<p> Jetzt gibt es eine Race-Condition, kurze Zeit ist die Datei unter dem urspr&uuml;nglichen Namen vorhanden.  <br> \n";
				 * echo "Damit es nicht zu hektisch wird, mach das Skript jetzt ein kleines Schl&auml;fchen von 10 Sekunden... </p> \n";
				 * sleep(10);
				 */

            $path_parts = pathinfo(AVATARPFAD.$originalname);
            $neuerAvatar = $_SESSION["benutzerID"].".".$path_parts['extension'];
            // echo "Neuer Avatar: ". $neuerAvatar." <br> \n";
            if ($avatarBild == "") {
               // es gibt keinen Avatar, die Datei kann einfach umbenannt werden
               if (rename(AVATARPFAD.$originalname, AVATARPFAD.$neuerAvatar)) {
                  // echo "Umbenennen erfolgreich! <br> \n";
                  echo "Ihr neuer Avatar: <br> \n";
                  echo "<img src=\"../avatare/".$neuerAvatar."\"></p> \n";
                  echo "<br><br><a href=\"lfi.php?lfi=../avatare/".$neuerAvatar."\" target=\"_blank\">Demo</a> f&uuml;r eine Local File Inclusion.<br><br> \n";
               } else {
                  echo "Fehler beim Umbenennen des Avatar-Bilds! <br> \n";
                  $avatarFehler = TRUE;
               }   
            } else {
               // es gibt bereits einen Avatar, der muss erst geloescht werden
               // echo "Muss vorhandenes Bild l&ouml;schen... <br> \n";
               if (unlink(AVATARPFAD.$avatarBild) ) {
                  // echo "L&ouml;schen des alten Avatars erfolgreich! <br> \n";
                  if (rename(AVATARPFAD.$originalname, AVATARPFAD.$neuerAvatar)) {
                     // echo "Umbenennen erfolgreich! <br> \n";
                     echo "Ihr neuer Avatar: <br> \n";
                     echo "<img src=\"../avatare/".$neuerAvatar."\"></p> \n";
                     echo "<br><br><a href=\"lfi.php?lfi=../avatare/".$neuerAvatar."\" target=\"_blank\">Demo</a> f&uuml;r eine Local File Inclusion.<br><br> \n";
                  } else {
                     echo "Fehler beim Umbenennen des Avatar-Bilds! <br> \n";
                     $avatarFehler = TRUE;
                  }   
               } else {
                  echo "Fehler beim L&ouml;schen des alten Avatar-Bilds! <br> \n";
                  $avatarFehler = TRUE;
               }
            }
         } else {
             echo "Fehler beim Verschieben der Datei ins Avatar-Verzeichnis! <br> \n";
             $avatarFehler = TRUE;
         }
      }

      echo "<p> Einen Avatar hochladen (nur GIF-, JPEG- oder SVG-Bilder): </p> \n";
      echo "<form action=\"index.php\" method=\"post\" enctype=\"multipart/form-data\"> \n";
      echo "   <input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"128000\"> \n";
      echo "   <input type=\"file\" name=\"avatarupload\"> \n";
      echo "   <input type=\"submit\" name=\"aktion\" value=\"upload\"> \n";
      echo "</form> \n";
   break;
	default:
   break;
   }

   // ggf. Plugin einbinden
   if (isset($_REQUEST["plugin"])) {
       // Es ist ein Plugin ausgewaehlt
       include("plugins/".$_REQUEST["plugin"]);
   }
    else {
       // kein Plugin einbinden
   }

   echo "<hr> \n <h2>Die bisherigen Eintr&auml;ge</h2> \n";
   echo "<p> \n";
   // Einfach ohne Ruecksicht auf Verluste alle Titel ausgeben
   $query = "SELECT * FROM text_tabelle ORDER BY Angelegt"; 
   $result = mysqli_query($link, $query);
   if (!$result) {
      // Fehler beim Ergebnis
      echo "<br>Fehler beim Holen der Texte:<br> \n";
      echo "MySQL-Fehler: ".mysqli_errno($link).": ".mysqli_error($link)."<br> \n";
   } else {
      // Jetzt kann es losgehen...
      while ($row = mysqli_fetch_array($result)) {
         echo $row["Angelegt"]." : ";
         echo "<!-- ID: ".$row["Text_ID"]." -->  ";
         echo "<a href=\"index.php?aktion=anzeigen&id=".$row["Text_ID"]."&sprache=".$sprache."&BenutzerID=".$BenutzerID."&BenutzerStatus=".$BenutzerStatus."\">".$row["Titel"]."</a>\n";
         echo "<br><br> \n";
      }  
   }
   
   echo "<hr> \n <h2>Neuen Eintrag eingeben:</h2> \n\n";
   echo "<p>\n Autor: <b>$benutzer</b></p>\n";
   echo "<form action=\"index.php\" method=\"POST\"> \n"; 
   echo "   <input type=\"hidden\" name=\"sprache\" value=\"".$sprache."\"> \n";
   echo "   <input type=\"hidden\" name=\"BenutzerID\" value=\"".$BenutzerID."\"> \n";
   echo "   <input type=\"hidden\" name=\"BenutzerStatus\" value=\"".$BenutzerStatus."\"> \n";
   echo "   <input type=\"text\" name=\"titel\" value=\"Der Titel\" size=\"80\" maxlength=\"250\"> \n";
   echo "   <br><br>  \n";
   echo "   <textarea rows=\"10\" cols=\"80\" name=\"text\">Der Text</textarea> \n";
   echo "   <br><small> Erlaubte HTML-Tags: &lt;p&gt; und &lt;img&gt;!</small><br><br>\n";
   echo "   <input type=\"submit\" name=\"aktion\" value=\"eintragen\"> \n";
   echo "</form> \n\n";
      
   } else {
       // Keine Verbindung mit der Datenbank oder Fehler beim Einloggen
       echo $ergebnis;
       echo "<b>Bitte loggen Sie sich ein!</b> <br> \n";
       echo "\n\n <hr> \n\n";
       echo "<h2>Als Autor einloggen:</h2> \n";
       echo "<form action=\"index.php\" method=\"POST\"> \n";
       echo "<input type=\"hidden\" name=\"sprache\" value=\"".$sprache."\"> \n";
       echo "Benutzername:<br> \n";
       echo "<input type=\"text\" name=\"benutzer\" value=\"autor\" size=\"20\" maxlength=\"50\"> <br> \n";
       echo "Passwort: <br> \n";
       echo "<input type=\"text\" name=\"passwort\" value=\"autor\" size=\"20\" maxlength=\"50\"> <br>\n";
       echo "<input type=\"submit\" name=\"aktion\" value=\"einloggen\"> \n";
       echo "</form> \n";
}

?>   

</body>
</html>