<?php 
var_dump($_POST);
echo
'
    <input name="test" class="button round" type="submit" value="Opslaan" />                        ';


if (isset($_POST['UserName'])) {
    $ret = 0;

    $userName = $_SESSION['UserAccount']['UserName'];
    $firstName = $_POST['firstname'];
    $lastName  = $_POST['lastname'];
    $postalcode = $_POST['postalcode'];
    $place = $_POST['place'];
    $country = $_POST['country'];
    $birthday = $_POST['birthdate'];

    $query = "UPDATE  Gebruiker
              SET   voornaam =   :firstname
              WHERE gebruikersnaam = :user";
    try {
        var_dump($firstName);
        $stmt = $dbh->prepare($query);
        $prm = array(
            ':user' => $userName,  ':firstname' => $firstName
        );
        $stmt->execute($prm);
        $Ret = 1;
    } catch (PDOException $e) {
        $Ret = 0;
        var_dump($e);
    }
    header("Location: ../account.php");
}

?>


<div class="columns small-6">
                        <p>Voornaam</p>
                        <input name="firstname" type="text" placeholder="Voornaam" >
                    </div>