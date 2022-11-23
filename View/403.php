<!DOCTYPE html>
<html lang="en" class="h-100">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/vendor/laswitchtech/bootstrap-panel/dist/css/BSPanel.css">
    <link rel="stylesheet" href="/vendor/twbs/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/dist/css/stylesheet.css">
    <title>403 - Permission Denied</title>
  </head>
  <body class="h-100">
    <main class="d-flex align-items-center justify-content-center h-100">
      <div class="text-center">
        <p><h1>Oops!</h1></p>
        <p><h2>403 Permission Denied</h2></p>
        <p>Sorry, an error has occured, You do not have access to the requested page!</p>
        <p>
          <a href="<?= $this->coreDB->getBack('route') ?>" class="shadow btn btn-primary btn-lg"><i class="bi-sign-turn-left me-2"></i>Take Me Back</a>
          <a href="/support" class="shadow btn btn-light btn-lg"><i class="bi-envelope me-2"></i>Contact Support</a>
        </p>
      </div>
    </main>
  </body>
</html>