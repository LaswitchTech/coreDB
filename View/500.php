<?php var_dump($this->getView(),$this->getRoute()); ?>
<?php if(strval($this->getRoute()) == '500'){ ?>
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
    <body class="h-100">
      <?php } ?>
        <main class="d-flex align-items-center justify-content-center h-100">
          <div class="text-center">
            <p><h1>Oops!</h1></p>
            <p><h2>500 Internal Server Error</h2></p>
            <p>Sorry, an error has occured, an internal server error occured!</p>
            <p>
              <a href="<?= $this->coreDB->getBack('route') ?>" class="shadow btn btn-primary btn-lg"><i class="bi-sign-turn-left me-2"></i>Take Me Back</a>
              <a href="/support" class="shadow btn btn-light btn-lg"><i class="bi-envelope me-2"></i>Contact Support</a>
            </p>
          </div>
        </main>
      <?php if(strval($this->getRoute()) == '500'){ ?>
      <?= $this->coreDB->getJS('body',['cookie.js']) ?>
    </body>
  </html>
<?php } ?>
