<?php
$name = "";
if(isset($_GET['id'])){ $name = $_GET['id']; }
?>
<div class="row m-0 h-100" id="roleContainer">
  <div class="col-sm-6 col-lg-4 p-2 ps-4">
    <div class="card shadow">
      <div class="card-header">
        <h5 class="card-title my-2 fw-light"><i class="bi-person-circle me-2"></i>Role</h5>
      </div>
      <div class="card-body p-0">
        <div class="d-flex align-items-center flex-column rounded-bottom bg-image text-light py-4">
          <h5 class="fw-light mt-2"><?= $name ?></h5>
        </div>
        <div class="d-flex justify-content-around py-3">
          <button class="btn btn-light shadow" type="button" aria-controls="offcanvasActivity"><i class="bi-activity me-2"></i>Activity</button>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-8 p-2 pe-4">
    <div class="card shadow mb-3">
      <div class="card-header">
        <h5 class="card-title my-2 fw-light"><i class="bi-file-lock me-2"></i>Permissions</h5>
      </div>
      <div class="card-body p-0">
        <table id="permissionsList" class="table table-striped w-100" style="margin:0px!important"></table>
      </div>
    </div>
    <div class="card shadow">
      <div class="card-header">
        <h5 class="card-title my-2 fw-light"><i class="bi-person-rolodex me-2"></i>Members</h5>
      </div>
      <div class="card-body p-0">
        <table id="membersList" class="table table-striped w-100" style="margin:0px!important"></table>
      </div>
    </div>
  </div>
</div>
<script>
  $(document).ready(function(){
    const roleContainer = $('#roleContainer')
    API.get("role/get/?id=<?= $name ?>",{success:function(result,status,xhr){
      if(typeof result[0] !== "undefined"){
        const roleData = result[0]
        console.log(roleData)
        roleContainer.find('button[aria-controls="offcanvasActivity"]').click(function(){
          Activity.show("roles",roleData.id)
        })
        let actions = $(document.createElement('div')).addClass('dropdown');
        actions.btn = $(document.createElement('a')).addClass('link-dark').attr('href','').attr('data-bs-toggle','dropdown').attr('aria-expanded','false').appendTo(actions);
        actions.btn.icon = $(document.createElement('i')).addClass('bi-three-dots-vertical').appendTo(actions.btn);
        actions.menu = $(document.createElement('ul')).addClass('dropdown-menu').appendTo(actions);
        actions.menu.details = $(document.createElement('li')).appendTo(actions.menu);
        actions.menu.details.btn = $(document.createElement('button')).attr('type','button').attr('data-action','details').addClass('dropdown-item').html('Details').appendTo(actions.menu.details);
        actions.menu.details.btn.icon = $(document.createElement('i')).addClass('bi-eye me-2').prependTo(actions.menu.details.btn);
        actions.menu.edit = $(document.createElement('li')).appendTo(actions.menu);
        actions.menu.edit.btn = $(document.createElement('button')).attr('type','button').attr('data-action','edit').addClass('dropdown-item').html('Edit').appendTo(actions.menu.edit);
        actions.menu.edit.btn.icon = $(document.createElement('i')).addClass('bi-pencil-square me-2').prependTo(actions.menu.edit.btn);
        actions.menu.delete = $(document.createElement('li')).appendTo(actions.menu);
        actions.menu.delete.btn = $(document.createElement('button')).attr('type','button').attr('data-action','delete').addClass('dropdown-item').html('Delete').appendTo(actions.menu.delete);
        actions.menu.delete.btn.icon = $(document.createElement('i')).addClass('bi-trash me-2').prependTo(actions.menu.delete.btn);
        let permissionsListTable = $('#permissionsList').DataTable({
          dom: 'rt<"card-footer"p>',
          pagingType: 'full_numbers',
          columnDefs: [
            { target: 0, visible: true, title: "Permission", name: "permission", data: "permission" },
            { target: 1, visible: true, title: "Level", name: "level", data: "level", render: function(data,type,row,meta){
              let color = '', icon = '', text = ''
              switch(data){
                case 0:
                  color = 'secondary'
                  icon = 'x-octagon'
                  text = 'None'
                  break
                case 1:
                  color = 'primary'
                  icon = 'eye'
                  text = 'Read'
                  break
                case 2:
                  color = 'success'
                  icon = 'plus-square'
                  text = 'Create'
                  break
                case 3:
                  color = 'warning'
                  icon = 'pencil-square'
                  text = 'Edit'
                  break
                case 4:
                  color = 'danger'
                  icon = 'trash'
                  text = 'Delete'
                  break
              }
              return '<span class="badge bg-'+color+'"><i class="bi-'+icon+' me-2"></i>'+text+'</span>'
            } },
            { target: 2, visible: true, title: "", data: null, defaultContent: actions.get(0).outerHTML },
          ]
        })
        for(const [permission, level] of Object.entries(JSON.parse(roleData.permissions))){
          permissionsListTable.row.add({permission:permission,level:level}).draw();
        }
        // $('#permissionsList tbody').on('dblclick','tr', function() {
        //   let currentRowData = permissionsListTable.row(this).data();
        //   window.location.href = window.location.origin+'/role?id='+currentRowData.name;
        // });
        // $('#permissionsList tbody').on('click', 'button', function () {
        //   let currentRowData = permissionsListTable.row($(this).parents('tr')).data();
        //   let button = $(this)
        //   let action = button.attr('data-action')
        //   switch(action){
        //     case"details":
        //       window.location.href = window.location.origin+'/role?id='+currentRowData.name;
        //       break
        //   }
        // });
        $('#coreDBSearch').keyup(function(){
          permissionsListTable.search($(this).val()).draw()
        })
        let membersListTable = $('#membersList').DataTable({
          dom: 'rt<"card-footer"p>',
          pagingType: 'full_numbers',
          columnDefs: [
            { target: 0, visible: true, title: "Identifier", name: "identifier", data: "identifier" },
            { target: 1, visible: true, title: "Type", name: "type", data: "type" },
            { target: 2, visible: true, title: "", data: null, defaultContent: actions.get(0).outerHTML },
          ]
        })
        for(const [key, member] of Object.entries(JSON.parse(roleData.members))){
          const type = Object.keys(member)[0]
          const identifier = member[type]
          membersListTable.row.add({identifier:identifier,type:type}).draw();
        }
        // $('#membersList tbody').on('dblclick','tr', function() {
        //   let currentRowData = membersListTable.row(this).data();
        //   window.location.href = window.location.origin+'/role?id='+currentRowData.name;
        // });
        // $('#membersList tbody').on('click', 'button', function () {
        //   let currentRowData = membersListTable.row($(this).parents('tr')).data();
        //   let button = $(this)
        //   let action = button.attr('data-action')
        //   switch(action){
        //     case"details":
        //       window.location.href = window.location.origin+'/role?id='+currentRowData.name;
        //       break
        //   }
        // });
        $('#coreDBSearch').keyup(function(){
          membersListTable.search($(this).val()).draw()
        })
      }
    }})
  })
</script>
