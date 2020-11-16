<?

/*
		MySQL-Funktionen fuer Hacking-Demos:
*/

function verbindeMitDB() {
	// Verbindung zur Datenbank herstellen: Ohne DB geht nichts

	$connection = mysql_connect(HOSTNAME,USERNAME,PASSWORD);

	if (!$connection) {
		// Keine Verbindung zu MySQL
		echo "<br>Fehler bei der Verbindung mit MySQL:<br> \n";
		echo "MySQL-Fehler: ".mysql_errno().": ".mysql_error()."<br> \n";
		return false;
	} else {
		$test = mysql_select_db(DATENBANK);
		if (!$test) {
			// Keine Verbindung zur Datenbank
			echo "<br>Fehler bei der Verbindung mit der Datenbank:<br> \n";
			echo "MySQL-Fehler: ".mysql_errno().": ".mysql_error()."<br> \n";
			return false;
		} else {
			return true;
		}
	}
}


?>