<h1>PHP Information</h1>
<p style="text-align:center">
  <a href="/" style="margin-left: 4px;margin-right: 4px;">Index</a>
  <a href="/info" style="margin-left: 4px;margin-right: 4px;">Info</a>
  <a href="/signin" style="margin-left: 4px;margin-right: 4px;">Sign In</a>
  <a href="?signout&csrf=<?= $this->Auth->CSRF->token() ?>" style="margin-left: 4px;margin-right: 4px;">Sign Out</a>
</p>
<?php if(isset($_SESSION)){ ?>
  <p>_SESSION: <?= json_encode($_SESSION, JSON_PRETTY_PRINT) ?></p>
<?php } ?>
<?php if(isset($_COOKIE)){ ?>
  <p>_COOKIE: <?= json_encode($_COOKIE, JSON_PRETTY_PRINT) ?></p>
<?php } ?>
<?php if(isset($_POST)){ ?>
  <p>_POST: <?= json_encode($_POST, JSON_PRETTY_PRINT) ?></p>
<?php } ?>
<p>
  <?= json_encode($this->Auth->getDiag(), JSON_PRETTY_PRINT) ?>
</p>
<?= phpinfo(); ?>
