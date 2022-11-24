<div class="row m-0 h-100">
  <div class="col-sm-6 col-lg-4 p-2 ps-4">
    <div class="accordion rounded shadow" id="settingsMenu">
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
          <div class="list-group list-group-flush rounded-bottom">
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
      <div id="settingsSectionGeneralProfile" class="accordion-collapse collapse" data-bs-parent="#settingsSection">
        <div class="card shadow">
          <div class="card-header">
            <h5 class="card-title my-2 fw-light"><i class="bi-person-circle me-2"></i>Profile</h5>
          </div>
          <div class="card-body text-center">
            <form method="post" id="profileForm">
              <div class="input-group shadow mb-3">
                <span class="input-group-text" id="profileFormEmail"><i class="bi-at me-2"></i>Email</span>
                <input type="text" class="form-control" name="username" placeholder="Email" autocomplete="username" aria-label="Email" aria-describedby="profileFormEmail">
              </div>
              <div class="input-group shadow mb-3">
                <span class="input-group-text" id="profileFormPassword"><i class="bi-lock me-2"></i>Password</span>
                <input type="password" class="form-control" name="password" placeholder="Password" autocomplete="new-password" aria-label="Password" aria-describedby="profileFormPassword">
                <input type="password" class="form-control" name="password2" placeholder="Confirm" autocomplete="new-password" aria-label="Confirm" aria-describedby="profileFormPassword">
              </div>
              <div class="input-group shadow mb-3">
                <span class="input-group-text" id="profileFormLanguage"><i class="bi-translate me-2"></i>Language</span>
                <select class="form-select" name="language" placeholder="Language" aria-label="Language" aria-describedby="profileFormLanguage">
                  <option value="english">English</option>
                  <option value="french">Francais</option>
                </select>
              </div>
              <button type="submit" name="profileFormSubmit" class="shadow w-100 btn btn-success"><i class="bi-save me-2"></i>Save</button>
            </form>
          </div>
        </div>
      </div>
      <div id="settingsSectionGeneralAppearence" class="accordion-collapse collapse" data-bs-parent="#settingsSection">
        <div class="card shadow">
          <div class="card-header">
            <h5 class="card-title my-2 fw-light"><i class="bi-palette me-2"></i>Appearence</h5>
          </div>
          <div class="card-body text-center">
            <form method="post" id="appearenceForm">
              <div class="input-group shadow mb-3">
                <span class="input-group-text" id="appearenceFormBrand"><i class="bi-badge-tm me-2"></i>Brand</span>
                <input type="text" class="form-control" name="brand" placeholder="Brand" aria-label="Brand" aria-describedby="appearenceFormBrand">
              </div>
              <div class="input-group shadow mb-3">
                <span class="input-group-text" id="appearenceFormLogo"><i class="bi-file-image me-2"></i>Logo</span>
                <input type="file" class="form-control" name="logo" placeholder="Logo" aria-label="Logo" aria-describedby="appearenceFormLogo">
              </div>
              <div class="input-group shadow mb-3">
                <span class="input-group-text" id="appearenceFormWallpaper"><i class="bi-image me-2"></i>Wallpaper</span>
                <input type="file" class="form-control" name="wallpaper" placeholder="Wallpaper" aria-label="Wallpaper" aria-describedby="appearenceFormWallpaper">
              </div>
              <div class="input-group shadow mb-3">
                <span class="flex-fill input-group-text" id="appearenceFormMode"><i class="bi-sun"></i><i class="bi-slash-lg"></i><i class="bi-moon me-2"></i>Mode</span>
                <input type="radio" class="btn-check" name="mode" value="light" id="appearenceFormModeDefault" aria-label="Mode" aria-describedby="appearenceFormMode" autocomplete="off" checked>
                <label class="flex-fill btn btn-outline-primary border" data-mode="light" for="appearenceFormModeDefault">
                  <div class="row h-100 px-2">
                    <div class="col bg-primary"></div>
                    <div class="col bg-secondary"></div>
                    <div class="col bg-success"></div>
                    <div class="col bg-danger"></div>
                    <div class="col bg-warning"></div>
                    <div class="col bg-info"></div>
                    <div class="col bg-light"></div>
                    <div class="col bg-dark"></div>
                  </div>
                </label>
                <input type="radio" class="btn-check" name="mode" value="dark" id="appearenceFormModeDark" aria-label="Mode" aria-describedby="appearenceFormMode" autocomplete="off">
                <label class="flex-fill btn btn-outline-primary border" data-mode="dark" for="appearenceFormModeDark">
                  <div class="row h-100 px-2">
                    <div class="col bg-primary"></div>
                    <div class="col bg-secondary"></div>
                    <div class="col bg-success"></div>
                    <div class="col bg-danger"></div>
                    <div class="col bg-warning"></div>
                    <div class="col bg-info"></div>
                    <div class="col bg-light"></div>
                    <div class="col bg-dark"></div>
                  </div>
                </label>
              </div>
              <div class="input-group shadow mb-3">
                <span class="flex-fill input-group-text" id="appearenceFormTheme"><i class="bi-palette2 me-2"></i>Theme</span>
                <input type="radio" class="btn-check" name="theme" value="default" id="appearenceFormThemeDefault" aria-label="Theme" aria-describedby="appearenceFormTheme" autocomplete="off" checked>
                <label class="flex-fill btn btn-outline-primary border" data-theme="default" for="appearenceFormThemeDefault">
                  <div class="row h-100 px-2">
                    <div class="col bg-primary"></div>
                    <div class="col bg-secondary"></div>
                    <div class="col bg-success"></div>
                    <div class="col bg-danger"></div>
                    <div class="col bg-warning"></div>
                    <div class="col bg-info"></div>
                    <div class="col bg-light"></div>
                    <div class="col bg-dark"></div>
                  </div>
                </label>
                <input type="radio" class="btn-check" name="theme" value="dark" id="appearenceFormThemeDark" aria-label="Theme" aria-describedby="appearenceFormTheme" autocomplete="off">
                <label class="flex-fill btn btn-outline-primary border" data-theme="dark" for="appearenceFormThemeDark">
                  <div class="row h-100 px-2">
                    <div class="col bg-primary"></div>
                    <div class="col bg-secondary"></div>
                    <div class="col bg-success"></div>
                    <div class="col bg-danger"></div>
                    <div class="col bg-warning"></div>
                    <div class="col bg-info"></div>
                    <div class="col bg-light"></div>
                    <div class="col bg-dark"></div>
                  </div>
                </label>
                <input type="radio" class="btn-check" name="theme" value="space" id="appearenceFormThemeSpace" aria-label="Theme" aria-describedby="appearenceFormTheme" autocomplete="off">
                <label class="flex-fill btn btn-outline-primary border" data-theme="space" for="appearenceFormThemeSpace">
                  <div class="row h-100 px-2">
                    <div class="col bg-primary"></div>
                    <div class="col bg-secondary"></div>
                    <div class="col bg-success"></div>
                    <div class="col bg-danger"></div>
                    <div class="col bg-warning"></div>
                    <div class="col bg-info"></div>
                    <div class="col bg-light"></div>
                    <div class="col bg-dark"></div>
                  </div>
                </label>
              </div>
              <button type="submit" name="appearenceFormSubmit" class="shadow w-100 btn btn-success"><i class="bi-save me-2"></i>Save</button>
            </form>
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
              <div class="input-group shadow mb-3">
                <span class="input-group-text" id="databaseFormHost"><i class="bi-hdd-network me-2"></i>Host</span>
                <input type="text" class="form-control" name="host" placeholder="Host" aria-label="Host" aria-describedby="databaseFormHost">
              </div>
              <div class="input-group shadow mb-3">
                <span class="input-group-text" id="databaseFormDatabase"><i class="bi-database me-2"></i>Database</span>
                <input type="text" class="form-control" name="database" placeholder="Database" aria-label="Database" aria-describedby="databaseFormDatabase">
              </div>
              <div class="input-group shadow mb-3">
                <span class="input-group-text" id="databaseFormUsername"><i class="bi-person me-2"></i>Username</span>
                <input type="text" class="form-control" name="username" placeholder="Username" autocomplete="username" aria-label="Username" aria-describedby="databaseFormUsername">
              </div>
              <div class="input-group shadow mb-3">
                <span class="input-group-text" id="databaseFormPassword"><i class="bi-lock me-2"></i>Password</span>
                <input type="password" class="form-control" name="password" placeholder="Password" autocomplete="current-password" aria-label="Password" aria-describedby="databaseFormPassword">
              </div>
              <button type="submit" name="databaseFormSubmit" class="shadow w-100 btn btn-success"><i class="bi-save me-2"></i>Save</button>
            </form>
          </div>
        </div>
      </div>
      <div id="settingsSectionGeneralSMTP" class="accordion-collapse collapse" data-bs-parent="#settingsSection">
        <div class="card shadow">
          <div class="card-header">
            <h5 class="card-title my-2 fw-light"><i class="bi-send-check me-2"></i>SMTP</h5>
          </div>
          <div class="card-body">
            <form method="post" id="smtpForm">
              <div class="input-group shadow mb-3">
                <span class="input-group-text" id="smtpFormHost"><i class="bi-hdd-network me-2"></i>Host</span>
                <input type="text" class="form-control" name="host" placeholder="Host" aria-label="Host" aria-describedby="smtpFormHost">
              </div>
              <div class="input-group shadow mb-3">
                <span class="input-group-text" id="smtpFormSecurity"><i class="bi-lock me-2"></i>Security</span>
                <select class="form-select" name="security" placeholder="Security" aria-label="Security" aria-describedby="smtpFormSecurity">
                  <option value="">None</option>
                  <option value="ssl">SSL</option>
                  <option value="starttls">STARTTLS</option>
                </select>
              </div>
              <div class="input-group shadow mb-3">
                <span class="input-group-text" id="smtpFormPort"><i class="bi-ethernet me-2"></i>Port</span>
                <input type="number" class="form-control" name="port" placeholder="Port" aria-label="Port" aria-describedby="smtpFormPort">
              </div>
              <div class="input-group shadow mb-3">
                <span class="input-group-text" id="smtpFormUsername"><i class="bi-person me-2"></i>Username</span>
                <input type="text" class="form-control" name="username" placeholder="Username" autocomplete="username" aria-label="Username" aria-describedby="smtpFormUsername">
              </div>
              <div class="input-group shadow mb-3">
                <span class="input-group-text" id="smtpFormPassword"><i class="bi-lock me-2"></i>Password</span>
                <input type="password" class="form-control" name="password" placeholder="Password" autocomplete="current-password" aria-label="Password" aria-describedby="smtpFormPassword">
              </div>
              <button type="submit" name="smtpFormSubmit" class="shadow w-100 btn btn-success"><i class="bi-save me-2"></i>Save</button>
            </form>
          </div>
        </div>
      </div>
      <div id="settingsSectionSecurityRoles" class="accordion-collapse collapse" data-bs-parent="#settingsSection">
        <div class="card shadow">
          <div class="card-header">
            <h5 class="card-title my-2 fw-light"><i class="bi-shield-shaded me-2"></i>Roles</h5>
          </div>
          <div class="card-body p-0">
            <table id="rolesList" class="table table-striped w-100" style="margin:0px!important">
            </table>
          </div>
        </div>
      </div>
      <div id="settingsSectionSecurityUsers" class="accordion-collapse collapse" data-bs-parent="#settingsSection">
        <div class="card shadow">
          <div class="card-header">
            <h5 class="card-title my-2 fw-light"><i class="bi-people me-2"></i>Users</h5>
          </div>
          <div class="card-body p-0">
            <table id="usersList" class="table table-striped w-100" style="margin:0px!important">
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $.holdReady(true)
  $(document).ready(function(){
    $('.list-group-item.list-group-item-action').click(function(){
      if($(this).hasClass("active")){
        $('.list-group-item.list-group-item-action.active').removeClass('active')
      } else {
        $('.list-group-item.list-group-item-action.active').removeClass('active')
        $(this).addClass('active')
      }
    })
    let rolesListTable = $('#rolesList').DataTable({
      dom: 'rt<"p-3"p>',
      pagingType: 'full_numbers',
      columnDefs: [
        { target: 0, visible: false, title: "ID", name: "id", data: "id" },
        { target: 1, visible: false, title: "Created", name: "created", data: "created" },
        { target: 2, visible: false, title: "Modified", name: "modified", data: "modified" },
        { target: 3, visible: true, title: "Name", name: "name", data: "name" },
        { target: 4, visible: true, title: "Permissions", name: "permissions", data: "permissions", render: function(data,type,row,meta){
          data = JSON.parse(data)
          let html = '', color = '', icon = ''
          for(const [permission, level] of Object.entries(data)){
            switch(level){
              case 0:
                color = 'secondary'
                icon = 'x-octagon'
                break
              case 1:
                color = 'primary'
                icon = 'eye'
                break
              case 2:
                color = 'success'
                icon = 'plus-square'
                break
              case 3:
                color = 'warning'
                icon = 'pencil-square'
                break
              case 4:
                color = 'danger'
                icon = 'trash'
                break
            }
            html += '<span class="badge bg-'+color+' mx-1"><i class="bi-'+icon+' me-2"></i>'+permission+'</span>'
          }
          return html;
        } },
        { target: 5, visible: true, title: "Members", name: "members", data: "members", render: function(data,type,row,meta){
          data = JSON.parse(data)
          let html = '', color = 'primary', icon = 'person'
          for(const [key, member] of Object.entries(data)){
            for(const [type, id] of Object.entries(member)){
              html += '<span class="badge bg-'+color+' mx-1"><i class="bi-'+icon+' me-1"></i>'+type+':'+id+'</span>'
            }
          }
          return html;
        } }
      ]
    })
    let usersListTable = $('#usersList').DataTable({
      dom: 'rt<"p-3"p>',
      pagingType: 'full_numbers',
      columnDefs: [
        { target: 0, visible: false, title: "ID", name: "id", data: "id" },
        { target: 1, visible: false, title: "Created", name: "created", data: "created" },
        { target: 2, visible: false, title: "Modified", name: "modified", data: "modified" },
        { target: 3, visible: true, title: "Username", name: "username", data: "username" },
        { target: 4, visible: false, title: "Type", name: "type", data: "type" },
        { target: 5, visible: false, title: "Roles", name: "roles", data: "roles" },
        { target: 6, visible: false, title: "Session ID", name: "sessionID", data: "sessionID" },
        { target: 7, visible: false, title: "Password", name: "password", data: "password" },
        { target: 8, visible: false, title: "Token", name: "token", data: "token" },
        { target: 9, visible: true, title: "Active", name: "isActive", data: "isActive", render: function(data,type,row,meta){
          let color = '', icon = '', text = ''
          switch(data){
            case 0:
              color = 'danger'
              icon = 'x-octagon'
              text = 'Inactive'
              break
            case 1:
              color = 'success'
              icon = 'check2'
              text = 'Active'
              break
          }
          return '<span class="badge bg-'+color+' mx-1"><i class="bi-'+icon+' me-2"></i>'+text+'</span>'
        } }
      ]
    })
    API.get('role/list',{success:function(result,status,xhr){
      for(const [key, role] of Object.entries(result)){
        rolesListTable.row.add(role).draw();
      }
    }})
    API.get('user/list',{success:function(result,status,xhr){
      for(const [key, role] of Object.entries(result)){
        usersListTable.row.add(role).draw();
      }
    }})
    $('#coreDBSearch').keyup(function(){
      rolesListTable.search($(this).val()).draw()
      usersListTable.search($(this).val()).draw()
    })
  })
</script>
