<?

/*
      Installations-Skript fuer Hacking-Demos
*/

// Include-Dateien einbinden

require("./lib/config.inc");
require("./lib/dbconfig.inc");

// Die Tabellen

$textTableQuery = "CREATE TABLE text_tabelle (
                   Text_ID int unsigned auto_increment PRIMARY KEY,
                   Titel varchar(255) NOT NULL default '',
                   Text text NOT NULL default '',
                   AutorID int unsigned,
                   Angelegt timestamp)";                    
            
$benutzerTableQuery = "CREATE TABLE benutzer_tabelle (
                   Benutzer_ID int unsigned auto_increment PRIMARY KEY,
                   Benutzer_Name varchar(255) unique NOT NULL default '',
                   Real_Name varchar(255) NOT NULL default '',
                   Passwort varchar(255) NOT NULL default '',
                   istAutor int NOT NULL default '0',
                   freigegeben int NOT NULL default '0',
                   Angelegt timestamp)";                    



// ###############################################################


// Funktionen deklarieren

function mysql_table_exists($table, $db){ 
   $tables=mysql_list_tables($db); 
   while (list($temp)=mysql_fetch_array($tables)) { 
      if($temp == $table) { 
         return 1;
      } 
   } 
   return 0; 
} 

function createTable($table, $query, $ok) {
   if ($ok) {
      $test = mysql_table_exists($table, DATENBANK);
      if ($test) {
         // Tabelle bereits vorhanden
         echo "<dd> ".$table." bereits vorhanden ..... OK!  \n";
         return true;
      } else {
         if (!mysql_query($query)) {
            // MySQL-Fehler
            echo "<dd> Fehler beim Anlegen der Tabelle:<br> \n";        
            echo "MySQL-Fehler: ".mysql_errno().": ".mysql_error()."<br> \n";
            return false;
         } else {
            // Tabelle angelegt
            echo "<dd> ".$table." angelegt ..... OK!  \n";
            return true;
         }
      }
   }
}


// Los gehts...

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Demos - Installation</title>
</head>

<body>

<h1>Hacking-Demos - Installation</h1>

<p>
Dieses Skript legt die notwendigen Datenbanktabellen an.<br>
Die Konfiguration erfolgt &uuml;ber das Skript <code>./lib/config.inc</code>.
</p>


// Start der eigentlichen Installation...

<?
$ok = true;

echo "<dl> \n";
         
/*
      Mit MySQL verbinden   
 */

echo "<dt> Schritt 1: Versuche, mich mit MySQL zu verbinden... \n";
  
$connection = mysql_connect(HOSTNAME,USERNAME,PASSWORD);

if (!$connection) {
   // Verbindung nicht moeglich
   echo "<dd> Fehler:<br> \n";
   echo "Keine Verbindung zum MySQL-Server m&ouml;glich. <br> \n";
   echo "Stimmen HOSTNAME, USERNAME und PASSWORD in config.inc?  \n";             
   $ok = false;
   } else {
   // Verbindung OK
   echo "<dd> MySQL-Verbindung ..... OK!  \n";
}            
          
/*
      Demo-Datenbank anlegen
 */

echo "<dt> Schritt 2: Demo-Datenbank anlegen... \n";

if ($ok) {      
   if (mysql_select_db(DATENBANK)) {
      // DB schon vorhanden
      echo "<dd> Demo-Datenbank bereits vorhanden ..... OK!  \n";
      } else {
      if (!mysql_query('CREATE DATABASE '.DATENBANK)) {
         // MySQL-Fehler
         echo "<dd> Fehler beim Anlegen der Datenbank:<br> \n";        
         echo "MySQL-Fehler: ".mysql_errno().": ".mysql_error()."\n";
         $ok = false;
      } else {
         // DB angelegt
         echo "<dd> Demo-Datenbank angelegt  ..... OK! \n";
      }
   } 
}         
         
/*
      Tabellen anlegen
 */

if ($ok) {
	echo "<dt> Schritt 3: Text-Tabelle anlegen...  \n";
	$ok = createTable('text_tabelle', $textTableQuery, $ok);
}

