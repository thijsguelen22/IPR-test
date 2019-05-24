<?php
require_once 'db_connector.php';
require_once 'globalFunctions.php';

function ControleerLoginData($dbh, $user, $passwd) {
	$LoginData['code'] = 0;
	try {
		$stmt = $dbh->prepare("SELECT * FROM gebruiker WHERE gebruikersnaam = :username");
		$prm = ([':username'=>$user]);
		$stmt->execute($prm);
		$data = $stmt->fetch(PDO::FETCH_ASSOC);
	} catch(PDOException $e) {
		$LoginData['code'] = 0;
	}
	if($data['wachtwoord'] == $passwd) {
			$LoginData['code'] = 1;
			$LoginData['userName'] = $data['gebruikersnaam'];
      $LoginData['firstName'] = $data['voornaam'];
      $LoginData['lastName'] = $data['achternaam'];
      $LoginData['email'] = $data['mailbox'];
	}
	return $LoginData;
}
