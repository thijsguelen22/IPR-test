<div class="row">
  <div class="column">
    <div class="register">
      <form class="callout text-center" action="" method="post">
        <h2>nieuw wachtwoord aanmaken</h2>
        <hr>
        <div class="column">
          <div class="column">
            <strong><p class="text-left">Geef uw nieuwe wachtwoord op</p></strong>
              <input name="passwd" type="password" value="" placeholder="Wachtwoord">
              <p class="form-error" style="display: block"><?php echo $passwdErr; ?></p>
          </div>
          <div class="column">
            <strong><p class="text-left">Herhaal uw wachtwoord</p></strong>
              <input name="passwd-repeat" type="password" value="" placeholder="Wachtwoord bevestigen">
              <p class="form-error" style="display: block"><?php echo $rePasswdErr; ?></p>
          </div>
          <div class="column">
            <input class="button expanded" name="submit" type="submit" value="Volgende">
          </div>

        </div>
      </form>
    </div>
  </div>
</div>
