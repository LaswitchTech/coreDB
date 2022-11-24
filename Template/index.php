<!DOCTYPE html>
<html lang="en" class="vh-100">
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
    <!-- <link rel="stylesheet" href="/dist/css/jquery.dataTables.min.css"> -->
    <link rel="stylesheet" href="/dist/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="/dist/css/stylesheet.css">
    <title><?= $this->coreDB->getBrand() ?> | <?= $this->getLabel() ?></title>
    <script src="/vendor/components/jquery/jquery.min.js"></script>
  </head>
  <body class="vh-100 overflow-hidden" data-theme="default" data-mode="light">
    <aside id="navbar">
      <nav class="navbar navbar-expand fixed-top bg-light shadow user-select-none">
        <div class="d-flex align-items-center container-fluid">
          <!-- Navigations -->
          <ul class="nav nav-pills my-1">
            <?php foreach($this->coreDB->getNavbar() as $key => $item){
              $item['class'] = 'link-dark';
              $item['attributes'] = '';
              if(count($item['menu']) > 0){
                $item['attributes'] = 'data-bs-toggle="dropdown" aria-expanded="'.$item['active'].'" id="NavDrop'.$key.'"';
              } else { if($item['active']){ $item['class'] = 'active shadow'; } }
              ?>
              <li class="nav-item <?php if(count($item['menu']) > 0){ echo 'dropdown'; } ?>">
                <a id="<?= $item['id'] ?>" href="<?= $item['route'] ?>" class="nav-link <?= $item['class'] ?>" <?= $item['attributes'] ?>>
                  <i class="<?= $item['icon'] ?> me-2"></i><?= $item['label'] ?>
                </a>
                <?php if(count($item['menu']) > 0){ ?>
                  <ul class="dropdown-menu" aria-labelledby="NavDrop<?= $key ?>">
                    <?php foreach($item['menu'] as $subkey => $subitem){ ?>
                      <?php if($subitem['active']){ $subitem['class'] = 'active'; } else { $subitem['class'] = ''; } ?>
                      <li><a href="<?= $subitem['route'] ?>" class="dropdown-item <?= $subitem['class'] ?>">
                        <?php if($subitem['icon'] != null){ ?><i class="<?= $subitem['icon'] ?> me-2"></i><?php } ?>
                        <?= $subitem['label'] ?>
                      </a></li>
                    <?php } ?>
                  </ul>
                <?php } ?>
              </li>
            <?php } ?>
          </ul>
          <!-- Search -->
          <form class="d-flex mx-2 flex-grow-1">
            <input class="form-control w-100 shadow-sm" id="coreDBSearch" type="search" placeholder="Search" aria-label="Search">
          </form>
          <!-- Notifications -->
          <div id="NotificationArea" class="dropdown notifications px-2">
            <button type="button" class="btn btn-light position-relative rounded-circle shadow" id="NotificationsMenu" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi-bell"></i>
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow" style="display: none;">0</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="NotificationsMenu">
              <li class="shadow d-flex align-items-center flex-column rounded-top bg-image text-light py-4">
                <h4 class="fw-light">Notifications</h4>
                <span>You have <strong>0</strong> unread notifications</span>
              </li>
              <li class="tl tl-hover p-3 overflow-auto" style="max-height:500px;"></li>
            </ul>
          </div>
          <!-- Profile -->
          <div class="dropdown profile">
            <a href="#" class="d-flex align-items-center mx-2 link-dark text-decoration-none" id="UserMenu" data-bs-toggle="dropdown" aria-expanded="false">
              <img src="<?= $this->coreDB->getGravatar($this->Auth->getUser("username")) ?>" alt="Avatar" width="40" height="40" class="rounded-circle shadow me-2">
              <?= $this->Auth->getUser("username") ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="UserMenu">
              <li class="shadow d-flex align-items-center flex-column rounded-top bg-image text-light py-4">
                <div class="shadow border border-light border-4 rounded-circle">
                  <img src="<?= $this->coreDB->getGravatar($this->Auth->getUser("username")) ?>" class="img-circle border border-primary border-4 rounded-circle" style="--bs-border-opacity: 0;" width="128" height="128" alt="Avatar">
                </div>
                <h5 class="fw-light mt-2"><?= $this->Auth->getUser("username") ?></h5>
                <small>Member since <?= $this->coreDB->getTimeago($this->Auth->getUser("created")) ?></small>
              </li>
              <li class="d-flex justify-content-around py-3">
                <a href="?signout" class="btn btn-light shadow ml-auto"><i class="bi-box-arrow-right me-2"></i>Sign out</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </aside>
    <aside id="sidebar">
      <div class="d-flex flex-column flex-shrink-0 p-3 h-100 bg-light fixed-top shadow-lg user-select-none">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
          <img src="/dist/img/logo.png" class="img-fluid me-2" style="max-height: 32px;max-width: 40px" alt="Logo">
          <span class="fs-4 fw-light">coreDB</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
          <?php foreach($this->coreDB->getSidebar() as $key => $item){
            $item['class'] = 'link-dark';
            $item['attributes'] = '';
            if(count($item['menu']) > 0){
              $item['attributes'] = 'data-bs-toggle="collapse" aria-expanded="'.$item['active'].'" data-bs-target="#SideCollapse'.$key.'"';
            } else { if($item['active']){ $item['class'] = 'active shadow'; } }
            ?>
            <li class="nav-item">
              <a href="<?= $item['route'] ?>" class="nav-link <?= $item['class'] ?>" <?= $item['attributes'] ?>>
                <i class="<?= $item['icon'] ?> me-2"></i><?= $item['label'] ?>
              </a>
              <?php if(count($item['menu']) > 0){ ?>
                <div class="collapse <?php if($item['active']){ echo 'show'; } ?>" id="SideCollapse<?= $key ?>">
                  <ul class="nav nav-pills flex-column ms-4">
                    <?php foreach($item['menu'] as $subkey => $subitem){ ?>
                      <?php if($subitem['active']){ $subitem['class'] = 'active shadow'; } else { $subitem['class'] = 'link-dark'; } ?>
                      <li class="nav-item"><a href="<?= $subitem['route'] ?>" class="nav-link <?= $subitem['class'] ?>"><i class="<?= $subitem['icon'] ?> me-2"></i><?= $subitem['label'] ?></a></li>
                    <?php } ?>
                  </ul>
                </div>
              <?php } ?>
            </li>
          <?php } ?>
        </ul>
        <hr>
        <ul class="nav nav-pills flex-column">
          <li class="nav-item">
            <a href="/settings" class="nav-link link-dark"><i class="bi-gear me-2"></i>Settings</a>
          </li>
        </ul>
      </div>
    </aside>
    <main id="content">
      <aside class="d-flex flex-column h-100">
        <div class="d-flex justify-content-between align-items-center p-4 user-select-none">
          <div class="d-flex align-items-center">
            <div class="icon rounded bg-light text-center shadow p-2"><i class="<?= $this->coreDB->getIcon(); ?> fs-2"></i></div>
            <div class="text-light ms-4 pt-2"><h1 class="display-6 fw-light"><?= $this->getLabel() ?></h1></div>
          </div>
          <div class="d-flex justify-content-end align-items-center flex-grow-1">
            <div class="btn-group shadow" role="group" aria-label="Breadcrumbs">
              <?php foreach($this->coreDB->getBreadcrumbs() as $key => $breadcrumb){ ?>
                <?php if(count($this->coreDB->getBreadcrumbs()) == $key +1){ $color = "primary"; } else { $color = "light"; } ?>
                <a href="<?= $breadcrumb['route'] ?>" class="btn btn-<?= $color ?>"><?= $breadcrumb['label'] ?></a>
              <?php } ?>
            </div>
          </div>
        </div>
        <div class="flex-grow-1 overflow-auto">
          <?php if(!$this->Auth->isAuthorized($this->View)){ $this->Load("403"); } ?>
          <?php $this->getView(); ?>
        </div>
      </aside>
    </main>
    <script>
      const API_TOKEN = "<?= $this->Auth->getUser('token') ?>"
    </script>
    <script src="/vendor/rmm5t/jquery-timeago/jquery.timeago.js"></script>
    <script src="/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/dist/js/jquery.dataTables.min.js"></script>
    <script src="/dist/js/dataTables.bootstrap5.min.js"></script>
    <script src="/vendor/laswitchtech/bootstrap-panel/dist/js/BSPanel.js"></script>
    <script src="/vendor/laswitchtech/php-api/dist/js/phpAPI.js"></script>
    <script src="/vendor/laswitchtech/php-auth/dist/js/cookie.js"></script>
    <script src="/dist/js/script.js"></script>
  </body>
</html>
