<div class="row">
  <div class="column">
    <div class="register">
      <form class="callout text-center" action="" method="post">
        <h2>vraag nieuw wachtwoord aan</h2>
        <hr>
        <div class="column">
          <div class="column">
            <strong><p class="text-left">Geef uw email op </p></strong>
            <div class="floated-label-wrapper">
              <input name="email" type="email" value="" placeholder="Email">
              <p class="form-error" style="display: block"><?php echo $emailErr; ?></p>
            </div>
          </div>
          <div class="column">
            <input class="button expanded" name="submit" type="submit" value="Volgende">
          </div>

        </div>
      </form>
    </div>
  </div>
</div>
