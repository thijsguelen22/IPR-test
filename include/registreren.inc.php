<?php
    $Ret = 0;
    var_dump($_session['registerForm']);
    try {
      $stmt = $dbh->prepare('INSERT INTO Gebruiker(gebruikersnaam, voornaam, achternaam, adres, toevoeging, postcode, plaatsnaam, land, geboortedag, mailbox, wachtwoord, isverkoper) VALUES (:Username, :fName, :lName, :adress, :addition, :postalCode, :city, :country, :birthday, :mailBox, :Password, 0)');
      $prm = array(':Username'=>$_session['registerForm']['username'],
                  ':fName'=>$_session['registerForm']['firstname'],
                  ':lName'=>$_session['registerForm']['lastname'],
                  ':adress'=>$_session['registerForm']['adress'],
                  ':addition'=>$_session['registerForm']['addition'],
                  ':postalCode'=>$_session['registerForm']['postalcode'],
                  ':city'=>$_session['registerForm']['city'],
                  ':country'=>$_session['registerForm']['country'],
                  ':birthday'=>$_session['registerForm']['birthday'],
                  ':mailBox'=>$_session['registerForm']['email'],
                  ':Password'=>$_session['registerForm']['password'],
          );
      $stmt->execute($prm);
      $Ret = 1;
    } catch(PDOException $e) {
      $Ret = 0;
    }
    return $Ret;
    ?>