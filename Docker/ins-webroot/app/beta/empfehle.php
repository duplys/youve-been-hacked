<?

/*
      empfehle.php fuer Hacking-Demos
      
      Aufgabe:
      	Empfehlungsfunktion
      
		Benutzte Variablen:
			Aufruf:
           aktion   ggf. durchzufuehrende Aktion
           to       ggf. der Empfaenger der Mail

      

 */

define ("SUBJECT","Das wird Dich interessieren!");
define ("BODY","Hallo, \nbesuch mal www.hacking-forum.example - sehr interessant! \nBye!");

// Werte uebernehmen
$aktion  = $_POST["aktion"];
$to      = urldecode($_POST["to"]);
$from    = urldecode($_POST["from"]);

// urldecode(), da die $_POST-Variable nicht automatisch
// dekodiert wird

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet" type="text/css" href="../style.css">
<title>Hacking-Demos Beta - Unsichere Empfehlungsfunktion</title>
</head>

<body class="beta">


<h1>Hacking-Demos Beta - Empfehlungsfunktion</h1>

<p>
Zur <a href="index.php?sprache=<?php echo $sprache; ?>">Startseite</a>,
zum 
<a href="admin/index.php?sprache=<?php echo $sprache; ?>">Administrationsbereich</a> 
oder zum 
<a href="backend/index.php?sprache=<?php echo $sprache; ?>">Backend</a>.
</p>

<p>
Zum 
<a href="kontakt.php">Kontaktformular</a>
und zum
<a href="kontakt-html5.php">Kontaktformular mit E-Mail-Pr&uuml;fung</a>.
</p>

<hr>

<p>
Dieses Skript ist ziemlich beschr&auml;nkt: Es nimmt Nachrichten 
entgegen und speichert sie in einer 
<a href="mail.txt" target="_blank">Textdatei</a>.
</p>
  
<p>
Es ist zwar auch der Mailversand m&ouml;glich, aber der scheitert 
meist daran, dass die meisten Mailserver keine Mails von 
Dialup-Adressen entgegennehmen.
</p>

<?

if ($aktion == "Absenden") {
   // Mail absenden
   if ($to != "") {
      // Ein Empfaenger ist vorhanden
      if ($from != "") {
         // Ein Absender ist vorhanden
         $fromheader = "From: ".$from;
  
         if ( mail($to, SUBJECT, BODY, $fromheader) ) {
            echo "<p><b>Die Mail wurde gesendet!</b></p> \n";
            $to = "";
            $from = "";
         } else {
            echo "<p><b>Fehler beim Senden der Mail!</b></p> \n";
         }
      } else {
         // Wem sollen wir die Mail schicken?
         echo "<p><b>Bitte geben Sie Ihre E-Mail-Adresse ein! <br> \n";
         echo "Danke!</b></p> \n";
      }
   } else {
      // Wem sollen wir die Mail schicken?
      echo "<p><b>Bitte geben Sie die E-Mail-Adresse des Empf&auml;ngers ein! <br> \n";
      echo "Danke!</b></p> \n";
   }
} else if ($aktion == "Simulieren") {
   // Mail in Datei schreiben
   if ($to != "") {
      // Ein Empfaenger ist vorhanden
      if ($from != "") {
         // Ein Absender ist vorhanden
         $fromheader = "From: ".$from;

         $mail = "";
         $mail = $mail."To: ".$to."\r\n";
         $mail = $mail."Subject: ".SUBJECT."\r\n";
         $mail = $mail.$fromheader."\r\n";
         $mail = $mail."\r\n";
         $mail = $mail.BODY."\r\n";
 
         // echo "Das Ergebnis ist: <br>\n<pre>".$mail."\n</pre>\n";
         // echo "Schreibe Mail-Text!<br>\n";
         if (!file_put_contents("mail.txt", $mail)) {
           // echo "Fehler beim Schreiben der Mail!<br>\n";
         } else {
           // echo "Datei erfolgreich geschrieben!<br>\n";
         }
      } else {
         // Wem sollen wir die Mail schicken?
         echo "<p><b>Bitte geben Sie Ihre E-Mail-Adresse ein! <br> \n";
         echo "Danke!</b></p> \n";
      }
   } else {
      // Wem sollen wir die Mail schicken?
      echo "<p><b>Bitte geben Sie die E-Mail-Adresse des Empf&auml;ngers ein! <br> \n";
      echo "Danke!</b></p> \n";
   }
}

?>

<form action="empfehle.php" method="POST"> 
   An: <br>
   <input type="text" name="to" value="<? echo $to; ?>" size="20">   <br>
   Ihre E-Mail-Adresse: <br> 
   <input type="text" name="from" value="<? echo $from; ?>" size="20">   <br><br>
   Das Subject: &nbsp;&nbsp; <code><? echo SUBJECT; ?> </code> <br><br>
   Der Text: <br>
   <pre><? echo BODY; ?></pre> <br>
   <input type="reset" value="Reset">
   <input type="submit" name="aktion" value="Simulieren">
   <input type="submit" name="aktion" value="Absenden">
</form>

<?

if ($aktion == "Simulieren") {
  // Es wurde eine Datei mail.txt geschrieben, diese anzeigen
  $mailtext = file_get_contents("mail.txt");
  echo "<h2>Die erzeugte Mail:</h2> \n";
  echo "<pre>".$mailtext."</pre> \n";

  // Datei leeren:
  // file_put_contents("mail.txt", "");
}

?>

</body>
</html>