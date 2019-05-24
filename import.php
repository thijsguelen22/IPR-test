<?php
/*
Echt een tyfus werk
Al die .sql files met items hebben descriptions waar ' of " in voorkomt, waardoor de hele insert op zn bek gaat
geen idee hoe we dat eruit moeten gaan halen

*/
//s$old = ini_set('memory_limit', '8192M');
ini_set('memory_limit', '4095M');

//naam van batches knop: name="batchknop"
$count = 0;

require_once($_SERVER["DOCUMENT_ROOT"] . "/include/db_connector.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/include/globalFunctions.inc.php");

$hostname = "(localhost)";
$user = "";
$pass = "";
$db = "(db)";
try
{
  $dbhConvert = new PDO("sqlsrv:Server=$hostname; ConnectionPooling=0", "$user", "$pass");
  $dbhConvert->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $dbhConvert->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);
}
catch (PDOException $e) {
  die ( "Fout met de database: {$e->getMessage()} " );
}

try{
  echo "table dropped";
  $dbhConvert->exec("DROP DATABASE IF EXISTS IprojectConvert");
  $dbhConvert->exec("CREATE DATABASE IprojectConvert");
  $dbhConvert->exec("USE IprojectConvert");
}
catch(PDOException $e) {
  print_r($e);
}

header('Content-Type: text/html; charset=utf-8');
$directories = array(
    './sql/1-Verzamelen/',
    './sql/12576-Industrie en kantoor/'
);

