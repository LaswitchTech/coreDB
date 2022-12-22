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
        let roleData = result[0]
        roleData.members = JSON.parse(roleData.members)
        roleData.permissions = JSON.parse(roleData.permissions)
        // Members
        const membersContainer = roleContainer.find('div.col-sm-12').first()
        let membersList = {}
        let membersActive = {}
        API.get("user/list/",{success:function(result,status,xhr){
          for(const [key, user] of Object.entries(result)){
            membersList[user.username] = user.id
          }
          membersListTable = Table.create({
            card:{title:"Members",icon:"person-rolodex"},
            showButtonsLabel: false,
            actions:{
              details:{
                label:"Details",
                icon:"eye",
                action:function(event, table, node, row, data){
                  window.location.href = window.location.origin+'/user?id='+data.identifier
                },
              },
              remove:{
                label:"Remove",
                icon:"trash",
                action:function(event, table, node, row, data){
                  let object = {}
                  object[data.type] = data.identifier
                  const roleMembersIndex = roleData.members.indexOf(object)
                  if(roleMembersIndex > -1){
                    roleData.members.splice(roleMembersIndex, 1)
                  }
                  if(typeof CSRF !== 'undefined' && CSRF != ''){
                    const url = "role/edit/?id="+roleData.name+"&type=member&action=remove&name="+membersList[data.identifier]+'&csrf='+CSRF
                    API.get(url,{success:function(result,status,xhr){
                      table.delete(row)
                      if(typeof membersActive[data.identifier] !== 'undefined'){
                        delete membersActive[data.identifier]
                      }
                      Toast.create({title:'Saved!',icon:'check-lg',color:'success',close:false})
                    }})
                  }
                },
              },
            },
            columnDefs:[
              { target: 0, visible: true, responsivePriority: 1, title: "Identifier", name: "identifier", data: "identifier" },
              { target: 1, visible: false, responsivePriority: 1000, title: "Type", name: "type", data: "type" },
            ],
            buttons:[
              {
        				extend: 'collection',
        				text: '<i class="bi-plus-lg"></i>',
        				action:function(e, dt, node, config){
                  Modal.create({title:'Add Member',icon:'plus-lg',body:''},function(modal){
                    modal.body.select = $(document.createElement('select')).addClass('form-select w-100').appendTo(modal.body)
                    for(var [username, id] of Object.entries(membersList)){
                      if(typeof membersActive[username] === 'undefined'){
                        $(document.createElement('option')).attr('value',username).html(username).appendTo(modal.body.select)
                      }
                    }
                    modal.on('shown.bs.modal', function(event){
                      modal.body.select.select2({dropdownParent: modal, width:'100%', placeholder:{id:'-1', text:'Select a Member'}, allowClear: true, theme:"bootstrap-5"})
                    })
                    modal.footer.group.primary.click(function(){
                      const memberUsername = modal.body.select.val()
                      roleData.members.push({users:memberUsername})
                      if(typeof CSRF !== 'undefined' && CSRF != ''){
                        const url = "role/edit/?id="+roleData.name+"&type=member&action=add&name="+membersList[memberUsername]+'&csrf='+CSRF
                        API.get(url,{success:function(result,status,xhr){
                          membersActive[memberUsername] = membersList[memberUsername]
                          membersListTable.add({identifier:memberUsername,type:'users'})
                          Toast.create({title:'Saved!',icon:'check-lg',color:'success',close:false})
                        }})
                      }
                      modal.bootstrap.hide()
                    })
                  })
                },
        			}
            ],
            dblclick: function(event, table, node, data){
              window.location.href = window.location.origin+'/user?id='+data.identifier
            },
          },function(table){
            for(const [key, member] of Object.entries(roleData.members)){
              let details = {
                identifier:member[Object.keys(member)[0]],
                type:Object.keys(member)[0],
              }
              table.add(details)
              membersActive[details.identifier] = membersList[details.identifier]
            }
          }).appendTo(membersContainer).init()
        }})

        // Permissions
        const permissionsContainer = roleContainer.find('div.col-sm-12').last()
        let permissionsActive = []
        let permissionsList = []
        API.get("permission/list/",{success:function(result,status,xhr){
          for(const [key, permission] of Object.entries(result)){
            permissionsList.push(permission.name)
          }
          permissionsListTable = Table.create({
            card:{title:"Permissions",icon:"file-lock"},
            showButtonsLabel: false,
            actions:{
              none:{
                label:"Set to None",
                icon:"unlock",
                action:function(event, table, node, row, data){
                  roleData.permissions[data.permission] = 0
                  data.level = 0
                  if(typeof CSRF !== 'undefined' && CSRF != ''){
                    const url = "role/edit/?id="+roleData.name+"&type=permission&action=set&level="+data.level+"&name="+data.permission+'&csrf='+CSRF
                    API.get(url,{success:function(result,status,xhr){
                      table.update(row,data)
                      Toast.create({title:'Saved!',icon:'check-lg',color:'success',close:false})
                    }})
                  }
                },
              },
              read:{
                label:"Set to Read",
                icon:"unlock",
                action:function(event, table, node, row, data){
                  roleData.permissions[data.permission] = 1
                  data.level = 1
                  if(typeof CSRF !== 'undefined' && CSRF != ''){
                    const url = "role/edit/?id="+roleData.name+"&type=permission&action=set&level="+data.level+"&name="+data.permission+'&csrf='+CSRF
                    API.get(url,{success:function(result,status,xhr){
                      table.update(row,data)
                      Toast.create({title:'Saved!',icon:'check-lg',color:'success',close:false})
                    }})
                  }
                },
              },
              create:{
                label:"Set to Create",
                icon:"unlock",
                action:function(event, table, node, row, data){
                  roleData.permissions[data.permission] = 2
                  data.level = 2
                  if(typeof CSRF !== 'undefined' && CSRF != ''){
                    const url = "role/edit/?id="+roleData.name+"&type=permission&action=set&level="+data.level+"&name="+data.permission+'&csrf='+CSRF
                    API.get(url,{success:function(result,status,xhr){
                      table.update(row,data)
                      Toast.create({title:'Saved!',icon:'check-lg',color:'success',close:false})
                    }})
                  }
                },
              },
              edit:{
                label:"Set to Edit",
                icon:"unlock",
                action:function(event, table, node, row, data){
                  roleData.permissions[data.permission] = 3
                  data.level = 3
                  if(typeof CSRF !== 'undefined' && CSRF != ''){
                    const url = "role/edit/?id="+roleData.name+"&type=permission&action=set&level="+data.level+"&name="+data.permission+'&csrf='+CSRF
                    API.get(url,{success:function(result,status,xhr){
                      table.update(row,data)
                      Toast.create({title:'Saved!',icon:'check-lg',color:'success',close:false})
                    }})
                  }
                },
              },
              delete:{
                label:"Set to Delete",
                icon:"unlock",
                action:function(event, table, node, row, data){
                  roleData.permissions[data.permission] = 4
                  data.level = 4
                  if(typeof CSRF !== 'undefined' && CSRF != ''){
                    const url = "role/edit/?id="+roleData.name+"&type=permission&action=set&level="+data.level+"&name="+data.permission+'&csrf='+CSRF
                    API.get(url,{success:function(result,status,xhr){
                      table.update(row,data)
                      Toast.create({title:'Saved!',icon:'check-lg',color:'success',close:false})
                    }})
                  }
                },
              },
              remove:{
                label:"Remove",
                icon:"trash",
                action:function(event, table, node, row, data){
                  delete roleData.permissions[data.permission]
                  if(typeof CSRF !== 'undefined' && CSRF != ''){
                    const url = "role/edit/?id="+roleData.name+"&type=permission&action=remove&name="+data.permission+'&csrf='+CSRF
                    API.get(url,{success:function(result,status,xhr){
                      table.delete(row)
                      const index = permissionsActive.indexOf(data.permission)
                      if (index > -1) {
                        permissionsActive.splice(index, 1)
                      }
                      Toast.create({title:'Saved!',icon:'check-lg',color:'success',close:false})
                    }})
                  }
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
                    for(var [key, name] of Object.entries(permissionsList)){
                      if(!inArray(name,permissionsActive)){
                        $(document.createElement('option')).attr('value',name).html(name).appendTo(modal.body.select)
                      }
                    }
                    modal.on('shown.bs.modal', function(event){
                      modal.body.select.select2({dropdownParent: modal, width:'100%', placeholder:{id:'-1', text:'Select a Permission'}, allowClear: true, theme:"bootstrap-5"})
                    })
                    modal.footer.group.primary.click(function(){
                      const permissionName = modal.body.select.val()
                      roleData.permissions[permissionName] = 1
                      if(typeof CSRF !== 'undefined' && CSRF != ''){
                        const url = "role/edit/?id="+roleData.name+"&type=permission&action=add&name="+permissionName+'&csrf='+CSRF
                        API.get(url,{success:function(result,status,xhr){
                          permissionsActive.push(permissionName)
                          permissionsListTable.add({permission:permissionName,level:1})
                          Toast.create({title:'Saved!',icon:'check-lg',color:'success',close:false})
                        }})
                      }
                      modal.bootstrap.hide()
                    })
                  })
                },
        			}
            ],
          },function(table){
            for(const [permission, level] of Object.entries(roleData.permissions)){
              table.add({permission:permission,level:level})
              permissionsActive.push(permission)
            }
          }).appendTo(permissionsContainer).init()
        }})
      }
    }})
  })
</script>
