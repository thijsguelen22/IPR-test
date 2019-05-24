<?php

function isMinimumLength($input, $minLength)
{
    return (strlen($input) >= (int)$minLength);
}
function isCharactersOnly($input)
{
    return (bool)(preg_match("/^[a-zA-Z ]*$/", $input)) ;
}

function getProtocol() {
	return empty($_SERVER['HTTPS']) ? 'http://' : 'https://';
}

function isSubCategoryUnique($dbh, $name, $ID) {
  $Ret = array('PDORetCode'=>0);
	try	{
		$stmt = $dbh->prepare('SELECT COUNT(naam) FROM Rubriek WHERE lower(naam) = lower(:cat) AND volgnummer = :ID');
		$prm = array(':cat'=>$name, ':ID'=>$ID);
		$stmt->execute($prm);
		$data = $stmt->fetch(PDO::FETCH_NUM);
		$Ret = array('PDORetCode'=>1);
		if ($data[0] > 0) {
			$Ret['unique'] = false;
		} else {
			$Ret['unique'] = true;
		}
	} catch(PDOException $e) {
		$Ret = array('PDORetCode'=>0);
	}
	return $Ret;
}

function isCategoryUnique($dbh, $name) {
  $Ret = array('PDORetCode'=>0);
	try	{
		$stmt = $dbh->prepare('SELECT COUNT(naam) FROM Rubriek WHERE lower(naam) = lower(:cat)');
		$prm = array(':cat'=>$name);
		$stmt->execute($prm);
		$data = $stmt->fetch(PDO::FETCH_NUM);
		$Ret = array('PDORetCode'=>1);
		if ($data[0] > 0) {
			$Ret['unique'] = false;
		} else {
			$Ret['unique'] = true;
		}
	} catch(PDOException $e) {
		$Ret = array('PDORetCode'=>0);
	}
	return $Ret;
}

function isUsernameUnique($input, $dbh)
{
	try	{
		$stmt = $dbh->prepare('SELECT COUNT(gebruikersnaam) FROM gebruiker WHERE gebruikersnaam = :login');
		$prm = array(':login'=>$input);
		$stmt->execute($prm);
		$data = $stmt->fetch(PDO::FETCH_NUM);
		$Ret = array('PDORetCode'=>1);
		if ($data[0] > 0) {
			$Ret['unused'] = false;
		} else {
			$Ret['unused'] = true;
		}
	} catch(PDOException $e) {
		$Ret = array('PDORetCode'=>0);
	}
	return $Ret;
}

function checkSession() {
	if(!isset($_SESSION)) {
		session_start();
	}
}

function checkLogin($dbh, $userName, $passwd) {
  /**Error codes:
  0 -   Database error
  1 -   User exists
  2 -   User does not exists
  **/

  $Ret = array('PDORetCode'=>0);
  try {
    $stmt = $dbh->prepare('SELECT * FROM Gebruiker WHERE gebruikersnaam = :username');
    $prm = array(':username'=>$userName);
    $stmt->execute($prm);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    if($data == false) {
      $Ret = array('PDORetCode'=>2);
    } else {
      $Ret = array('PDORetCode'=>1, 'data'=>$data);
    }
  } catch(PDOException $e) {
		$Ret = array('PDORetCode'=>0);
	}
  return $Ret;
}

function getMainCategories($dbh) {
  $Ret = array('PDORetCode'=>0);
  try {
    $Sort = "ASC";
    if(getStoredSetting("CatSortMethod") == "alfa-za") {
      $Sort = "DESC";
    }
    $stmt = $dbh->prepare('SELECT * FROM Rubriek WHERE verwijzing = -1 ORDER BY naam '.$Sort);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $Ret = array('PDORetCode'=>1, 'data'=>$data);
  } catch(PDOException $e) {
		$Ret = array('PDORetCode'=>0, 'err'=>$e);
    var_dump($Ret);
	}
  return $Ret;
}

