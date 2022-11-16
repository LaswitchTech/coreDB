<!DOCTYPE html>
<html lang="en" class="vh-100">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/vendor/laswitchtech/bootstrap-panel/dist/css/BSPanel.css">
    <link rel="stylesheet" href="/vendor/twbs/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/dist/css/stylesheet.css">
    <title>Index</title>
  </head>
  <body class="vh-100 overflow-hidden background">
    <main id="navbar">
      <nav class="navbar navbar-expand fixed-top bg-light shadow">
        <div class="d-flex align-items-center container-fluid">
          <ul class="nav nav-pills my-1">
            <li class="nav-item">
              <a class="nav-link active shadow" aria-current="page" href="#">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link link-dark" href="#">Link</a>
            </li>
          </ul>
          <form class="d-flex mx-2 flex-grow-1">
            <input class="form-control w-100 shadow-sm" type="search" placeholder="Search" aria-label="Search">
          </form>
          <div class="dropdown profile">
            <a href="#" class="d-flex align-items-center mx-2 link-dark text-decoration-none" id="UserMenu" data-bs-toggle="dropdown" aria-expanded="false">
              <img src="<?= $this->getGravatar('louis@albcie.com') ?>" alt="Avatar" width="40" height="40" class="rounded-circle shadow me-2">
              louis@albcie.com
            </a>
            <ul class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="UserMenu">
              <li class="shadow d-flex align-items-center flex-column rounded-top bg-primary text-light py-4">
                <div class="shadow border border-light border-4 rounded-circle">
                  <img src="<?= $this->getGravatar('louis@albcie.com') ?>" class="img-circle border border-primary border-4 rounded-circle" width="128" height="128" alt="Avatar">
                </div>
                <span class="mt-2">louis@albcie.com</span>
                <small>Member since Nov. 2012</small>
              </li>
              <li class="d-flex justify-content-around py-3">
                <a href="#" class="btn btn-light shadow"><i class="bi-person-circle me-2"></i>Profile</a>
                <a href="#" class="btn btn-light shadow ml-auto"><i class="bi-box-arrow-right me-2"></i>Sign out</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </main>
    <main id="sidebar">
      <div class="d-flex flex-column flex-shrink-0 p-3 h-100 bg-light fixed-top shadow-lg">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
          <img src="/dist/img/logo.png" class="img-fluid me-2" style="max-height: 32px;max-width: 40px" alt="Logo">
          <span class="fs-4">coreDB</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
          <li class="nav-item">
            <a href="#" class="nav-link active shadow" aria-current="page">
              <i class="bi-house-door me-2"></i>Home
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link link-dark">
              <i class="bi-speedometer2 me-2"></i>Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link link-dark" data-bs-toggle="collapse" data-bs-target="#orders-collapse" aria-expanded="false">
              <i class="bi-cart-check me-2"></i>Orders
            </a>
            <div class="collapse" id="orders-collapse">
              <ul class="nav nav-pills flex-column ms-4">
                <li class="nav-item"><a href="#" class="nav-link active shadow"><i class="bi-circle me-2"></i>New</a></li>
                <li class="nav-item"><a href="#" class="nav-link link-dark"><i class="bi-circle me-2"></i>Processed</a></li>
                <li class="nav-item"><a href="#" class="nav-link link-dark"><i class="bi-circle me-2"></i>Shipped</a></li>
                <li class="nav-item"><a href="#" class="nav-link link-dark"><i class="bi-circle me-2"></i>Returned</a></li>
              </ul>
            </div>
          </li>
        </ul>
        <hr>
        <ul class="nav nav-pills flex-column">
          <li class="nav-item">
            <a href="#" class="nav-link active shadow"><i class="bi-gear me-2"></i>Settings</a>
          </li>
        </ul>
      </div>
    </main>
    <main id="content" class="overflow-auto">
      <?php require $this->getView(); ?>
    </main>
    <script src="/vendor/components/jquery/jquery.min.js"></script>
    <script src="/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/vendor/laswitchtech/bootstrap-panel/dist/js/BSPanel.js"></script>
    <script src="/dist/js/script.js"></script>
  </body>
</html>
