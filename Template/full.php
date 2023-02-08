<!DOCTYPE html>
<html lang="en" class="h-100">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Louis Ouellet">
  	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
    <?= $this->coreDB->getCSS() ?>
    <title><?= $this->coreDB->getBrand() ?> | <?= $this->getLabel() ?></title>
    <?= $this->coreDB->getJS('head') ?>
  </head>
  <body class="h-100 bg-dark background">
    <main class="d-flex align-items-center flex-column justify-content-center h-100">
      <div class="d-flex align-items-center justify-content-center bg-light shadow-lg px-5 rounded-5">
        <form method="post" class="form-md text-center user-select-none">
          <?php require $this->getViewFile(); ?>
          <p class="mt-5 mb-4 text-muted">&copy; 2017–<?= date('Y'); ?></p>
        </form>
      </div>
    </main>
    <?= $this->coreDB->getJS('body',['cookie.js']) ?>
  </body>
</html>
