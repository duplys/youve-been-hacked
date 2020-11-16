<?

/*
      lfi.php fuer Hacking-Demos Backend
      
      Aufgabe:
      	Demo fuer Local File Inclusion
      
		Benutzte Variablen:
			Aufruf:
           lfi   einzubindende Datei
						
 */

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Hacking-Demos - File Inclusion Demo</title>
</head>

<body>

<h1>Hacking-Demos - File Inclusion Demo</h1>

<h2>Der Parameter:</h2>

<p>
<? echo $_REQUEST["lfi"]; ?>
</p>

<!-- <br><br><br><br><br><br><br> -->
<hr>
<!-- <br><br><br><br><br><br><br> -->
  
<h2>Das Bild sieht so aus:</h2>

<p>
<img src="<? echo $_REQUEST["lfi"]; ?>">
</p>

<br><br><br> <!-- <br><br><br><br> -->
<hr>
<!-- <br><br><br><br><br><br><br> -->
  
<h2>Und in einem <code>include()</code>-Aufruf ergibt das:</h2>

<? include($_REQUEST["lfi"]); ?>


</body>
</html>