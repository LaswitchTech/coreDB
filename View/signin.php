<!doctype html>
<html lang="en" class="h-100">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/vendor/laswitchtech/bootstrap-panel/dist/css/BSPanel.css">
    <link rel="stylesheet" href="/vendor/twbs/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/dist/css/stylesheet.css">
    <title>Sign In</title>
  </head>
  <body class="h-100">
    <main class="d-flex align-items-center justify-content-center h-100">
      <form class="text-center w-25 mw-100">
        <p><h1 class="h3 fw-normal">Please sign in</h1></p>
        <div class="form-floating mb-3">
          <input type="email" class="form-control" id="floatingInput" name="username" placeholder="name@example.com">
          <label for="floatingInput">Email address</label>
        </div>
        <div class="form-floating mb-3">
          <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password">
          <label for="floatingPassword">Password</label>
        </div>
        <div class="form-check form-check-inline mb-3">
          <input class="form-check-input" type="checkbox" value="remember" name="remember" id="flexCheckDefault">
          <label class="form-check-label" for="flexCheckDefault">Remember me</label>
        </div>
        <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
        <p class="mt-5 mb-3 text-muted">&copy; 2017–2021</p>
      </form>
    </main>
    <script src="/vendor/components/jquery/jquery.min.js"></script>
    <script src="/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/vendor/laswitchtech/bootstrap-panel/dist/js/BSPanel.js"></script>
    <script src="/dist/js/script.js"></script>
  </body>
</html>