function getSubCategories($dbh, $categoryID) {
  try {
    $Sort = "ASC";
    if(getStoredSetting("CatSortMethod") == "alfa-za") {
      $Sort = "DESC";
    }
    $stmt = $dbh->prepare('SELECT DISTINCT(naam) as naam, nummer, verwijzing, volgnummer FROM Rubriek WHERE verwijzing = :catID ORDER BY naam '.$Sort);
    $prm = array(':catID'=>$categoryID);
    $stmt->execute($prm);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $Ret = array('PDORetCode'=>1, 'data'=>$data);
  } catch(PDOException $e) {
		$Ret = array('PDORetCode'=>0);
	}
  return $Ret;
}

function mainCategoriesDropdown($data) {
  $returnStr = '<option value="" selected disabled>Categorie...</option>';
  foreach($data['data'] as $val) {
    $returnStr = $returnStr.'<option class="mainCat" value="'.$val['nummer'].'" name="rubriek">'.$val['naam'].'</option>';
  }
  return $returnStr;
}

function CategoriesDropdown($data, $dbh, $disableMain) {
  $returnStr = '<option value="" selected disabled>Categorie...</option>';
  foreach($data['data'] as $val) {
    if($disableMain) {
      $returnStr = $returnStr.'<option class="mainCat" value="'.$val['nummer'].'" name="rubriek">'.$val['naam'].'</option>';
    } else {
      $returnStr = $returnStr.'<option class="mainCat" value="'.$val['nummer'].'" name="rubriek" disabled>'.$val['naam'].'</option>';
    }
    $subData = getSubCategories($dbh, $val['nummer']);
    foreach($subData['data'] as $subCat) {
      $returnStr = $returnStr.'<option class="subCat" value="'.$subCat['nummer'].'" name="rubriek">&nbsp;&nbsp;&nbsp;'.$subCat['naam'].'</option>';
    }
  }
  return $returnStr;
}

