<?php
$name = "";
if(isset($_GET['id'])){ $name = $_GET['id']; }
?>
<div class="row m-0 h-100 px-3" id="roleContainer">
  <div class="col-sm-12 col-md-6 p-2 px-2"></div>
  <div class="col-sm-12 col-md-6 p-2 px-2"></div>
</div>
<script>
  $(document).ready(function(){
    const roleContainer = $('#roleContainer')
    API.get("role/get/?id=<?= $name ?>",{success:function(result,status,xhr){
      if(typeof result[0] !== "undefined"){
        const roleData = result[0]
        // Members
        const membersContainer = roleContainer.find('div.col-sm-12').first()
        Table.create({
          card:{title:"Members",icon:"person-rolodex"},
          showButtonsLabel: false,
          actions:{
            details:{
              label:"Details",
              icon:"eye",
              action:function(event, table, node, row, data){},
            },
            remove:{
              label:"Remove",
              icon:"trash",
              action:function(event, table, node, row, data){},
            },
          },
          columnDefs:[
            { target: 0, visible: true, responsivePriority: 1, title: "Identifier", name: "identifier", data: "identifier" },
            { target: 1, visible: true, responsivePriority: 1000, title: "Type", name: "type", data: "type" },
          ],
          buttons:[
            {
      				extend: 'collection',
      				text: '<i class="bi-plus-lg"></i>',
      				action:function(e, dt, node, config){},
      			}
          ],
          dblclick: function(event, table, node, data){
            window.location.href = window.location.origin+'/user?id='+data.identifier
          },
        },function(table){
          for(const [key, member] of Object.entries(JSON.parse(roleData.members))){
            table.add({identifier:member[Object.keys(member)[0]],type:Object.keys(member)[0]})
          }
        }).appendTo(membersContainer).init()

        // Permissions
        const permissionsContainer = roleContainer.find('div.col-sm-12').last()
        let permissionsActive = []
        let permissionsList = []
        API.get("permission/list/",{success:function(result,status,xhr){
          for(const [key, permission] of Object.entries(result)){
            permissionsList.push(permission.name)
          }
          Table.create({
            card:{title:"Permissions",icon:"file-lock"},
            showButtonsLabel: false,
            actions:{
              none:{
                label:"Set to None",
                icon:"unlock",
                action:function(event, table, node, row, data){
                  data.level = 0
                  table.update(row,data)
                },
              },
              read:{
                label:"Set to Read",
                icon:"unlock",
                action:function(event, table, node, row, data){
                  data.level = 1
                  table.update(row,data)
                },
              },
              create:{
                label:"Set to Create",
                icon:"unlock",
                action:function(event, table, node, row, data){
                  data.level = 2
                  table.update(row,data)
                },
              },
              edit:{
                label:"Set to Edit",
                icon:"unlock",
                action:function(event, table, node, row, data){
                  data.level = 3
                  table.update(row,data)
                },
              },
              delete:{
                label:"Set to Delete",
                icon:"unlock",
                action:function(event, table, node, row, data){
                  data.level = 4
                  table.update(row,data)
                },
              },
              remove:{
                label:"Remove",
                icon:"trash",
                action:function(event, table, node, row, data){
                  table.delete(row)
                },
              },
            },
            columnDefs:[
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
            ],
            buttons:[
              {
        				extend: 'collection',
        				text: '<i class="bi-plus-lg"></i>',
        				action:function(e, dt, node, config){
                  Modal.create({title:'Add Permission',icon:'plus-lg',body:''},function(modal){
                    modal.body.select = $(document.createElement('select')).addClass('form-select w-100').appendTo(modal.body)
                    // $(document.createElement('option')).appendTo(modal.body.select)
                    for(var [key, name] of Object.entries(permissionsList)){
                      if(!inArray(name,permissionsActive)){
                        $(document.createElement('option')).attr('value',name).html(name).appendTo(modal.body.select)
                      }
                    }
                    modal.body.select.select2()
                    // modal.on('shown.bs.modal', function(event){
                    //   modal.body.select.select2({dropdownParent: modal, width:'100%', placeholder:{id:'-1', text:'Select a Permission'}, allowClear: true, theme:"bootstrap-5"})
                    // })
                    // modal.footer.group.primary.click(function(){
                    //   const permissionName = body.select.val()
                    //   const url = "role/edit/?id="+roleData.name+"&type=permission&action=add&name="+permissionName
                    //   console.log(permissionName)
                    //   API.get(url,{success:function(result,status,xhr){
                    //     permissionsActive.push(permissionName)
                    //   }})
                    //   modal.bootstrap.hide()
                    // })
                  })
                },
        			}
            ],
          },function(table){
            for(const [permission, level] of Object.entries(JSON.parse(roleData.permissions))){
              table.add({permission:permission,level:level})
              permissionsActive.push(permission)
            }
          }).appendTo(permissionsContainer).init()
        }})
      }
    }})
  })
</script>
