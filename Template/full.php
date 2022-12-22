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
    <link rel="stylesheet" href="/vendor/laswitchtech/bootstrap-panel/dist/css/BSPanel.css">
    <link rel="stylesheet" href="/vendor/twbs/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/dist/css/jquery-ui.min.css">
    <link rel="stylesheet" href="/dist/css/select2.min.css">
    <?php foreach($this->coreDB->getFiles('/dist/css/',['jquery-ui.min.css','select2.min.css','coreDB.css']) as $file){ ?>
      <link rel="stylesheet" href="/dist/css/<?= $file ?>">
    <?php } ?>
    <link rel="stylesheet" href="/dist/css/coreDB.css">
    <title><?= $this->coreDB->getBrand() ?> | <?= $this->getLabel() ?></title>
    <script src="/vendor/components/jquery/jquery.min.js"></script>
    <script src="/dist/js/jquery-ui.min.js"></script>
    <script>
      $.holdReady(true)
    </script>
  </head>
  <body class="h-100 bg-dark background">
    <main class="d-flex align-items-center flex-column justify-content-center h-100">
      <div class="d-flex align-items-center justify-content-center bg-light shadow-lg px-5 rounded-5">
        <form method="post" class="form-md text-center user-select-none">
          <?php $this->getView(); ?>
          <p class="mt-5 mb-4 text-muted">&copy; 2017–<?= date('Y'); ?></p>
        </form>
      </div>
    </main>
    <script>
      const CSRF = '<?= $this->Auth->CSRF->token() ?>'
    </script>
    <script src="/vendor/rmm5t/jquery-timeago/jquery.timeago.js"></script>
    <script src="/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/vendor/laswitchtech/bootstrap-panel/dist/js/BSPanel.js"></script>
    <script src="/vendor/laswitchtech/php-api/dist/js/phpAPI.js"></script>
    <script src="/dist/js/jquery.dataTables.min.js"></script>
    <script src="/dist/js/dataTables.bootstrap5.min.js"></script>
    <?php foreach($this->coreDB->getFiles('/dist/js/',['jquery-ui.min.js','jquery.dataTables.min.js','dataTables.bootstrap5.min.js','coreDB.js']) as $file){ ?>
      <script src="/dist/js/<?= $file ?>"></script>
    <?php } ?>
    <script src="/dist/js/coreDB.js"></script>
  </body>
</html>
