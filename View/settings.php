<div class="row m-0 h-100">
  <div class="col-sm-6 col-lg-4 p-2 ps-4">
    <div class="accordion shadow" id="settingsMenu">
      <div class="accordion-item">
        <h2 class="accordion-header shadow" id="settingsMenuGeneralHeader">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#settingsMenuGeneral" aria-expanded="true" aria-controls="settingsMenuGeneral">
            <i class="bi-gear me-2"></i>General
          </button>
        </h2>
        <div id="settingsMenuGeneral" class="accordion-collapse collapse show" aria-labelledby="settingsMenuGeneralHeader" data-bs-parent="#settingsMenu">
          <div class="list-group list-group-flush rounded-0">
            <button type="button" class="list-group-item list-group-item-action active" data-bs-toggle="collapse" data-bs-target="#settingsSectionGeneralInformation" aria-expanded="true" aria-controls="settingsSectionGeneralInformation"><i class="bi-info-circle ms-4 me-2"></i>Information</button>
            <button type="button" class="list-group-item list-group-item-action" data-bs-toggle="collapse" data-bs-target="#settingsSectionGeneralProfile" aria-expanded="false" aria-controls="settingsSectionGeneralProfile"><i class="bi-person-circle ms-4 me-2"></i>Profile</button>
            <button type="button" class="list-group-item list-group-item-action" data-bs-toggle="collapse" data-bs-target="#settingsSectionGeneralAppearence" aria-expanded="false" aria-controls="settingsSectionGeneralAppearence"><i class="bi-palette ms-4 me-2"></i>Appearence</button>
            <button type="button" class="list-group-item list-group-item-action" data-bs-toggle="collapse" data-bs-target="#settingsSectionGeneralDatabase" aria-expanded="false" aria-controls="settingsSectionGeneralDatabase"><i class="bi-database ms-4 me-2"></i>Database</button>
            <button type="button" class="list-group-item list-group-item-action" data-bs-toggle="collapse" data-bs-target="#settingsSectionGeneralSMTP" aria-expanded="false" aria-controls="settingsSectionGeneralSMTP"><i class="bi-send-check ms-4 me-2"></i>SMTP</button>
          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header shadow" id="settingsMenuSecurityHeader">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#settingsMenuSecurity" aria-expanded="false" aria-controls="settingsMenuSecurity">
            <i class="bi-shield-lock me-2"></i>Security
          </button>
        </h2>
        <div id="settingsMenuSecurity" class="accordion-collapse collapse" aria-labelledby="settingsMenuSecurityHeader" data-bs-parent="#settingsMenu">
          <div class="list-group list-group-flush rounded-0">
            <button type="button" class="list-group-item list-group-item-action" data-bs-toggle="collapse" data-bs-target="#settingsSectionSecurityRoles" aria-expanded="false" aria-controls="settingsSectionSecurityRoles"><i class="bi-shield-shaded ms-4 me-2"></i>Roles</button>
            <button type="button" class="list-group-item list-group-item-action" data-bs-toggle="collapse" data-bs-target="#settingsSectionSecurityUsers" aria-expanded="false" aria-controls="settingsSectionSecurityUsers"><i class="bi-people ms-4 me-2"></i>Users</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-8 p-2 pe-4">
    <div class="accordion" id="settingsSection">
      <div id="settingsSectionGeneralInformation" class="accordion-collapse collapse show" data-bs-parent="#settingsSection">
        <div class="card shadow">
          <div class="card-header">
            <h5 class="card-title my-2 fw-light"><i class="bi-info-circle me-2"></i>Information</h5>
          </div>
          <div class="card-body text-center">
            <div class="d-flex justify-content-center align-items-center my-3">
              <img src="/dist/img/logo.png" class="img-fluid me-2" style="max-height: 128px;max-width: 128px" alt="Logo">
              <h5 class="fs-2 fw-light"><?= $this->coreDB->getBrand(); ?></h5>
            </div>
            <p class="text-muted">Version <?= $this->coreDB->getVersion(); ?></p>
            <p class="text-muted">&copy; 2017–<?= date('Y'); ?></p>
          </div>
        </div>
      </div>
      <div id="settingsSectionGeneralDatabase" class="accordion-collapse collapse" data-bs-parent="#settingsSection">
        <div class="card shadow">
          <div class="card-header">
            <h5 class="card-title my-2 fw-light"><i class="bi-database me-2"></i>Database</h5>
          </div>
          <div class="card-body">
            <form method="post" id="databaseForm">
              <div class="input-group mb-3">
                <span class="input-group-text" id="databaseFormHost"><i class="bi-hdd-network me-2"></i>Host</span>
                <input type="text" class="form-control" placeholder="Host" aria-label="Host" aria-describedby="databaseFormHost">
              </div>
              <div class="input-group mb-3">
                <span class="input-group-text" id="databaseFormDatabase"><i class="bi-database me-2"></i>Database</span>
                <input type="text" class="form-control" placeholder="Database" aria-label="Database" aria-describedby="databaseFormDatabase">
              </div>
              <div class="input-group mb-3">
                <span class="input-group-text" id="databaseFormUsername"><i class="bi-person me-2"></i>Username</span>
                <input type="text" class="form-control" placeholder="Username" autocomplete="username" aria-label="Username" aria-describedby="databaseFormUsername">
              </div>
              <div class="input-group mb-3">
                <span class="input-group-text" id="databaseFormPassword"><i class="bi-lock me-2"></i>Password</span>
                <input type="password" class="form-control" placeholder="Password" autocomplete="current-password" aria-label="Password" aria-describedby="databaseFormPassword">
              </div>
              <button type="submit" class="w-100 btn btn-success"><i class="bi-save me-2"></i>Save</button>
            </form>
          </div>
        </div>
      </div>
      <div id="settingsSectionSecurityRoles" class="accordion-collapse collapse" data-bs-parent="#settingsSection">
        <div class="card shadow">
          <div class="card-header">
            <h5 class="card-title my-2 fw-light"><i class="bi-badge-tm me-2"></i>Roles</h5>
            <ul class="nav nav-pills card-header-pills">
              <li class="nav-item">
                <a class="nav-link active" href="#">Active</a>
              </li>
            </ul>
          </div>
          <div class="card-body text-center">
            <h5 class="card-title">Special title treatment</h5>
            <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
            <a href="#" class="btn btn-primary">Go somewhere</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $('.list-group-item.list-group-item-action').click(function(){
    if($(this).hasClass("active")){
      $('.list-group-item.list-group-item-action.active').removeClass('active')
    } else {
      $('.list-group-item.list-group-item-action.active').removeClass('active')
      $(this).addClass('active')
    }
  })
</script>
