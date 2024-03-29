<?php if(isset($_GET['id'])){ $id = $_GET['id']; } else { $id = ""; } ?>
<div class="row m-0 h-100 pb-4" id="topic">
  <div class="col-sm-6 col-lg-4 p-2 ps-4" id="start"></div>
  <div class="col-sm-6 col-lg-8 p-2 pe-4" id="end"></div>
</div>
<div class="row m-0 h-100 d-none" id="denied">
  <?php if(is_file($this->Path . '/View/403.php')){ include $this->Path . '/View/403.php'; } ?>
</div>
<div class="row m-0 h-100 d-none" id="notfound">
  <?php if(is_file($this->Path . '/View/404.php')){ include $this->Path . '/View/404.php'; } ?>
</div>
<script>
  $(document).ready(function(){

    // Declare Variables
    const denied = $('#denied')
    const notfound = $('#notfound')
    let container = $('#topic')
    container.start = container.find('#start')
    container.end = container.find('#end')
    console.log('Container',container)

    // Request Topic
    API.get("topic/get/?id=<?= $id ?>",{
      success:function(result,status,xhr){
        if(typeof result[0] !== "undefined"){
          let topic = result[0]
          console.log('Topic',topic)
          container.start.details = Card.create({
            icon: 'chat-text',
            title: 'Details',
            close:true,
            collapsed: false,
            collapse: true,
            fullscreen: true,
            classBody: 'p-0',
          }, function(card){
            card.body.description = $(document.createElement('div')).addClass('d-flex align-items-center flex-column rounded-bottom bg-image text-light py-4').appendTo(card.body)
            card.body.description.icon = $(document.createElement('i')).addClass('bi-chat').css('font-size','120px').appendTo(card.body.description)
            card.body.description.header = $(document.createElement('h5')).addClass('fw-light mt-2').appendTo(card.body.description)
            card.body.description.header.title = $(document.createElement('strong')).html('ID: ').appendTo(card.body.description.header)
            card.body.description.header.id = $(document.createElement('span')).html(topic.id).appendTo(card.body.description.header)
            card.body.description.status =  $(document.createElement('button')).addClass('btn border btn-light shadow mt-2').appendTo(card.body.description)
            card.body.description.status.icon = $(document.createElement('i')).addClass('me-2').appendTo(card.body.description.status)
            card.body.description.status.label = $(document.createElement('span')).addClass('text-capitalize').appendTo(card.body.description.status)
            card.setStatus = function(status){
              if(typeof status === 'number'){
                card.body.description.status.removeClass('btn-light btn-info btn-primary btn-success btn-secondary')
                card.body.description.status.icon.removeClass('bi-chat-text bi-chat bi-folder-check bi-folder-symlink bi-activity')
                card.body.description.status.label.html('')
                switch(status){
                  case 0:
                    card.body.description.status.addClass('btn-info')
                    card.body.description.status.icon.addClass('bi-chat-text')
                    card.body.description.status.label.html('New')
                    break
                  case 1:
                    card.body.description.status.addClass('btn-primary')
                    card.body.description.status.icon.addClass('bi-chat')
                    card.body.description.status.label.html('Open')
                    break
                  case 2:
                    card.body.description.status.addClass('btn-success')
                    card.body.description.status.icon.addClass('bi-folder-check')
                    card.body.description.status.label.html('Closed')
                    break
                  case 3:
                    card.body.description.status.addClass('btn-secondary')
                    card.body.description.status.icon.addClass('bi-folder-symlink')
                    card.body.description.status.label.html('Merged')
                    break
                  default:
                    card.body.description.status.addClass('btn-light')
                    card.body.description.status.icon.addClass('bi-activity')
                    card.body.description.status.label.html('Unknown')
                    break
                }
              }
            }
            card.body.controls = $(document.createElement('div')).addClass('d-flex justify-content-center py-3').appendTo(card.body)
            card.body.controls.group = $(document.createElement('div')).addClass('btn-group border shadow w-100 mx-3').appendTo(card.body.controls)
            card.body.controls.group.activity = $(document.createElement('button')).addClass('btn btn-light').appendTo(card.body.controls.group)
            card.body.controls.group.activity.icon = $(document.createElement('i')).addClass('bi-activity me-2').prependTo(card.body.controls.group.activity)
            card.body.controls.group.activity.label = $(document.createElement('span')).addClass('text-capitalize').html('Activity').appendTo(card.body.controls.group.activity)
            card.body.controls.group.activity.click(function(){
              Activity.show("topics",topic.id)
            })
            card.body.controls.group.share = $(document.createElement('button')).addClass('btn btn-primary').appendTo(card.body.controls.group)
            card.body.controls.group.share.icon = $(document.createElement('i')).addClass('bi-share me-2').prependTo(card.body.controls.group.share)
            card.body.controls.group.share.label = $(document.createElement('span')).addClass('text-capitalize').html('Share').appendTo(card.body.controls.group.share)
            card.body.controls.group = Note.create('button',{
              linkTo: {topics:topic.id},
              removeClass: 'border',
            }).appendTo(card.body.controls.group)
            card.setStatus(topic.status)
          }).appendTo(container.start)
          container.start.files = Card.create({
            icon: 'files',
            title: 'Files',
            close:true,
            collapsed: false,
            collapse: true,
            fullscreen: true,
            classCard: 'mt-3',
            classBody: 'p-0',
          }, function(card){
            card.list = File.list({
              beforeUpload: function(file){
                if(typeof topic.sharedTo !== 'undefined'){
                  file.sharedTo = JSON.stringify(topic.sharedTo)
                }
                file.isPublic = 1
                return file
              },
              afterUpload: function(file){
                API.get("topic/addFile/?id="+topic.id+"&file="+file.id+"&csrf="+CSRF,{
                  success:function(result,status,xhr){}
                })
              },
            },function(list){
              console.log('Files', list)
              list.addClass('rounded-bottom')
              for(const [id, file] of Object.entries(topic.files)){
                list.add(file,function(item){
                  console.log('File', item)
                })
              }
            }).appendTo(card.body)
          }).appendTo(container.start)
          container.start.contacts = Card.create({
            icon: 'person-rolodex',
            title: 'Contacts',
            close:true,
            collapsed: false,
            collapse: true,
            fullscreen: true,
            classCard: 'mt-3',
            classBody: 'p-0',
          }, function(card){
            card.list = List.create({
              callback: {
        				click: function(item, list){
                  copyToClipboard(item.field)
                },
        			},
        			icon: 'person-lines-fill',
        			control: {
        				list: {
                  add: {
                    icon: 'plus-lg',
            				label: null,
            				color: 'success',
            				class: null,
            				callback: function(control){},
                  },
                },
        				item: {
                  delete: {
                    icon: 'trash',
            				label: null,
            				color: 'danger',
            				class: null,
            				callback: function(item){},
                  },
                },
        			},
            },function(list){
              console.log('Contacts', list)
              list.addClass('rounded-bottom')
              for(const [key, contact] of Object.entries(topic.contacts)){
                list.add({
                  field:contact
                },function(item){
                  console.log('Item', item)
                })
              }
            }).appendTo(card.body)
          }).appendTo(container.start)
          container.start.dataset = Card.create({
            icon: 'database',
            title: 'Dataset',
            close:true,
            collapsed: false,
            collapse: true,
            fullscreen: true,
            classCard: 'mt-3',
            classBody: 'p-0',
          }, function(card){
            card.list = List.create({
        			control: {
        				list: {
                  add: {
                    icon: 'plus-lg',
            				label: null,
            				color: 'success',
            				class: null,
            				callback: function(control){},
                  },
                },
        			},
            },function(list){
              console.log('Dataset', list)
              list.addClass('rounded-bottom')
              for(const [category, values] of Object.entries(topic.dataset)){
                list.add(function(item){
                  console.log('Item', item)
                  item.field.container = $(document.createElement('div')).addClass('d-flex justify-content-start align-items-center').appendTo(item.field)
                  item.field.category = $(document.createElement('div')).addClass('flex-shrink-1 pe-2 fw-bold').html(category + ':').appendTo(item.field.container)
                  item.field.values = $(document.createElement('div')).addClass('flex-grow-1').appendTo(item.field.container)
                  for(const [key, value] of Object.entries(values)){
                    let group = $(document.createElement('div')).addClass('btn-group border shadow mx-1').appendTo(item.field.values)
                    group.value = $(document.createElement('button')).addClass('btn btn-sm btn-primary').html(value).appendTo(group)
                    group.value.click(function(){
                      copyToClipboard(group.value)
                    })
                    group.delete = $(document.createElement('button')).addClass('btn btn-sm btn-danger').appendTo(group)
                    group.delete.icon = $(document.createElement('i')).addClass('bi-trash').appendTo(group.delete)
                    group.delete.click(function(){
                      // copyToClipboard(group.value)
                    })
                  }
                })
              }
            }).appendTo(card.body)
          }).appendTo(container.start)
          container.start.meta = Card.create({
            icon: 'clipboard-data',
            title: 'Meta',
            close:true,
            collapsed: false,
            collapse: true,
            fullscreen: true,
            classCard: 'mt-3',
            classBody: 'p-0',
          }, function(card){
            card.list = List.create({
        			control: {
        				list: {
                  add: {
                    icon: 'plus-lg',
            				label: null,
            				color: 'success',
            				class: null,
            				callback: function(control){},
                  },
                },
        			},
            },function(list){
              console.log('Meta', list)
              list.addClass('rounded-bottom')
              for(const [category, values] of Object.entries(topic.meta)){
                list.add(function(item){
                  console.log('Item', item)
                  item.field.container = $(document.createElement('div')).addClass('d-flex justify-content-start align-items-center').appendTo(item.field)
                  item.field.category = $(document.createElement('div')).addClass('flex-shrink-1 pe-2 fw-bold').html(category + ':').appendTo(item.field.container)
                  item.field.values = $(document.createElement('div')).addClass('flex-grow-1').appendTo(item.field.container)
                  for(const [key, value] of Object.entries(values)){
                    let group = $(document.createElement('div')).addClass('btn-group border shadow mx-1').appendTo(item.field.values)
                    group.value = $(document.createElement('button')).addClass('btn btn-sm btn-primary').html(value).appendTo(group)
                    group.value.click(function(){
                      copyToClipboard(group.value)
                    })
                    group.delete = $(document.createElement('button')).addClass('btn btn-sm btn-danger').appendTo(group)
                    group.delete.icon = $(document.createElement('i')).addClass('bi-trash').appendTo(group.delete)
                    group.delete.click(function(){
                      // copyToClipboard(group.value)
                    })
                  }
                })
              }
            }).appendTo(card.body)
          }).appendTo(container.start)
          container.end.feed = Card.create({
            icon: 'chat-square-text',
            title: 'Feed',
            close:true,
            collapsed: false,
            collapse: true,
            fullscreen: true,
          }, function(card){
            card.feed = Feed.create({
              linkTo: {topics:topic.id},
              note: true,
              share: true,
              like: true,
              edit: true,
              comment: true,
            },function(feed){
              console.log('Feed', feed)
            }).appendTo(card.body)
          }).appendTo(container.end)
        }
      },
      error:function(xhr,status,error){
        container.addClass('d-none')
        switch(xhr.status){
          case 403:
            denied.removeClass('d-none')
            break
          case 404:
            notfound.removeClass('d-none')
            break
          default:
            break
        }
    	}
    })
  })
</script>