function getAllItems($dbh) {
  $Ret = array('PDORetCode'=>0);
	try	{
		$stmt = $dbh->prepare('select * from voorwerp
                  where (looptijdeindedag = convert(date, getdate()) and looptijdeindetijdstip >= format(getdate(), \'HH:mm\'))
	                or (looptijdeindedag > convert(date, getdate()))');
		$stmt->execute();
		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$Ret = array('PDORetCode'=>1, 'data'=>$data);
	} catch(PDOException $e) {
		$Ret = array('PDORetCode'=>0);
	}
	return $Ret;
}

function getTwentyItems($dbh, $search) {
  $Ret = array('PDORetCode'=>0);
	try	{
		$stmt = $dbh->prepare("select TOP(20) * from voorwerp
                  where titel LIKE CONCAT('%', :search1, '%') AND ((looptijdeindedag = convert(date, getdate()) and looptijdeindetijdstip >= format(getdate(), 'HH:mm'))
	                or (looptijdeindedag > convert(date, getdate())))");
    $param = array(':search1'=>$search);
		$stmt->execute($param);
		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$Ret = array('PDORetCode'=>1, 'data'=>$data);
	} catch(PDOException $e) {
		$Ret = array('PDORetCode'=>0);
    var_dump($e);
	}
	return $Ret;
}

function fourCheapestItem($dbh){
    $Ret = array('PDORetCode'=>0);
    try	{
        $stmt = $dbh->prepare('SELECT TOP 4 * from voorwerp
                    where (looptijdeindedag = convert(date, getdate()) and looptijdeindetijdstip >= format(getdate(), \'HH:mm\'))
	                    or (looptijdeindedag > convert(date, getdate()))
	                order by verkoopprijs asc');
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $Ret = array('PDORetCode'=>1, 'data'=>$data);
    } catch(PDOException $e) {
        $Ret = array('PDORetCode'=>0);
    }
    return $Ret;
}

function fourExpiringItems($dbh){
    $Ret = array('PDORetCode'=>0);
    try	{
        $stmt = $dbh->prepare('SELECT TOP 4 * from voorwerp
                        where (looptijdeindedag = convert(date, getdate()) and looptijdeindetijdstip >= format(getdate(), \'HH:mm\'))
	                        or (looptijdeindedag > convert(date, getdate()))
	                    order by looptijdeindedag asc');
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $Ret = array('PDORetCode'=>1, 'data'=>$data);
    } catch(PDOException $e) {
        $Ret = array('PDORetCode'=>0);
    }
    return $Ret;
}

function getItemImage($dbh, $itemNr) {
    $Ret = array('PDORetCode'=>0);
    try	{
        $stmt = $dbh->prepare('SELECT Bestandsnaam from Bestand where Voorwerpnummer = :nr');
        $prm = array(':nr'=>$itemNr);
        $stmt->execute($prm);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $Ret = array('PDORetCode'=>1, 'data'=>$data);
    } catch(PDOException $e) {
        $Ret = array('PDORetCode'=>0);
    }
    return $Ret;
}

function getLat($dbh, $postalCode){
    $Ret = array('PDORetCode'=>0);
    try{
        $stmt = $dbh->prepare('SELECT lat FROM PostcodeTest WHERE pcode = :pcode');
        $prm = array(':pcode'=>$postalCode);
        $stmt->execute($prm);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $Ret = array('PDORetCode'=>1, 'data'=>$data);
    } catch(PDOException $e) {
        $Ret = array('PDORetCode'=>0);
    }
    return $Ret;
}

function getLon($dbh, $postalCode){
    $Ret = array('PDORetCode'=>0);
    try{
        $stmt = $dbh->prepare('SELECT lon FROM PostcodeTest WHERE pcode = :pcode');
        $prm = array(':pcode'=>$postalCode);
        $stmt->execute($prm);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $Ret = array('PDORetCode'=>1, 'data'=>$data);
    } catch(PDOException $e) {
        $Ret = array('PDORetCode'=>0);
    }
    return $Ret;
}

function getPostalCodeCity($dbh, $postalCode) {
  $Ret = array('PDORetCode'=>0);
  try{
      $stmt = $dbh->prepare('SELECT * FROM PostcodeTest WHERE pcode = :pcode');
      $prm = array(':pcode'=>$postalCode);
      $stmt->execute($prm);
      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      $Ret = array('PDORetCode'=>1, 'data'=>$data);
  } catch(PDOException $e) {
      $Ret = array('PDORetCode'=>0);
  }
  return $Ret;
}

function calculateCurrencyToEuro($value, $Curr) {
  $ret = $value;
  if($Curr == "USD") {
    $ret = round(($value * 0.896346492), 2);
  } else if($Curr == "GBP") {
    $ret = round(($value * 1.1422322), 2);
  }
  return $ret;
}

function AVGLatItem($dbh, $city){
    try{
        $stmt = $dbh->prepare ('SELECT SUM(lat)/COUNT(*) as lat FROM PostcodeTest WHERE stad = :city');
        $prm = array(':city'=>$city);
        $stmt->execute($prm);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $Ret = array('PDORetCode'=>1, 'data'=>$data);
    } catch(PDOException $e) {
        $Ret = array('PDORetCode'=>0);
    }
    return $Ret;
}

function AVGLonItem($dbh, $city){
    try{
        $stmt = $dbh->prepare ('SELECT SUM(lon)/COUNT(*) as lon FROM PostcodeTest WHERE stad = :city');
        $prm = array(':city'=>$city);
        $stmt->execute($prm);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $Ret = array('PDORetCode'=>1, 'data'=>$data);
    } catch(PDOException $e) {
        $Ret = array('PDORetCode'=>0);
    }
    return $Ret;
}

function checkDistance($dbh, $postalCodeSearch, $city){
    $postalCodeSearchLat = getLat($dbh, $postalCodeSearch);
    $postalCodeItemLat = AVGLatItem($dbh, $city);
    $postalCodeSearchLon = getLon($dbh, $postalCodeSearch);
    $postalCodeItemLon = AVGLonItem($dbh, $city);

    return distance($postalCodeSearchLat['data'][0]['lat'], $postalCodeSearchLon['data'][0]['lon'], $postalCodeItemLat['data'][0]['lat'], $postalCodeItemLon['data'][0]['lon'], 'k');
}

function distance($lat1, $lon1, $lat2, $lon2, $unit) {
    // Source: https://www.geodatasource.com/developers/php

    if (($lat1 == $lat2) && ($lon1 == $lon2)) {
        return 0;
    }
    else {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }
}

function insertNewCategory($dbh, $name) {
  $Ret = array('PDORetCode'=>1);
	try	{
    $stmt = $dbh->prepare('SELECT TOP (1) nummer FROM Rubriek  order by nummer desc');
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_NUM);
    $newID = $data[0] + 1;

		$stmt = $dbh->prepare('INSERT Rubriek (nummer, naam, verwijzing) VALUES (:newID, :name, -1)');
		$prm = array(':newID'=>$newID, ':name'=>$name);
		$stmt->execute($prm);
	} catch(PDOException $e) {
		$Ret = array('PDORetCode'=>0);
    var_dump($e);
	}
	return $Ret;
}

function insertNewSubCategory($dbh, $ID, $name) {
  $Ret = array('PDORetCode'=>1);
	try	{
    $stmt = $dbh->prepare('SELECT TOP (1) nummer FROM Rubriek  order by nummer desc');
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_NUM);
    $newID = $data[0] + 1;

		$stmt = $dbh->prepare('INSERT Rubriek (nummer, naam, verwijzing) VALUES (:newID, :name, :ID)');
		$prm = array(':newID'=>$newID, ':name'=>$name, ':ID'=>$ID);
		$stmt->execute($prm);
	} catch(PDOException $e) {
		$Ret = array('PDORetCode'=>0);
	}
	return $Ret;
}

function changeCategoryName($dbh, $name, $ID) {
  $Ret = array('PDORetCode'=>1);
	try	{
    $stmt = $dbh->prepare('UPDATE Rubriek SET naam = :name WHERE nummer = :ID');
    $prm = array(':name'=>$name, ':ID'=>$ID);
    $stmt->execute($prm);
	} catch(PDOException $e) {
		$Ret = array('PDORetCode'=>0);
	}
	return $Ret;
}

function phaseOutCategory($dbh, $id) {
  $Ret = array('PDORetCode'=>0);
	try	{
    $stmt = $dbh->prepare('UPDATE Rubriek SET isuitgefaseerd = 1 WHERE nummer = :ID');
    $prm = array(':ID'=>$ID);
    $stmt->execute($prm);
    $Ret = array('PDORetCode'=>0);
	} catch(PDOException $e) {
		$Ret = array('PDORetCode'=>0);
	}
	return $Ret;
}

function getStoredSetting($setting) {
  $file = "../settings.json";
  $data = file_get_contents(dirname(__DIR__).$file, true);
  $settingsArr = json_decode($data, true);
  return $settingsArr[$setting];
}

function changeStoredSetting($setting, $value) {
  $file = "../settings.json";
  $data = file_get_contents(dirname(__DIR__).$file);
  $settingsArr = json_decode($data, true);
  $settingsArr[$setting] = $value;
  $newJSON = json_encode($settingsArr);
  file_put_contents($file, $newJSON);
}

function readBlockedUser($userName) {
  $file = "../blocked.json";
  $data = file_get_contents(dirname(__DIR__).$file, true);
  $arr = json_decode($data, true);
  if(isset($arr[$userName])) {
    return $arr[$userName];
  } else {
    return false;
  }
}

function checkBlockedUsers() {
  $file = "../blocked.json";
  $blockedUsers = readBlockedUsers();
  foreach($blockedUsers['Users'] as $userKey => $val) {
    if(isset($val['time']) && $val['time'] < time()) {
      $blockedUsers['Users'][$userKey] = array();
    }
  }
  $newJSON = json_encode($blockedUsers);
  file_put_contents(dirname(__DIR__).$file, $newJSON);
}

function readBlockedUsers() {
  $file = "../blocked.json";
  $data = file_get_contents(dirname(__DIR__).$file, true);
  $arr = json_decode($data, true);
  return $arr;
}

function addLoginTry($userName) {
  $file = "../blocked.json";
  $data = file_get_contents(dirname(__DIR__).$file);
  $arr = json_decode($data, true);
  if($arr['Users'][$userName]['tries'] > 2) {
    $arr['Users'][$userName]['time'] = time() + 1200;
  } else {
      if(empty($arr['Users'][$userName]['tries'])) {
          $arr['Users'][$userName]['tries'] = 0;
      }
    $arr['Users'][$userName]['tries'] = $arr['Users'][$userName]['tries'] + 1;
  }
  $newJSON = json_encode($arr);
  file_put_contents(dirname(__DIR__).$file, $newJSON);
}

function cleanInput($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

function addTempDBindex($dbh, $UID) {
    $Ret = 1;
    try	{
        $stmt = $dbh->prepare('INSERT INTO tempGebruiker(ID, unixtime) VALUES (:ID, :unixtime)');
        $prm = array(':ID'=>$UID, ':unixtime'=>time());
        $stmt->execute($prm);
    } catch(PDOException $e) {
        $Ret = 0;
    }
    return $Ret;
}

function checkMailUnique($dbh, $email) {
  $ret = 2;
  try {
    $stmt = $dbh->prepare("SELECT COUNT(*) FROM Gebruiker WHERE mailbox = :Email");
    $prm = array(':Email'=>$email);
    $stmt->execute($prm);
    $data = $stmt->fetch(PDO::FETCH_NUM);
    if($data[0] > 0) {
      $ret = 1;
    } else {
      $ret = 0;
    }
  }
  catch(PDOException $e) {
    $ret = 2;
  }
  return $ret;
}

function deleteTempUser($dbh, $email) {
  $query = "DELETE FROM tempGebruiker WHERE mailbox = :email";
  try {
    $stmt = $dbh->prepare($query);
    $prm = array(':email'=>$email);
    $stmt->execute($prm);
    $Ret = 1;
  }
  catch(PDOException $e) {
    $Ret = 0;
    var_dump($e);
  }
  return $Ret;
}

function checkTempMailUnique($dbh, $email) {
  $ret = 2;
  try {
    $stmt = $dbh->prepare("SELECT COUNT(*) FROM tempGebruiker WHERE mailbox = :Email");
    $prm = array(':Email'=>$email);
    $stmt->execute($prm);
    $data = $stmt->fetch(PDO::FETCH_NUM);
    if($data[0] > 0) {
      $ret = 1;
    } else {
      $ret = 0;
    }
  }
  catch(PDOException $e) {
    $ret = 2;
  }
  return $ret;
}

function addTempEmail($dbh, $value, $UID) {
    $Ret = 1;
    try	{
        $stmt = $dbh->prepare('UPDATE tempGebruiker SET mailbox = :val WHERE ID = :ID');
        $prm = array(':val'=>$value, ':ID'=>$UID);
        $stmt->execute($prm);
    } catch(PDOException $e) {
        $Ret = 0;
    }
    return $Ret;
}

function addTempUsername($dbh, $value, $UID) {
    $Ret = 1;
    try	{
        $stmt = $dbh->prepare('UPDATE tempGebruiker SET gebruikersnaam = :val WHERE ID = :ID');
        $prm = array(':val'=>$value, ':ID'=>$UID);
        $stmt->execute($prm);
    } catch(PDOException $e) {
        $Ret = 0;
    }
    return $Ret;
}

function addTempPassword($dbh, $value, $UID) {
    $Ret = 1;
    try	{
        $stmt = $dbh->prepare('UPDATE tempGebruiker SET wachtwoord = :val WHERE ID = :ID');
                $prm = array(':val'=>$value, ':ID'=>$UID);
        $stmt->execute($prm);
    } catch(PDOException $e) {
        $Ret = 0;
    }
    return $Ret;
}

function addTempFirstName($dbh, $value, $UID){
    $Ret = 1;
    try	{
        $stmt = $dbh->prepare('UPDATE tempGebruiker SET voornaam = :val WHERE ID = :ID');
        $prm = array(':val'=>$value, ':ID'=>$UID);
        $stmt->execute($prm);
    } catch(PDOException $e) {
        $Ret = 0;
    }
    return $Ret;
}

function addTempLastName($dbh, $value, $UID){
    $Ret = 1;
    try	{
        $stmt = $dbh->prepare('UPDATE tempGebruiker SET achternaam = :val WHERE ID = :ID');
        $prm = array(':val'=>$value, ':ID'=>$UID);
        $stmt->execute($prm);
    } catch(PDOException $e) {
        $Ret = 0;
    }
    return $Ret;
}

function addTempAdress($dbh, $value, $UID){
    $Ret = 1;
    try	{
        $stmt = $dbh->prepare('UPDATE tempGebruiker SET adres = :val WHERE ID = :ID');
        $prm = array(':val'=>$value, ':ID'=>$UID);
        $stmt->execute($prm);
    } catch(PDOException $e) {
        $Ret = 0;
    }
    return $Ret;
}

function addTempAddition($dbh, $value, $UID){
    $Ret = 1;
    try	{
        $stmt = $dbh->prepare('UPDATE tempGebruiker SET toevoeging = :val WHERE ID = :ID');
        $prm = array(':val'=>$value, ':ID'=>$UID);
        $stmt->execute($prm);
    } catch(PDOException $e) {
        $Ret = 0;
    }
    return $Ret;
}

function addTempPostalCode($dbh, $value, $UID){
    $Ret = 1;
    try	{
        $stmt = $dbh->prepare('UPDATE tempGebruiker SET postcode = :val WHERE ID = :ID');
        $prm = array(':val'=>$value, ':ID'=>$UID);
        $stmt->execute($prm);
    } catch(PDOException $e) {
        $Ret = 0;
    }
    return $Ret;
}

function addTempCity($dbh, $value, $UID){
    $Ret = 1;
    try	{
        $stmt = $dbh->prepare('UPDATE tempGebruiker SET plaatsnaam = :val WHERE ID = :ID');
        $prm = array(':val'=>$value, ':ID'=>$UID);
        $stmt->execute($prm);
    } catch(PDOException $e) {
        $Ret = 0;
    }
    return $Ret;
}

function addTempCountry($dbh, $value, $UID){
    $Ret = 1;
    try	{
        $stmt = $dbh->prepare('UPDATE tempGebruiker SET land = :val WHERE ID = :ID');
        $prm = array(':val'=>$value, ':ID'=>$UID);
        $stmt->execute($prm);
    } catch(PDOException $e) {
        $Ret = 0;
    }
    return $Ret;
}

function addTempBirthday($dbh, $value, $UID){
    $Ret = 1;
    try	{
        $stmt = $dbh->prepare('UPDATE tempGebruiker SET geboortedag = :val WHERE ID = :ID');
        $prm = array(':val'=>$value, ':ID'=>$UID);
        $stmt->execute($prm);
    } catch(PDOException $e) {
        $Ret = 0;
    }
    return $Ret;
}

function addTempPhone1($dbh, $value, $UID){
    $Ret = 1;
    try	{
        $stmt = $dbh->prepare('UPDATE tempGebruiker SET telefoon1 = :val WHERE ID = :ID');
        $prm = array(':val'=>$value, ':ID'=>$UID);
        $stmt->execute($prm);
    } catch(PDOException $e) {
        $Ret = 0;
    }
    return $Ret;
}

function addTempPhone2($dbh, $value, $UID){
    $Ret = 1;
    try	{
        $stmt = $dbh->prepare('UPDATE tempGebruiker SET telefoon2 = :val WHERE ID = :ID');
        $prm = array(':val'=>$value, ':ID'=>$UID);
        $stmt->execute($prm);
    } catch(PDOException $e) {
        $Ret = 0;
    }
    return $Ret;
}

function addTempCode($dbh, $value, $UID){
    $Ret = 1;
    try	{
        $stmt = $dbh->prepare('UPDATE tempGebruiker SET verificatie = :val WHERE ID = :ID');
        $prm = array(':val'=>$value, ':ID'=>$UID);
        $stmt->execute($prm);
    } catch(PDOException $e) {
        $Ret = 0;
    }
    return $Ret;
}

function getTempCode($dbh, $UID){
    try	{
        $stmt = $dbh->prepare('SELECT verificatie FROM tempGebruiker where ID = :ID');
        $prm = array(':ID'=>$UID);
        $stmt->execute($prm);
        $Ret = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $Ret = 0;
    }
    return $Ret;
}

function deleteUser($dbh, $userName) {
  $query = "DELETE FROM Gebruikertelefoon WHERE gebruikersnaam = :user1; DELETE FROM Gebruiker WHERE gebruikersnaam = :user2";
  try {
    $stmt = $dbh->prepare($query);
    $prm = array(':user1'=>$userName, ':user2'=>$userName);
    $stmt->execute($prm);
    $Ret = 1;
  }
  catch(PDOException $e) {
    $Ret = 0;
    var_dump($Ret);
  }
  return $Ret;
}

function emptyTempDB($dbh, $UID){
    $Ret = array('PDORetCode'=>1);
    try	{
        $stmt = $dbh->prepare('DELETE tempGebruiker WHERE ID = :ID');
        $prm = array(':ID'=>$UID);
        $stmt->execute($prm);
    } catch(PDOException $e) {
        $Ret = array('PDORetCode'=>0);
    }
    return $Ret;
}

function getTempInfo($dbh, $UID){
    try	{
        $stmt = $dbh->prepare('SELECT gebruikersnaam, mailbox, voornaam, achternaam, geboortedag,
                                      telefoon1, telefoon2, adres, toevoeging, postcode, plaatsnaam, land, wachtwoord
                                FROM tempGebruiker where ID = :ID');
        $prm = array(':ID'=>$UID);
        $stmt->execute($prm);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $Ret = array('PDORetCode'=>1, 'data'=>$data);
    } catch(PDOException $e) {
        $Ret = array('PDORetCode'=>0);
    }
    return $Ret;
}

function addUserDB($dbh, $username, $email, $firstname, $lastname, $birthday,
                   $adress, $addition, $postalcode, $city, $country, $passwd){
    $Ret = 1;
    try	{
        $stmt = $dbh->prepare('INSERT INTO Gebruiker(gebruikersnaam, mailbox, voornaam, achternaam, geboortedag,
                                      adres, toevoeging, postcode, plaatsnaam, land, wachtwoord, isverkoper, isbeheerder)
                                VALUES (:username, :email, :firstname, :lastname, :birthday,
                                        :adress, :addition, :postalcode, :city, :country, :passwd, 0, 0)');
        $prm = array(':username'=>$username, ':email'=>$email, ':firstname'=>$firstname, ':lastname'=>$lastname,
                    ':birthday'=>$birthday, ':adress'=>$adress, ':addition'=>$addition, ':postalcode'=>$postalcode,
                    ':city'=>$city, ':country'=>$country, ':passwd'=>$passwd);
        $stmt->execute($prm);
    } catch(PDOException $e) {
        var_dump($e);
        var_dump($prm);
        $Ret = 0;
    }
    return $Ret;
}

function addUserPhoneDB($dbh, $username, $phone){
    $Ret = 1;
    try	{
        $stmt = $dbh->prepare('INSERT INTO Gebruikertelefoon(gebruikersnaam, telefoon)
                              VALUES (:username, :phone)');
        $prm = array(':username'=>$username, ':phone'=>$phone);
        $stmt->execute($prm);
    } catch(PDOException $e) {
        var_dump($e);
        $Ret = 0;
    }
    return $Ret;
}

function includeWithVariable($filepath, $variabele = array(), $print = true)
{
    $inhoud = NULL;
    if(file_exists($filepath))
    {
        // extracts the variables from the asocietive array
        extract($variabele);

        // Start output buffering
        ob_start();

        // Include the template file
        include $filepath;

        // End buffering and return its contents
        $content = ob_get_clean();

    }

    if ($print)
    {
        print $content;
    }
    return $content;

}

function generatePasswdToken($dbh, $email) {
  $continue = false;
  $time = time();
  $hashStr = $email.$time;
  $token = password_hash($hashStr, PASSWORD_DEFAULT);
  try {
    $stmt = $dbh->prepare('UPDATE Gebruiker SET token = :token, tokentijd = :tokenTime WHERE mailbox = :email');
    $param = array(':token'=>$token, ':tokenTime'=>$time, ':email'=>$email);
    $stmt->execute($param);
    $continue = true;
   }
   catch(PDOException $e) {
     var_dump($e);
     $continue = false;
   }
   if($continue) {
     return $token;
   }
}

function emailPasswordToken($dbh, $email, $token) {
  try {
    $stmt = $dbh->prepare('SELECT voornaam, achternaam FROM Gebruiker WHERE mailbox = :email');
    $param = array(':email'=>$email);
    $stmt->execute($param);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $continue = true;
  }
  catch(PDOException $e) {
    var_dump($e);
    $continue = false;
  }
  if($continue) {
    // Subject
    $subject = 'Wachtwoordherstel EenmaalAndermaal';

    // Message
    $message = '
    <html>
    <head>
    <title>Toeter</title>
    </head>
    <body>
    <h1>Wachtwoordherstel EenmaalAndermaal</h1><br>
    <p>Geachte '.$data['voornaam'].' '.$data['achternaam'].',<br><br>U heeft recentelijk een wachtwoordherstel aangevraagd. Volg de volgende link om uw wachtworod te herstellen:<br><br><a href="https://iproject36.icasites.nl/wachtwoordvergeten/nieuwWachtwoordValideer.php?token='.urlencode($token).'">Wachtwoord herstellen</a><br><br></p>
    </body>
    </html>
    ';

    // To send HTML mail, the Content-type header must be set
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';

    // Additional headers
    $headers[] = 'To: '.$email;
    $headers[] = 'From: Eenmaal Andermaal No-Reply <no-reply@eenmaalandermaal.com>';

    // Mail it
    return mail($email, $subject, $message, implode("\r\n", $headers));
  }
}

function verifyToken($dbh, $token) {
  $ret = array('data'=>array(), 'code'=>2);
  try {
    $stmt = $dbh->prepare('SELECT mailbox, tokentijd FROM Gebruiker WHERE token = :token');
    $param = array(':token'=>$token);
    $stmt->execute($param);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
  }
  catch(PDOException $e) {
    //var_dump($e);
    $ret = array('data'=>array(), 'code'=>2);
  }
  if(password_verify($data['mailbox'].$data['tokentijd'], $token)) {
    $ret = array('data'=>$data, 'code'=>1);
  } else {
    $ret = array('data'=>array('code'=>$token), 'code'=>0);
  }
  return $ret;
}

function updatePassword($dbh, $passwd, $email) {
  $ret = 0;
  try {
    $stmt = $dbh->prepare('UPDATE Gebruiker SET wachtwoord = :passwd, token = NULL, tokentijd = NULL WHERE mailbox = :mailbox');
    $param = array(':passwd'=>$passwd, ':mailbox'=>$email);
    $stmt->execute($param);
    $ret = 1;
  }
  catch(PDOException $e) {
    //var_dump($e);
    $ret = 0;
  }
  return $ret;
}
?>
