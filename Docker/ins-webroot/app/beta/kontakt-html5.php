<?

/*
      kontakt.php fuer Hacking-Demos
      
      Aufgabe:
      	Kontaktformular
      
		Benutzte Variablen:
			Aufruf:
           aktion   ggf. durchzufuehrende Aktion
           subject  ggf. das Subject der Mail
           from     ggf. der Absender der Mail
           body     ggf. der Inhalt der Mail

 
						

 */

// Der Mail-Empfaenger
define ("MAILTO","empfaenger@anwendung.example");

// Werte uebernehmen
$aktion  = $_POST["aktion"];
$subject = urldecode($_POST["subject"]);
$from    = urldecode($_POST["from"]);
$body    = urldecode($_POST["body"]);

// urldecode(), da die $_POST-Variable nicht automatisch
// dekodiert wird


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet" type="text/css" href="../style.css">
<title>Hacking-Demos Beta - Kontaktformular mit HTML5</title>
</head>

<body class="beta">


<h1>Hacking-Demos Beta - Kontaktformular mit HTML5</h1>

<p>
Zur <a href="index.php?sprache=<?php echo $sprache; ?>">Startseite</a>,
zum 
<a href="admin/index.php?sprache=<?php echo $sprache; ?>">Administrationsbereich</a> 
oder zum 
<a href="backend/index.php?sprache=<?php echo $sprache; ?>">Backend</a>.
</p>

<p>
Zum 
<a href="kontakt.php"> normalen Kontaktformular</a>
und zur
<a href="empfehle.php">Empfehlungsfunktion</a>.
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
   if ($from != "") {
      // Ein Absender ist vorhanden
      $fromheader = "From: ".$from;
      if ( mail(MAILTO, $subject, $body, $fromheader) ) {
         echo "<p><b>Die Mail wurde gesendet!</b></p> \n";
         $from = "";
         $subject = "";
         $body = "";
      } else {
         echo "<p><b>Fehler beim Senden der Mail!</b></p> \n";
      }
   } else {
      // Wem sollen wir antworten, wenn kein Absender angegeben wurde?
      echo "<p><b>Bitte geben Sie ihre E-Mail-Adresse als Absender an, damit wir Ihnen antworten k&ouml;nnen! <br> \n";
      echo "Danke!</b></p> \n";
   }
} else if ($aktion == "Simulieren") {
   // Mail in Datei schreiben
   if ($from != "") {
      // Ein Absender ist vorhanden
      $fromheader = "From: ".$from;

      $mail = "";
      $mail = $mail."To: ".MAILTO."\r\n";
      $mail = $mail."Subject: ".$subject."\r\n";
      $mail = $mail.$fromheader."\r\n";
      $mail = $mail."\r\n";
      $mail = $mail.$body."\r\n";
 
      file_put_contents("mail.txt", $mail);
   } else {
      // Wem sollen wir antworten, wenn kein Absender angegeben wurde?
      echo "<p><b>Bitte geben Sie ihre E-Mail-Adresse als Absender an, damit wir Ihnen antworten k&ouml;nnen! <br> \n";
      echo "Danke!</b></p> \n";
   }
}



?>

<form action="kontakt.php" method="POST"> 
   Ihre E-Mail-Adresse: <br> 
   <input type="email" name="from" value="<? echo $from; ?>" size="50">   <br>
   Das Subject: <br> 
   <input type="text" name="subject" value="<? echo $subject; ?>" size="50">   <br>
   Der Text: <br>
   <textarea rows="20" cols="70" name="body"><? echo $body; ?></textarea> <br>
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

  file_put_contents("mail.txt", "");
}

?>

</body>
</html>