function selectTable($dbh, $query) {
  try {
    $stmt = $dbh->query($query);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  catch(PDOException $e) {
    //echo "oopsie";
    echo "fout select table<br>".'\n\r';
  }
  return $data;
}

function insertNewRow($dbh, $data, $query) {
  try {
    $stmt = $dbh->prepare($query);
    $stmt->execute($data);
    if(isset($data[':ID']) && isset($data[':Name']) && isset($data[':Parent'])) {
      //echo "poging tot insert ".$data[':ID']."   ".$data[':Name']."<br>";
    }
  } catch(PDOException $e) {
    if(isset($data[':ID']) && isset($data[':Name']) && isset($data[':Parent'])) {
      //echo "<br>STUKKIE WUKKIE".$data[':ID']."   ".$data[':Name']."<br><br>";
    }
    //echo $query;

    //print_r($data);
    print_r($e);
  }
}

function insertFileToDB($dbh, $file, $convert) {
    try	{
        //$dbh->exec(mb_convert_encoding(file_get_contents($file),"UTF-8","Windows-1252"));
        if($convert) {
          $dbh->exec(mb_convert_encoding(file_get_contents($file),"UTF-8","Windows-1252"));
        } else {
          $dbh->exec(file_get_contents($file));
        }
        echo "--------------------";
        echo '<br>';
        //echo $data."\n";
        echo "file $file inserted";
        echo '<br><br>';
        //echo $count." ";

    } catch(PDOException $e) {
      echo "fout insert<br>".'\n\r';

        echo "------FAILED QUERY----";
        echo '\n';
        //echo $data;
        //print_r($e);
        echo '\n';
        echo "--------------";
        echo '\n';
    }
}

function executeQuery($dbh, $q) {
  try {
    $dbh->query($q);
  }
  catch(PDOException $e) {
    print_r($e);
    //echo "fout exec query<br>".'\n\r';
  }
}
echo "backup<br>";
$q = "BACKUP DATABASE IprojectDEV
TO DISK = 'C:\IprojectDEV.bak'";
executeQuery($dbh, $q);
echo "backup done<br>";
function insertFile($dbhConvert, $dir, $file, $count, $convert) {
  $insert = true;
  $newString = substr($file,-4);
    if($newString == '.sql'){
      echo "+++++++ INSERTING ".$dir.$file.'+++++++';
      echo '<br>';
      insertFileToDB($dbhConvert, $dir.$file, $convert);
    }
}

$filesFirst = array('CREATE Tables.sql', 'CREATE Categorieen.sql');
insertFile($dbhConvert, "./sql/", "CREATE Tables.sql", $count, false);
insertFile($dbhConvert, "./sql/", "CREATE Categorieen.sql", $count, true);

foreach($directories as $dir) {
    $files = scandir($dir);
    foreach($files as $file) {
        if($file != "CREATE Users.sql") {
          echo "inserting file ".$file;
          insertFile($dbhConvert, $dir, $file, $count, false);
        }
    }
}


//---- CATEGORIEEN NAAR DATABASE
echo "convert categorieen to rubriek<br>";
$data = selectTable($dbhConvert, "SELECT * FROM Categorieen");

$q = "INSERT INTO Rubriek (nummer,naam,verwijzing) VALUES(:ID, :Name, :Parent)";
echo "asdfsadsfasdf";
foreach($data as $d) {
  echo count($data);
  if($d['ID'] != NULL && $d['Name'] != NULL && $d['Parent'] != NULL) {
    $prm = array(':ID'=>$d['ID'],':Name'=>$d['Name'],':Parent'=>$d['Parent']);
    insertNewRow($dbh, $prm, $q);
  } else {
    echo "not suitable:<br>";
    print_r($d);
  }
}

echo "converting items to voorwerp<br>";

$query = "BEGIN
   IF NOT EXISTS (SELECT * FROM Gebruiker
                   WHERE gebruikersnaam = 'EenmaalAndermaal')
   BEGIN
       INSERT INTO Gebruiker (gebruikersnaam, mailbox, wachtwoord, isverkoper, isbeheerder)
       VALUES ('EenmaalAndermaal', 'info@eenmaalandermaal.com', 'test', 1, 1)
	   INSERT INTO Verkoper (gebruikersnaam, controloptie) VALUES ('EenmaalAndermaal', 'test')
   END
END";

echo "create seller for items<br><br>";
executeQuery($dbh, $query);



$data = selectTable($dbhConvert, "SELECT COUNT(*) as ctn FROM Items");
var_dump($data[0]['ctn']);
$amountOfLoops = ceil($data[0]['ctn']/1000);
echo 'amount of loops is '.$amountOfLoops;
for($i=0;$i<$amountOfLoops;$i++) {
  $data = selectTable($dbhConvert, "select * from Items ORDER BY ID OFFSET ".($i*1000)." rows FETCH NEXT 1000 ROWS ONLY;");

  $q = 'SET IDENTITY_INSERT voorwerp ON; INSERT INTO Voorwerp (voorwerpnummer, titel, beschrijving, plaatsnaam, land, verkoper, startprijs, looptijdbegindag, looptijdbegintijdstip, looptijdeindedag, looptijdeindetijdstip, veilingstatus, betalingswijze, looptijd, verkoopprijs) VALUES(:id, :title, :descr, :city, :country, :seller, :price, :startDay, :startTime, :endDay, :endTime, 1, :paymentMethod, 7, :sellPrice);';
  foreach($data as $d) {
    if($d['Postcode'] != NULL) {
      $city = getPostalCodeCity($dbh, $d['Postcode']);
      if(isset($city['Stad']) && $city != false) {
        $itemCity = $city['Stad'];
      } else {
        $itemCity = "nvt";
      }
    } else {
      $itemCity = "nvt";
    }

    $newDesc = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $d['Beschrijving']);
    $newDesc = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $newDesc);
    $newDesc = strip_tags($newDesc);
    $newDesc = str_replace(array("\r", "\n"), ' ', $newDesc);

    $startDay = date("Y-m-d");
    $endDay = date('Y-m-d', time() + 604800);
    $startTime = $endTime = date('H:i');
    $price = calculateCurrencyToEuro($d['Prijs'], $d['Valuta']);

    //$seller = $d['Verkoper']
    $seller = "EenmaalAndermaal";

    $prm = array(':id'=>$d['ID'], ':title'=>$d['Titel'], ':descr'=>$newDesc, ':city'=>$itemCity, ':country'=>$d['Land'], ':seller'=>$seller, ':price'=>$price, ':startDay'=>$startDay, ':startTime'=>$startTime, ':endDay'=>$endDay, ':endTime'=>$endTime, ':paymentMethod'=>"Anders", ':sellPrice'=>$price);
    insertNewRow($dbh, $prm, $q);
  }
}

//

$data = selectTable($dbhConvert, "SELECT * FROM Illustraties");
//var_dump($data);
$q = 'INSERT INTO Bestand (Voorwerpnummer, Bestandsnaam) VALUES(:id, :file)';
foreach($data as $d) {
  $prm = array(':id'=>$d['ItemID'], ':file'=>$d['IllustratieFile']);
  insertNewRow($dbh, $prm, $q);
}
//rmdir('./sql');
/*
$data = selectTable($dbhConvert, "SELECT * FROM items");
foreach($data as $d) {
  $newDesc = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $d['Beschrijving']);
  $newDesc = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $newDesc);
  $newDesc = strip_tags($newDesc);
  $newDesc = str_replace(array("\r", "\n"), ' ', $newDesc);
  $newData[$i]['Beschrijving'] = $newDesc;

  $prm =
  insertNewRow($dbh, $prm, $q);
}*/
//$data = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $data);
//$data = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $data);
//print_r($newData);

try {
  //$dbhConvert->exec("DROP DATABASE IF EXISTS IprojectConvert");
}
catch(PDOException $e) {
  //var_dump($e);
}
?>
