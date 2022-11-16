<!DOCTYPE html>
<html lang="en" class="h-100">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/vendor/laswitchtech/bootstrap-panel/dist/css/BSPanel.css">
    <link rel="stylesheet" href="/vendor/twbs/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/dist/css/stylesheet.css">
    <title>Forgot Password</title>
  </head>
  <body class="h-100 bg-dark background">
    <main id="register" class="d-flex align-items-center justify-content-center h-100">
      <div class="d-flex align-items-center justify-content-center bg-light shadow-lg px-5 rounded-5">
        <form class="text-center user-select-none">
          <!-- <img src="/dist/img/logo.png" class="img-fluid mx-auto d-block mb-5" style="max-height: 200px" alt="Logo"> -->
          <p><h1 class="display-5 pt-5">Forgot Password?</h1></p>
          <div class="form-floating mt-5 mb-3">
            <input type="email" class="form-control shadow" id="floatingInput" name="username" autocomplete="username" placeholder="name@example.com">
            <label for="floatingInput">Email address</label>
          </div>
          <div class="d-flex justify-content-between py-3">
            <button class="shadow btn btn-lg btn-primary flex-grow-1 mx-1" type="submit"><i class="bi-arrow-counterclockwise me-2"></i>Reset Password</button>
            <a href="/signin" class="btn btn-lg btn-light shadow mx-1"><i class="bi-box-arrow-in-right me-2"></i>Sign In</a>
          </div>
          <p class="mt-5 mb-4 text-muted">&copy; 2017–<?= date('Y'); ?></p>
        </form>
      </div>
    </main>
    <script src="/vendor/components/jquery/jquery.min.js"></script>
    <script src="/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/vendor/laswitchtech/bootstrap-panel/dist/js/BSPanel.js"></script>
    <script src="/dist/js/script.js"></script>
  </body>
</html>
