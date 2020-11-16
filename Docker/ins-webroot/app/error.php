<?php 

error_reporting(E_ALL);

ini_set('display_errors','On');

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>Hacking-Demos - PHP-Fehlermeldungen</title>
  </head>
  
  <body>
    <h1>Hacking-Demos - PHP-Fehlermeldungen</h1>
    
    <p>
      Wenn PHP ausf&uuml;rliche Fehlermeldungen an den Benutzer ausgibt, sieht das so aus:
    </p>
    
    <h2>Fehlermeldung für 1/0</h2>
    
    <p>
      Das Ergebnis von 1 geteilt durch 0 ist: <? echo(1/0); ?>
    </p>
 
    <h2>Fehlermeldung für fehlenden Parameter</h2>
    
    <p>
      Wenn Sie versuchen, auf eine undefinierte Variable zuzugreifen, kommt folgende Fehlermeldung: <? echo($gibtsjagarnicht); ?>
    </p>
       
    <h2>Beliebige Ausgaben</h2>
    
    <p>
      Das Skript kann auch die Daten ausgeben, die im folgenden Formular eingegebenen wurden.<br>
      Wenn das Formular nicht abgeschickt wurde, gibt es eine weitere Fehlermeldung.
    </p>
   
    <form action="error.php" method="POST">
      <textarea rows="3" cols="80" name="eingabe">Geben Sie hier ein, was das Skript ausgeben soll...</textarea>
      <br>
      <input type="submit" name="aktion" value="ausgeben">
    </form>
    
    <p>
      <? echo( $_POST["eingabe"] ); ?>
    </p> 

  </body>
</html>  
