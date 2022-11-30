<?php
$name = "";
if(isset($_GET['id'])){ $name = $_GET['id']; }
?>
<div class="row m-0 h-100 px-3" id="roleContainer">
  <div class="col-sm-12 col-md-6 p-2 px-2">
    <div class="card shadow">
      <div class="card-header d-flex justify-content-between">
        <h5 class="card-title my-2 fw-light"><i class="bi-person-rolodex me-2"></i>Members</h5>
        <a href="" id="membersAdd" class="link-dark fs-3"><i class="bi-plus-lg"></i></a>
      </div>
      <div class="card-body p-0">
        <table id="membersList" class="table table-striped w-100" style="margin:0px!important"></table>
      </div>
    </div>
  </div>
  <div class="col-sm-12 col-md-6 p-2 px-2">
    <div class="card shadow">
      <div class="card-header d-flex justify-content-between">
        <h5 class="card-title my-2 fw-light"><i class="bi-file-lock me-2"></i>Permissions</h5>
        <a href="" id="permissionsAdd" class="link-dark fs-3"><i class="bi-plus-lg"></i></a>
      </div>
      <div class="card-body p-0">
        <table id="permissionsList" class="table table-striped w-100" style="margin:0px!important"></table>
      </div>
    </div>
  </div>
</div>
<script>
  $(document).ready(function(){
    const roleContainer = $('#roleContainer')
    const membersAddBtn = $('#membersAdd')
    membersAddBtn.click(function(event){
      event.preventDefault();
    })
    const permissionsAddBtn = $('#permissionsAdd')
    permissionsAddBtn.click(function(event){
      event.preventDefault();
    })
    const membersActions = ActionDropdown.create({details:{label:"Details",icon:"eye"},remove:{label:"Remove",icon:"trash"}})
    const membersListTable = $('#membersList').DataTable({
      dom: 'rt<"d-flex justify-content-between align-items-center card-footer"ip>',
      responsive: true,
      columnDefs: [
        { target: 0, visible: true, responsivePriority: 1, title: "Identifier", name: "identifier", data: "identifier" },
        { target: 1, visible: true, responsivePriority: 1000, title: "Type", name: "type", data: "type" },
        { target: 2, visible: true, responsivePriority: 2, title: "Action", data: null, defaultContent: membersActions.get(0).outerHTML },
      ]
    })
    const permissionsActions = ActionDropdown.create({
      none:{label:"Set to None",icon:"unlock"},
      read:{label:"Set to Read",icon:"unlock"},
      create:{label:"Set to Create",icon:"unlock"},
      edit:{label:"Set to Edit",icon:"unlock"},
      delete:{label:"Set to Delete",icon:"unlock"},
      remove:{label:"Remove",icon:"trash"},
    })
    const permissionsListTable = $('#permissionsList').DataTable({
      dom: 'rt<"d-flex justify-content-between align-items-center card-footer"ip>',
      responsive: true,
      columnDefs: [
        { target: 0, visible: true, responsivePriority: 1, title: "Permission", name: "permission", data: "permission" },
        { target: 1, visible: true, responsivePriority: 1000, title: "Level", name: "level", data: "level", render: function(data,type,row,meta){
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
        { target: 2, visible: true, responsivePriority: 2, title: "Action", data: null, defaultContent: permissionsActions.get(0).outerHTML },
      ]
    })
    API.get("role/get/?id=<?= $name ?>",{success:function(result,status,xhr){
      if(typeof result[0] !== "undefined"){
        const roleData = result[0]
        console.log(roleData)
        roleContainer.find('button[aria-controls="offcanvasActivity"]').click(function(){
          Activity.show("roles",roleData.id)
        })
        for(const [permission, level] of Object.entries(JSON.parse(roleData.permissions))){
          permissionsListTable.row.add({permission:permission,level:level}).draw();
        }
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
        for(const [key, member] of Object.entries(JSON.parse(roleData.members))){
          const type = Object.keys(member)[0]
          const identifier = member[type]
          membersListTable.row.add({identifier:identifier,type:type}).draw();
        }
        $('#membersList tbody').on('dblclick','tr', function() {
          let currentRowData = membersListTable.row(this).data();
          switch(currentRowData.type){
            case"users":
              window.location.href = window.location.origin+'/user?id='+currentRowData.identifier;
              break
          }
        });
        $('#membersList tbody').on('click', 'button', function () {
          let currentRowData = membersListTable.row($(this).parents('tr')).data();
          let button = $(this)
          let action = button.attr('data-action')
          switch(action){
            case"details":
              switch(currentRowData.type){
                case"users":
                  window.location.href = window.location.origin+'/user?id='+currentRowData.identifier;
                  break
              }
              break
          }
        });
        $('#coreDBSearch').keyup(function(){
          membersListTable.search($(this).val()).draw()
        })
      }
    }})
  })
</script>