if ($ok) {
	echo "<dt> Schritt 4: Benutzer-Tabelle anlegen...  \n";
	$ok = createTable('benutzer_tabelle', $benutzerTableQuery, $ok);
}


/*
      Standard-Eintraege erstellen
 */

if ($ok) {
	echo "<dt> Schritt 5: Begruessungstext und erste Texte anlegen, damit die DB nicht so leer ist... \n";

   $anlegen = true;
   $testquery = "SELECT * FROM text_tabelle WHERE Text_ID = 1";
   $testresult = mysql_query($testquery);
      // Existiert der Begruessungstext schon?
   if ($testresult) {
      $testrow = mysql_fetch_array($testresult);
      if ($testrow["Titel"] != "") {
         echo "<dd> Es ist schon ein Text vorhanden ..... OK!  \n";
         $anlegen = false;
      }
   }
   if ($anlegen) {
      $query = "INSERT INTO text_tabelle (Titel, Text, AutorID)
                            values ('Willkommen zu den Hacking-Demos',
                                    'Dieser Text steht hier nur, damit hier &uuml;berhaupt etwas steht...',
                                    1),
                                    ('Ein zweiter Text',
                                    'Auch dieser Text steht hier nur, damit hier &uuml;berhaupt etwas steht...',
                                    1),
                                   ('... und ein dritter Text',
                                    'Dieser Text steht hier auch nur, damit hier &uuml;berhaupt etwas steht...',
                                    1)";                    
      if (!mysql_query($query)) {
         // MySQL-Fehler
         echo "<dt> Fehler beim Anlegen der ersten Eintr&auml;ge:<br> \n";        
         echo "MySQL-Fehler: ".mysql_errno().": ".mysql_error()." \n";
         $ok = false;
      } else {
         // Begruessungstext angelegt
         echo "<dd> Begr&uuml;&szlig;ungstext etc. angelegt ..... OK!  \n";
      }
   }
}

/*
      Default-Benutzer anlegen
 */

if ($ok) {
	echo "<dt> Schritt 6: Einen ersten Autor und weitere Benutzer anlegen \n";

   $anlegen = true;
   $testquery = "SELECT * FROM benutzer_tabelle WHERE Benutzer_ID = 1";
   $testresult = mysql_query($testquery);
      // Existiert der Autor schon?
   if ($testresult) {
      $testrow = mysql_fetch_array($testresult);
      if ($testrow["Benutzer_Name"] == "autor") {
         echo "<dd> Es ist schon ein Autor vorhanden ..... OK!  \n";
         $anlegen = false;
      }
   }
   if ($anlegen) {
      $query = "INSERT INTO benutzer_tabelle (Benutzer_Name, Real_Name, Passwort, istAutor, freigegeben)
                            values ('autor', 'Der Autor', 'autor', 1, 1),
                                   ('gast', 'Der Gast-Benutzer', 'gast', 0, 1),
                                   ('test', 'Test User 1', 'test', 1, 1),
                                   ('user', 'Test User 2', 'password', 1, 1)";                  
      if (!mysql_query($query)) {
         // MySQL-Fehler
         echo "<dt> Fehler beim Anlegen der Benutzer<br> \n";        
         echo "MySQL-Fehler: ".mysql_errno().": ".mysql_error()." \n";
         $ok = false;
      } else {
         // Benutzer angelegt
         echo "<dd> Benutzer angelegt ..... OK!  \n";
      }
   }
}

echo "</dl> \n";

/*
      Fertig...
 */

if ($ok) {
   // alles OK, es kann losgehen
   echo "<br>Herzlichen Gl&uuml;ckwunsch: Die Datenbank wurde erfolgreich eingerichtet!<br><br> \n";
   echo "Sie k&ouml;nnen die <a href=\"./index.php\">Demo</a> jetzt starten! \n";
} else {
   // Fehler bei der Installation, Einstellungen pruefen!
   echo "<br>Fehler bei der Einrichtung der Datenbank!<br><br> \n";
   echo "Bitte &uuml;berpr&uuml;fen Sie die Einstellungen in <code>./lib/config.inc</code> und rufen Sie diese Seite danach erneut auf! \n";
}
        
?>
 

</body>
</html>




   
