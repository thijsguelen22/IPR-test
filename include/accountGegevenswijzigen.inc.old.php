<?php
//dit pagina kan verwijderd worden
require_once './db_connector.php';
require_once './globalFunctions.inc.php';
checkSession();




if(isset($_POST['wijzigknop'])){
  $edit ='';
  $ret = 0;
  header("Location: ../account.php");
  //$userName = $_SESSION['UserAccount']['UserName'];
  // $fName    =
  // $query = "UPDATE  Gebruiker
  //           SET voornaam =          ,
  //               achternaam=         ,
  //               postcode=           ,
  //               plaatsnaam=         ,
  //               land=               ,
  //               geboortedag=        ,
  //               mailbox= 
  //           WHERE   gebruikersnaam = :user";
  // try {
  //   $stmt = $dbh->prepare($query);
  //   $prm = array(':user'=>$userName);
  //   $stmt->execute($prm);
  //   $Ret = 1;
  // }
  // catch(PDOException $e) {
  //   $Ret = 0;
  // }
}

?>
