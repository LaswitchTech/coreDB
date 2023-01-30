<?php if(isset($_GET['id'])){ $id = $_GET['id']; } else { $id = ""; } ?>
<div class="row m-0 h-100 d-none" id="topic">
  <div class="col-sm-6 col-lg-4 p-2 ps-4">
    <div class="card shadow" id="details">
      <div class="card-header user-select-none">
        <h5 class="card-title d-flex align-items-center my-2 fw-light" style="line-height:32px;"><i class="bi-chat-text me-2"></i>Details</h5>
      </div>
      <div class="card-body p-0">
        <div class="d-flex align-items-center flex-column rounded-bottom bg-image text-light py-4">
          <i class="bi-chat" style="font-size:120px"></i>
          <h5 class="fw-light mt-2"><strong>ID: </strong><?= $id ?></h5>
          <button class="btn border shadow btn-light mt-2" type="button" id="status"></button>
        </div>
        <div class="d-flex justify-content-center py-3">
          <div class="btn-group border shadow w-100 mx-3">
            <button class="btn btn-light" type="button" aria-controls="offcanvasActivity"><i class="bi-activity"></i></button>
            <button class="btn btn-primary" type="button" id="reply"><i class="bi-envelope-plus"></i></button>
            <button class="btn btn-warning" type="button" id="note"><i class="bi-sticky"></i></button>
            <button class="btn btn-primary" type="button" id="comment"><i class="bi-chat-left-text"></i></button>
            <button class="btn btn-light" type="button" id="share"><i class="bi-share"></i></button>
          </div>
        </div>
      </div>
    </div>
    <div class="card shadow mt-3" id="topics">
      <div class="card-header user-select-none cursor-pointer" data-bs-toggle="collapse" href="#collapseTopics">
        <h5 class="card-title d-flex align-items-center my-2 fw-light" style="line-height:32px;"><i class="bi-chat-quote me-2"></i>Similar Topics</h5>
      </div>
      <div class="card-body collapse p-0" id="collapseTopics">
        <ul class="list-group list-group-flush rounded-bottom"></ul>
      </div>
    </div>
    <div class="card shadow mt-3" id="files">
      <div class="card-header user-select-none cursor-pointer" data-bs-toggle="collapse" href="#collapseFiles">
        <h5 class="card-title d-flex align-items-center my-2 fw-light" style="line-height:32px;"><i class="bi-files me-2"></i>Files<span class="badge bg-light border ms-auto text-dark shadow d-flex align-items-center"><i class="bi-files me-2"></i><span class="badge bg-primary" id="countFiles"></span></span></h5>
      </div>
      <div class="card-body collapse show p-0" id="collapseFiles">
        <ul class="list-group list-group-flush rounded-bottom">
          <li class="list-group-item user-select-none bg-light">
            <div class="d-flex justify-content-center align-items-center">
              <div class="btn-group border shadow w-100">
                <button class="btn btn-success" type="button" id="addFileBtn"><i class="bi-upload"></i></button>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <div class="card shadow mt-3 d-none" id="trash">
      <div class="card-header user-select-none cursor-pointer" data-bs-toggle="collapse" href="#collapseTrash">
        <h5 class="card-title d-flex align-items-center my-2 fw-light" style="line-height:32px;"><i class="bi-trash me-2"></i>Trash<span class="badge bg-light border ms-auto text-dark shadow d-flex align-items-center"><i class="bi-files me-2"></i><span class="badge bg-danger" id="countTrash"></span></span></h5>
      </div>
      <div class="card-body collapse p-0" id="collapseTrash">
        <ul class="list-group list-group-flush rounded-bottom"></ul>
      </div>
    </div>
    <div class="card shadow mt-3" id="contacts">
      <div class="card-header user-select-none cursor-pointer" data-bs-toggle="collapse" href="#collapseContacts">
        <h5 class="card-title d-flex align-items-center my-2 fw-light" style="line-height:32px;"><i class="bi-person-rolodex me-2"></i>Contacts<span class="badge bg-light border ms-auto text-dark shadow d-flex align-items-center"><i class="bi-person-vcard me-2"></i><span class="badge bg-primary" id="countContacts"></span></span></h5>
      </div>
      <div class="card-body collapse p-0" id="collapseContacts">
        <ul class="list-group list-group-flush rounded-bottom">
          <li class="list-group-item user-select-none bg-light">
            <div class="d-flex justify-content-center align-items-center">
              <div class="btn-group border shadow w-100">
                <button class="btn btn-success" type="button" id="addContactBtn"><i class="bi-person-plus"></i></button>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <div class="card shadow mt-3" id="dataset">
      <div class="card-header user-select-none cursor-pointer" data-bs-toggle="collapse" href="#collapseDataset">
        <h5 class="card-title d-flex align-items-center my-2 fw-light" style="line-height:32px;"><i class="bi-database me-2"></i>Dataset</h5>
      </div>
      <div class="card-body collapse p-0" id="collapseDataset">
        <ul class="list-group list-group-flush rounded-bottom">
          <li class="list-group-item user-select-none bg-light">
            <div class="d-flex justify-content-center align-items-center">
              <div class="btn-group border shadow w-100">
                <button class="btn btn-success" type="button" id="addLoadBtn"><i class="bi-minecart-loaded"></i></button>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <div class="card shadow mt-3" id="meta">
      <div class="card-header user-select-none cursor-pointer" data-bs-toggle="collapse" href="#collapseMeta">
        <h5 class="card-title d-flex align-items-center my-2 fw-light" style="line-height:32px;"><i class="bi-clipboard-data me-2"></i>Meta</h5>
      </div>
      <div class="card-body collapse p-0" id="collapseMeta">
        <ul class="list-group list-group-flush rounded-bottom"></ul>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-8 p-2 pe-4">
    <div class="card shadow">
      <div class="card-header user-select-none">
        <h5 class="card-title d-flex align-items-center my-2 fw-light" style="line-height:32px;"><i class="bi-clock me-2"></i>Timeline<span class="badge bg-light border ms-auto text-dark shadow d-flex align-items-center"><i class="bi-star me-2"></i><span class="badge bg-primary" id="countUnread"></span></span></h5>
      </div>
      <div class="card-body p-0">
        <div id="timeline" class="p-3"></div>
      </div>
    </div>
  </div>
</div>
<div class="row m-0 h-100 d-none" id="denied">
  <?php if(is_file($this->Path . '/View/403.php')){ include $this->Path . '/View/403.php'; } ?>
</div>
<div class="row m-0 h-100 d-none" id="notfound">
  <?php if(is_file($this->Path . '/View/404.php')){ include $this->Path . '/View/404.php'; } ?>
</div>
<script>
  $(document).ready(function(){
    const denied = $('#denied')
    const notfound = $('#notfound')
    const topic = $('#topic')
    const details = $('#details')
    const dataset = $('#dataset')
    const listDatasets = dataset.find('.list-group')
    const meta = $('#meta')
    const listMetas = meta.find('.list-group')
    const files = $('#files')
    const addFileBtn = $('#addFileBtn')
    const listFiles = files.find('.list-group')
    const countFiles = $('#countFiles')
    const trash = $('#trash')
    const listTrash = trash.find('.list-group')
    const countTrash = $('#countTrash')
    const contacts = $('#contacts')
    const addContactBtn = $('#addContactBtn')
    const listContacts = contacts.find('.list-group')
    const countContacts = $('#countContacts')
    const reply = $('#reply')
    const note = $('#note')
    const comment = $('#comment')
    const share = $('#share')
    const status = $('#status')
    const countUnread = $('#countUnread')
    let topicData = {}
    let timeline = $('#timeline')
    let Authorization = {trash: false, trashed: false}
    timeline.timeline = Timeline.create()
    timeline.timeline.appendTo(timeline)
    $('#coreDBSearch').keyup(function(){
      if($(this).val() != ''){
        listFiles.find('li').hide()
        listFiles.find('li:contains('+$(this).val()+')').show()
        listTrash.find('li').hide()
        listTrash.find('li:contains('+$(this).val()+')').show()
        listContacts.find('li').hide()
        listContacts.find('li:contains('+$(this).val()+')').show()
        listDatasets.find('li').hide()
        listDatasets.find('li:contains('+$(this).val()+')').show()
        listMetas.find('li').hide()
        listMetas.find('li:contains('+$(this).val()+')').show()
      } else {
        listFiles.find('li').show()
        listTrash.find('li').show()
        listContacts.find('li').show()
        listDatasets.find('li').show()
        listMetas.find('li').show()
      }
    })
    const addComment = function(button){
      Modal.create({title:'Add Comment',size:'lg',color:"primary",icon:'chat-left-text',body:''},function(modal){
        modal.header.addClass('text-bg-primary')
        modal.footer.group.primary.html('Comment')
        modal.body.addClass('p-0')
        modal.body.textarea = $(document.createElement('textarea')).attr('id','addCommentTopic'+topicData.id).addClass('form-control').appendTo(modal.body)
        modal.body.textarea.summernote({
          placeholder: 'Type your comment here...',
          focus: true,
          disableResizeEditor: true,
          dialogsInBody: true,
          dialogsFade: false,
          height: 300,
          focus: true,
          toolbar: [
            ['fontsize', ['fontname','fontsize']],
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol']],
            ['code', ['code']],
          ],
        })
        modal.footer.group.primary.click(function(){
          if(!modal.body.textarea.summernote('isEmpty')){
            let linkTo = button.attr('data-linkTo'), type = null, identifier = null
            let comment = {
              topic: topicData.id,
              content: modal.body.textarea.summernote('code'),
              linkTo:{},
            }
            if(linkTo.toString().includes(':')){
              type = linkTo.split(':')[0].toString()
              identifier = linkTo.split(':')[1].toString()
              comment.linkTo[type] = identifier
            }
            comment.linkTo = JSON.stringify(comment.linkTo)
            comment.content = btoa(comment.content)
            API.post("topic/comment/?id="+topicData.id,comment,{
              success:function(result,status,xhr){
                addCommentObject(result)
                timeline.timeline.sort()
                Toast.create({title:'Saved',icon:'check2',color:'success',close:false})
              }
            })
            modal.bootstrap.hide()
          }
        })
      })
    }
    const addNote = function(button){
      Modal.create({title:'Add Note',size:'lg',color:"warning",icon:'sticky',body:''},function(modal){
        modal.header.addClass('text-bg-warning')
        modal.footer.group.primary.html('Post')
        modal.body.addClass('p-0')
        modal.body.textarea = $(document.createElement('textarea')).attr('id','addNoteTopic'+topicData.id).addClass('form-control').appendTo(modal.body)
        modal.body.textarea.summernote({
          placeholder: 'Type your notes here...',
          focus: true,
          disableResizeEditor: true,
          dialogsInBody: true,
          dialogsFade: false,
          height: 300,
          focus: true,
          toolbar: [
            ['fontsize', ['fontname','fontsize']],
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol']],
            ['code', ['code']],
          ],
        })
        modal.footer.group.primary.click(function(){
          if(!modal.body.textarea.summernote('isEmpty')){
            let linkTo = button.attr('data-linkTo'), type = null, identifier = null
            let note = {
              topic: topicData.id,
              content: modal.body.textarea.summernote('code'),
              owner:{
                users: "<?= $this->Auth->getUser("username") ?>",
              },
              linkTo:{},
            }
            if(linkTo.toString().includes(':')){
              type = linkTo.split(':')[0].toString()
              identifier = linkTo.split(':')[1].toString()
              note.linkTo[type] = identifier
            }
            note.linkTo = JSON.stringify(note.linkTo)
            note.content = btoa(note.content)
            API.post("topic/note/?id="+topicData.id,note,{
              success:function(result,status,xhr){
                addNoteObject(result)
                timeline.timeline.sort()
                Toast.create({title:'Saved',icon:'check2',color:'success',close:false})
              }
            })
            modal.bootstrap.hide()
          }
        })
      })
    }
    const addReply = function(button, array = null){
      var contacts = getContacts()
      Modal.create({title:'Reply',size:'lg',color:"primary",icon:'envelope-plus',body:''},function(modal){
        modal.header.addClass('text-bg-primary')
        modal.footer.group.primary.html('Send')
        modal.body.addClass('p-0')
        modal.body.group = $(document.createElement('div')).addClass('p-3 bg-light').appendTo(modal.body)
        modal.body.group.select = $(document.createElement('select')).attr('name','contacts[]').addClass('form-control').appendTo(modal.body.group)
        for(const [key, contact] of Object.entries(contacts)){
          $(document.createElement('option')).attr('value',contact).html(contact).appendTo(modal.body.group.select)
        }
        modal.on('shown.bs.modal', function(event){
          modal.body.group.select.attr('multiple','multiple').select2({dropdownParent: modal, width:'100%', placeholder:{id:'-1', text:'Select a Contact'}, allowClear: true, theme:"bootstrap-5"})
          if(array != null){
            modal.body.group.select.val(array).trigger('change')
          } else {
            modal.body.group.select.val(contacts).trigger('change')
          }
        })
        modal.body.textarea = $(document.createElement('textarea')).addClass('form-control').appendTo(modal.body)
        modal.body.textarea.summernote({
          placeholder: 'Type your reply here...',
          focus: true,
          disableResizeEditor: true,
          dialogsInBody: true,
          dialogsFade: false,
          height: 300,
          focus: true,
          toolbar: [
            ['fontsize', ['fontname','fontsize']],
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol']],
            ['code', ['code']],
          ],
        })
        modal.footer.group.primary.click(function(){
          if(!modal.body.textarea.summernote('isEmpty')){
            let linkTo = button.attr('data-linkTo'), type = null, identifier = null
            let message = {
              topic: topicData.id,
              to: modal.body.group.select.val(),
              content: modal.body.textarea.summernote('code'),
              owner:{
                users: "<?= $this->Auth->getUser("username") ?>",
              },
              linkTo:{},
            }
            if(linkTo.toString().includes(':')){
              type = linkTo.split(':')[0].toString()
              identifier = linkTo.split(':')[1].toString()
              message.linkTo[type] = identifier
            }
            message.to = JSON.stringify(message.to)
            message.linkTo = JSON.stringify(message.linkTo)
            message.content = btoa(message.content)
            // API.post("topic/reply/?id="+topicData.id,message,{
            //   success:function(result,status,xhr){
            //     addNoteObject(result)
            //     timeline.timeline.sort()
            //     Toast.create({title:'Saved',icon:'check2',color:'success',close:false})
            //   }
            // })
            modal.bootstrap.hide()
          }
        })
      })
    }
    const addEmlObject = function(eml){
      if(timeline.find('[data-type="eml"][data-id="'+eml.id+'"]').length <= 0){
        let item = {
          icon: 'envelope',
          color: 'primary',
          type: 'eml',
          datetime: eml.date,
          header: eml.subject_stripped,
          body: '',
          footer: '',
        }
        timeline.timeline.object(item,function(object){
          object.data = eml
          object.attr('data-id',eml.id)
          object.icon.addClass('cursor-pointer').attr('data-bs-toggle','collapse').attr('data-bs-target','#eml'+eml.id).attr('aria-expanded',false)
          object.item.header.addClass('cursor-pointer').attr('data-bs-toggle','collapse').attr('data-bs-target','#eml'+eml.id).attr('aria-expanded',false)
          object.item.body.attr('id','eml'+eml.id).addClass('collapse p-0')
          object.item.body.contacts = $(document.createElement('div')).addClass('border-bottom p-3 bg-light user-select-none').appendTo(object.item.body)
          for(const [key, contact] of Object.entries(eml.contacts)){
            var badgeContact = $(document.createElement('span')).addClass('badge bg-primary me-2 shadow cursor-pointer').html(contact).appendTo(object.item.body.contacts)
            badgeContact.click(function(){
              copyToClipboard($(this))
            })
          }
          object.item.body.content = $(document.createElement('div')).addClass('p-3 border-bottom').html(eml.body_stripped.replaceAll('<div><br></div>', '').replaceAll('<div></div>', '').replaceAll('\r\n', '<br>')).appendTo(object.item.body)
          object.item.body.files = $(document.createElement('div')).addClass('border-bottom p-3 d-flex flex-wrap bg-light')
          if(eml.files.length > 0){
            object.item.body.files.appendTo(object.item.body)
          }
          for(const [key, file] of Object.entries(eml.files)){
            if(typeof topicData.files[file] !== 'undefined'){
              let fileElement = $(document.createElement('div')).addClass('card m-1 cursor-default shadow').css('max-width','175px').appendTo(object.item.body.files)
              fileElement.css('transition','all 300ms').css('-webkit-transition','all 300ms').css('-ms-transition','all 300ms').css('-o-transition','all 300ms')
              fileElement.data = topicData.files[file]
              fileElement.attr('data-isDelected',false)
              fileElement.attr('data-fileID',fileElement.data.id)
              fileElement.controls = $(document.createElement('div')).addClass('btn-group-vertical rounded shadow border position-absolute').css('top','8px').css('right','8px').appendTo(fileElement)
              fileElement.controls.preview = $(document.createElement('button')).addClass('btn btn-sm btn-primary d-none').attr('data-fileAction','preview').appendTo(fileElement.controls)
              fileElement.controls.preview.icon = $(document.createElement('i')).addClass('bi-eye').appendTo(fileElement.controls.preview)
              fileElement.controls.download = $(document.createElement('button')).addClass('btn btn-sm btn-light d-none').attr('data-fileAction','download').appendTo(fileElement.controls)
              fileElement.controls.download.icon = $(document.createElement('i')).addClass('bi-arrow-bar-down').appendTo(fileElement.controls.download)
              fileElement.controls.unpublish = $(document.createElement('button')).addClass('btn btn-sm btn-primary d-none').attr('data-fileAction','unpublish').appendTo(fileElement.controls)
              fileElement.controls.unpublish.icon = $(document.createElement('i')).addClass('bi-globe2').appendTo(fileElement.controls.unpublish)
              fileElement.controls.publish = $(document.createElement('button')).addClass('btn btn-sm btn-light d-none').attr('data-fileAction','publish').appendTo(fileElement.controls)
              fileElement.controls.publish.icon = $(document.createElement('i')).addClass('bi-share').appendTo(fileElement.controls.publish)
              fileElement.controls.restore = $(document.createElement('button')).addClass('btn btn-sm btn-info rounded d-none').attr('data-fileAction','restore').appendTo(fileElement.controls)
              fileElement.controls.restore.icon = $(document.createElement('i')).addClass('bi-arrow-counterclockwise').appendTo(fileElement.controls.restore)
              fileElement.controls.trash = $(document.createElement('button')).addClass('btn btn-sm btn-danger d-none').attr('data-fileAction','trash').appendTo(fileElement.controls)
              fileElement.controls.trash.icon = $(document.createElement('i')).addClass('bi-trash').appendTo(fileElement.controls.trash)
              fileElement.controls.preview.click(function(){
                File.preview(fileElement.data.id)
              })
              fileElement.controls.download.click(function(){
                File.download(fileElement.data.id)
              })
              fileElement.controls.publish.click(function(){
                publishFile(fileElement.data.id)
              })
              fileElement.controls.unpublish.click(function(){
                unpublishFile(fileElement.data.id)
              })
              fileElement.controls.trash.click(function(){
                deleteFile(fileElement.data.id)
              })
              fileElement.controls.restore.click(function(){
                restoreFile(fileElement.data.id)
              })
              if(fileElement.data.isDeleted){
                fileElement.attr('data-isDelected',true)
                if(Authorization.trashed){
                  fileElement.removeClass('d-none')
                } else {
                  fileElement.addClass('d-none')
                }
                fileElement.controls.preview.addClass('d-none')
                fileElement.controls.download.addClass('d-none')
                fileElement.controls.unpublish.addClass('d-none')
                fileElement.controls.publish.addClass('d-none')
                fileElement.controls.restore.removeClass('d-none')
                fileElement.controls.trash.addClass('d-none')
              } else {
                if(fileElement.data.isPublic){
                  fileElement.controls.unpublish.removeClass('d-none')
                  fileElement.controls.publish.addClass('d-none')
                } else {
                  fileElement.controls.unpublish.addClass('d-none')
                  fileElement.controls.publish.removeClass('d-none')
                }
                fileElement.controls.preview.removeClass('d-none')
                fileElement.controls.download.removeClass('d-none')
                fileElement.controls.restore.addClass('d-none')
                fileElement.controls.trash.removeClass('d-none')
              }
              fileElement.body = $(document.createElement('div')).addClass('card-body rounded-top text-center px-5').css('padding-top','29px').css('padding-bottom','29px')
              fileElement.icon = $(document.createElement('i')).css('font-size','54px').appendTo(fileElement.body)
              switch(fileElement.data.type){
                case "aac":
                case "ai":
                case "bmp":
                case "cs":
                case "css":
                case "csv":
                case "doc":
                case "docx":
                case "exe":
                case "gif":
                case "heic":
                case "html":
                case "java":
                case "jpg":
                case "js":
                case "json":
                case "jsx":
                case "key":
                case "m4p":
                case "md":
                case "mdx":
                case "mov":
                case "mp3":
                case "mp4":
                case "otf":
                case "pdf":
                case "php":
                case "png":
                case "ppt":
                case "pptx":
                case "psd":
                case "py":
                case "raw":
                case "rb":
                case "sass":
                case "scss":
                case "sh":
                case "sql":
                case "svg":
                case "tiff":
                case "tsx":
                case "ttf":
                case "txt":
                case "wav":
                case "woff":
                case "xls":
                case "xlsx":
                case "xml":
                case "yml":
                  fileElement.icon.addClass('bi-filetype-'+fileElement.data.type)
                  fileElement.body.appendTo(fileElement)
                  break;
                default:
                  fileElement.icon.addClass('bi-file-earmark')
                  fileElement.body.appendTo(fileElement)
                  break;
              }
              fileElement.footer = $(document.createElement('div')).addClass('card-footer').appendTo(fileElement)
              fileElement.footer.name = $(document.createElement('div')).addClass('fw-bold text-break').html(fileElement.data.filename).appendTo(fileElement.footer)
              fileElement.footer.size = $(document.createElement('div')).addClass('text-muted').html(formatBytes(fileElement.data.size)).appendTo(fileElement.footer)
              fileElement.hover(function(){
                fileElement.addClass("text-bg-primary")
              }, function(){
                fileElement.removeClass("text-bg-primary")
              });
            }
          }
          object.item.footer.addClass('d-flex justify-content-between align-items-center user-select-none')
          object.item.footer.sender = $(document.createElement('div')).html('Sender:').appendTo(object.item.footer)
          object.item.footer.sender.badge = $(document.createElement('span')).addClass('badge bg-primary ms-2 shadow cursor-pointer').html(eml.sender).appendTo(object.item.footer.sender)
          object.item.footer.sender.badge.click(function(){
            copyToClipboard($(this))
          })
          object.item.footer.controls = $(document.createElement('div')).addClass('btn-group border shadow').appendTo(object.item.footer)
          object.item.footer.controls.note = $(document.createElement('button')).addClass('btn btn-warning').attr('data-linkTo','emls:'+eml.id).html('Note').appendTo(object.item.footer.controls)
          object.item.footer.controls.note.icon = $(document.createElement('i')).addClass('bi-sticky me-1').prependTo(object.item.footer.controls.note)
          object.item.footer.controls.note.click(function(){
            addNote($(this))
          })
          object.item.footer.controls.comment = $(document.createElement('button')).addClass('btn btn-primary').attr('data-linkTo','emls:'+eml.id).html('Comment').appendTo(object.item.footer.controls)
          object.item.footer.controls.comment.icon = $(document.createElement('i')).addClass('bi-chat-left-text me-1').prependTo(object.item.footer.controls.comment)
          object.item.footer.controls.comment.click(function(){
            addComment($(this))
          })
          object.item.footer.controls.reply = $(document.createElement('button')).addClass('btn btn-light').html('Reply').appendTo(object.item.footer.controls)
          object.item.footer.controls.reply.icon = $(document.createElement('i')).addClass('bi-reply me-1').prependTo(object.item.footer.controls.reply)
          object.item.footer.controls.reply.click(function(){
            addReply($(this),eml.contacts)
          })
          object.attr('data-search',object.text().toString().toUpperCase())
          object.collapse = new bootstrap.Collapse(object.item.body,{toggle: false})
          object.item.body.on('show.bs.collapse',function(){
            object.icon.removeClass('bi-envelope').addClass('bi-envelope-paper')
            object.item.header.addClass('shadow-sm')
            if(eml.isRead <= 0){
              API.get("topic/readEml/?id="+eml.id,{success:function(result,status,xhr){
                if(typeof result[topicData.id] !== 'undefined'){
                  eml.isRead = 1
                  object.data.isRead = 1
                  topicData.emls[eml.id].isRead = 1
                  setStatus(result[topicData.id].status)
                  setCount(result[topicData.id].countUnread)
                  Toast.create({title:'Read',icon:'eyeglasses',color:'info',close:false})
                }
              }})
            }
            if(Authorization.trashed){
              timeline.find('[data-isDeleted="true"]').removeClass('d-none')
            }
          })
          object.item.body.on('shown.bs.collapse',function(){
            if(typeof object.animate !== 'undefined'){
              clearInterval(object.animate)
            }
          })
          object.item.body.on('hide.bs.collapse',function(){
            object.item.header.removeClass('shadow-sm')
          })
          object.item.body.on('hidden.bs.collapse',function(){
            object.icon.removeClass('bi-envelope-paper').addClass('bi-envelope')
          })
          if(eml.isRead <= 0){
            object.duration = 2000
            object.item.effect("highlight",{color:Theme.primary,duration:object.duration})
            object.animate = setInterval(function(){
              object.item.effect("highlight",{color:Theme.primary,duration:object.duration})
            }, object.duration)
          }
        })
      }
    }
    const addCommentObject = function(comment){
      if(comment.linkTo == null){
        comment.linkTo = [{topics:topicData.id}]
      }
      for(const [key, link] of Object.entries(comment.linkTo)){
        const type = Object.keys(link)[0]
        const identifier = link[type]
        let item = {
          icon: 'chat-left-text',
          color: 'primary',
          type: 'comment',
          datetime: comment.created,
          header: '<i class="bi-chat-left-text me-2"></i>Comment',
          body: comment.content,
          footer: '',
        }
        switch(type){
          case "emls":
            if(timeline.find('[data-type="comment"][data-id="'+comment.id+'"]').length <= 0){
              if(timeline.find('[data-type="eml"][data-id="'+parseInt(identifier)+'"]').length > 0){
                const emlObject = timeline.find('[data-type="eml"][data-id="'+parseInt(identifier)+'"]')
                timeline.timeline.object(item,function(object){
                  object.attr('data-id',comment.id).attr('data-after','eml:'+parseInt(identifier))
                  object.item.css('margin-left',(parseInt(emlObject.find('.timeline-item').css('margin-left')) + parseInt(object.item.css('margin-left'))) + 'px')
                  object.item.footer.addClass('d-flex justify-content-between align-items-center border-top user-select-none')
                  object.item.footer.owner = $(document.createElement('div')).html('Commented by:').appendTo(object.item.footer)
                  object.item.footer.owner.badge = $(document.createElement('span')).addClass('badge bg-primary ms-2 shadow').html(comment.owner.users).appendTo(object.item.footer.owner)
                  object.item.footer.controls = $(document.createElement('div')).addClass('btn-group border shadow').appendTo(object.item.footer)
                  object.item.footer.controls.note = $(document.createElement('button')).addClass('btn btn-warning').attr('data-linkTo','comments:'+comment.id).html('Note').appendTo(object.item.footer.controls)
                  object.item.footer.controls.note.icon = $(document.createElement('i')).addClass('bi-sticky me-1').prependTo(object.item.footer.controls.note)
                  object.item.footer.controls.note.click(function(){
                    addNote($(this))
                  })
                  object.attr('data-search',object.text().toString().toUpperCase())
                  object.item.header.addClass('text-bg-primary rounded-top user-select-none')
                  object.item.time.addClass('text-light')
                  emlObject.after(object)
                })
              }
            }
            break;
          case "topics":
            if(identifier == topicData.id){
              if(timeline.find('[data-type="comment"][data-id="'+comment.id+'"]').length <= 0){
                timeline.timeline.object(item,function(object){
                  object.attr('data-id',comment.id)
                  object.item.footer.addClass('d-flex justify-content-between align-items-center border-top user-select-none')
                  object.item.footer.owner = $(document.createElement('div')).html('Commented by:').appendTo(object.item.footer)
                  object.item.footer.owner.badge = $(document.createElement('span')).addClass('badge bg-primary ms-2 shadow').html(comment.owner.users).appendTo(object.item.footer.owner)
                  object.item.footer.controls = $(document.createElement('div')).addClass('btn-group border shadow').appendTo(object.item.footer)
                  object.item.footer.controls.note = $(document.createElement('button')).addClass('btn btn-warning').attr('data-linkTo','comments:'+comment.id).html('Note').appendTo(object.item.footer.controls)
                  object.item.footer.controls.note.icon = $(document.createElement('i')).addClass('bi-sticky me-1').prependTo(object.item.footer.controls.note)
                  object.item.footer.controls.note.click(function(){
                    addNote($(this))
                  })
                  object.attr('data-search',object.text().toString().toUpperCase())
                  object.item.header.addClass('text-bg-primary rounded-top user-select-none')
                  object.item.time.addClass('text-light')
                })
              }
            }
            break;
        }
      }
    }
    const addNoteObject = function(note){
      if(note.linkTo == null){
        note.linkTo = [{topics:topicData.id}]
      }
      for(const [key, link] of Object.entries(note.linkTo)){
        const type = Object.keys(link)[0]
        const identifier = link[type]
        let item = {
          icon: 'sticky',
          color: 'warning',
          type: 'note',
          datetime: note.created,
          header: '<i class="bi-sticky me-2"></i>Note',
          body: note.content,
          footer: '',
        }
        switch(type){
          case "comments":
            if(timeline.find('[data-type="note"][data-id="'+note.id+'"]').length <= 0){
              if(timeline.find('[data-type="comment"][data-id="'+parseInt(identifier)+'"]').length > 0){
                const commentObject = timeline.find('[data-type="comment"][data-id="'+parseInt(identifier)+'"]')
                timeline.timeline.object(item,function(object){
                  object.attr('data-id',note.id).attr('data-after','comment:'+parseInt(identifier))
                  object.item.css('margin-left',(parseInt(commentObject.find('.timeline-item').css('margin-left')) + parseInt(object.item.css('margin-left'))) + 'px')
                  object.item.footer.addClass('d-flex justify-content-between align-items-center border-top user-select-none')
                  object.item.footer.owner = $(document.createElement('div')).html('Posted by:').appendTo(object.item.footer)
                  object.item.footer.owner.badge = $(document.createElement('span')).addClass('badge bg-warning ms-2 shadow').html(note.owner.users).appendTo(object.item.footer.owner)
                  object.attr('data-search',object.text().toString().toUpperCase())
                  object.item.header.addClass('text-bg-warning rounded-top user-select-none')
                  object.item.time.addClass('text-dark')
                  commentObject.after(object)
                })
              }
            }
            break;
          case "emls":
            if(timeline.find('[data-type="note"][data-id="'+note.id+'"]').length <= 0){
              if(timeline.find('[data-type="eml"][data-id="'+parseInt(identifier)+'"]').length > 0){
                const emlObject = timeline.find('[data-type="eml"][data-id="'+parseInt(identifier)+'"]')
                timeline.timeline.object(item,function(object){
                  object.attr('data-id',note.id).attr('data-after','eml:'+parseInt(identifier))
                  object.item.css('margin-left',(parseInt(emlObject.find('.timeline-item').css('margin-left')) + parseInt(object.item.css('margin-left'))) + 'px')
                  object.item.footer.addClass('d-flex justify-content-between align-items-center border-top user-select-none')
                  object.item.footer.owner = $(document.createElement('div')).html('Posted by:').appendTo(object.item.footer)
                  object.item.footer.owner.badge = $(document.createElement('span')).addClass('badge bg-warning ms-2 shadow').html(note.owner.users).appendTo(object.item.footer.owner)
                  object.attr('data-search',object.text().toString().toUpperCase())
                  object.item.header.addClass('text-bg-warning rounded-top user-select-none')
                  object.item.time.addClass('text-dark')
                  emlObject.after(object)
                })
              }
            }
            break;
          case "topics":
            if(identifier == topicData.id){
              if(timeline.find('[data-type="note"][data-id="'+note.id+'"]').length <= 0){
                timeline.timeline.object(item,function(object){
                  object.attr('data-id',note.id)
                  object.item.footer.addClass('d-flex justify-content-between align-items-center border-top user-select-none')
                  object.item.footer.owner = $(document.createElement('div')).html('Posted by:').appendTo(object.item.footer)
                  object.item.footer.owner.badge = $(document.createElement('span')).addClass('badge bg-warning ms-2 shadow').html(note.owner.users).appendTo(object.item.footer.owner)
                  object.attr('data-search',object.text().toString().toUpperCase())
                  object.item.header.addClass('text-bg-warning rounded-top user-select-none')
                  object.item.time.addClass('text-dark')
                })
              }
            }
            break;
        }
      }
    }
    const addContact = function(){
      Modal.create({title:'Contact',icon:'person-plus',color:'success',body:''},function(modal){
        modal.header.addClass('text-bg-success')
        modal.footer.group.primary.html('Save')
        modal.body.form = $(document.createElement('div')).addClass('needs-validation').appendTo(modal.body)
        modal.body.form.group = $(document.createElement('div')).addClass('input-group has-validation').appendTo(modal.body.form)
        modal.body.form.group.input = $(document.createElement('input')).attr('type','email').attr('placeholder','Email Address').addClass('form-control rounded-end').appendTo(modal.body.form.group)
        modal.body.form.group.input.tooltip = $(document.createElement('div')).html('Invalid Email Address').addClass('invalid-tooltip').appendTo(modal.body.form.group)
        modal.footer.group.primary.click(function(){
          var contact = modal.body.form.group.input.val()
          modal.body.form.removeClass('was-validated')
          if(validateEmail(contact)){
            modal.body.form.addClass('was-validated')
            modal.body.form.group.input.addClass('is-valid')
            modal.body.form.group.input.removeClass('is-invalid')
            modal.body.form.group.input.addClass('valid')
            modal.body.form.group.input.removeClass('invalid')
            modal.body.form.group.input[0].setCustomValidity('')
            modal.bootstrap.hide()
            API.get("topic/addContact/?id="+topicData.id+"&contact="+contact+"&csrf="+CSRF,{
              success:function(result,status,xhr){
                addContactObject(contact)
              }
            })
          } else {
            modal.body.form.addClass('was-validated')
            modal.body.form.group.input.addClass('is-invalid')
            modal.body.form.group.input.removeClass('is-valid')
            modal.body.form.group.input.addClass('invalid')
            modal.body.form.group.input.removeClass('valid')
            modal.body.form.group.input[0].setCustomValidity('Invalid Email Address')
          }
        })
      })
    }
    const reloadContactCount = function(){
      countContacts.html(listContacts.find('li').length - 1)
    }
    const getContacts = function(){
      var array = []
      listContacts.find('[data-value]').each(function(){
        array.push($(this).attr('data-value'))
      })
      return array
    }
    const addContactObject = function(value){
      if(listContacts.find('[data-value="'+value+'"]').length <= 0){
        let item = $(document.createElement('li')).attr('data-value',value).addClass('list-group-item cursor-pointer').appendTo(listContacts)
        item.data = value
        item.css('transition','all 300ms').css('-webkit-transition','all 300ms').css('-ms-transition','all 300ms').css('-o-transition','all 300ms')
        item.contact = $(document.createElement('div')).html(value).appendTo(item)
        item.contact.icon = $(document.createElement('i')).addClass('bi-person-lines-fill me-2').prependTo(item.contact)
        item.hover(function(){
          item.addClass("text-bg-primary")
        }, function(){
          item.removeClass("text-bg-primary")
        });
        item.click(function(){
          copyToClipboard($(this))
        })
        reloadContactCount()
      }
    }
    addContactBtn.click(function(){
      addContact()
    })
    const setContacts = function(data){
      for(const [key, value] of Object.entries(data)){
        addContactObject(value)
      }
    }
    addFileBtn.click(function(){
      uploadFile()
    })
    const reloadFileCount = function(){
      countFiles.html(listFiles.find('li').length - 1)
      countTrash.html(listTrash.find('li').length)
    }
    const uploadFile = function(){
      File.upload(function(file){
        if(typeof topicData.sharedTo !== 'undefined'){
          file.sharedTo = JSON.stringify(topicData.sharedTo)
        }
        file.isPublic = 1
        return file
      },function(file){
        API.get("topic/addFile/?id="+topicData.id+"&file="+file.id+"&csrf="+CSRF,{
          success:function(result,status,xhr){
            addFileObject(file)
          }
        })
      })
    }
    const publishFile = function(fileID){

      // Get Elements
      var filesObject = files.find('[data-fileID="'+fileID+'"]')
      filesObject.publish = filesObject.find('[data-fileAction="publish"]')
      filesObject.unpublish = filesObject.find('[data-fileAction="unpublish"]')
      var timelineObject = timeline.find('[data-fileID="'+fileID+'"]')
      timelineObject.publish = timelineObject.find('[data-fileAction="publish"]')
      timelineObject.unpublish = timelineObject.find('[data-fileAction="unpublish"]')

      API.get("file/publish/?id="+fileID+"&csrf="+CSRF,{
        success:function(result,status,xhr){
          filesObject.publish.addClass('d-none')
          filesObject.unpublish.removeClass('d-none')
          timelineObject.publish.addClass('d-none')
          timelineObject.unpublish.removeClass('d-none')
          Toast.create({title:'Published',icon:'globe2',color:'success',close:false})
        }
      })
    }
    const unpublishFile = function(fileID){

      // Get Elements
      var filesObject = files.find('[data-fileID="'+fileID+'"]')
      filesObject.publish = filesObject.find('[data-fileAction="publish"]')
      filesObject.unpublish = filesObject.find('[data-fileAction="unpublish"]')
      var timelineObject = timeline.find('[data-fileID="'+fileID+'"]')
      timelineObject.publish = timelineObject.find('[data-fileAction="publish"]')
      timelineObject.unpublish = timelineObject.find('[data-fileAction="unpublish"]')

      API.get("file/unpublish/?id="+fileID+"&csrf="+CSRF,{
        success:function(result,status,xhr){
          filesObject.publish.removeClass('d-none')
          filesObject.unpublish.addClass('d-none')
          timelineObject.publish.removeClass('d-none')
          timelineObject.unpublish.addClass('d-none')
          Toast.create({title:'Unpublished',icon:'globe2',color:'success',close:false})
        }
      })
    }
    const restoreFile = function(fileID){
      API.get("file/restore/?id="+fileID+"&csrf="+CSRF,{
        success:function(result,status,xhr){

          // Get Elements
          var filesObject = trash.find('[data-fileID="'+fileID+'"]')
          filesObject.download = filesObject.find('[data-fileAction="download"]')
          filesObject.unpublish = filesObject.find('[data-fileAction="unpublish"]')
          filesObject.publish = filesObject.find('[data-fileAction="publish"]')
          filesObject.trash = filesObject.find('[data-fileAction="trash"]')
          filesObject.restore = filesObject.find('[data-fileAction="restore"]')
          var timelineObject = timeline.find('[data-fileID="'+fileID+'"]')
          timelineObject.preview = timelineObject.find('[data-fileAction="preview"]')
          timelineObject.publish = timelineObject.find('[data-fileAction="publish"]')
          timelineObject.unpublish = timelineObject.find('[data-fileAction="unpublish"]')
          timelineObject.download = timelineObject.find('[data-fileAction="download"]')
          timelineObject.trash = timelineObject.find('[data-fileAction="trash"]')
          timelineObject.restore = timelineObject.find('[data-fileAction="restore"]')

          // Edit Elements
          filesObject.attr('data-isDelected',false).appendTo(listFiles)
          if(result.isPublic){
            filesObject.unpublish.removeClass('d-none')
            filesObject.publish.addClass('d-none')
            timelineObject.unpublish.removeClass('d-none')
            timelineObject.publish.addClass('d-none')
          } else {
            filesObject.unpublish.addClass('d-none')
            filesObject.publish.removeClass('d-none')
            timelineObject.unpublish.addClass('d-none')
            timelineObject.publish.removeClass('d-none')
          }
          filesObject.download.removeClass('d-none')
          filesObject.trash.removeClass('d-none')
          filesObject.restore.addClass('d-none')
          timelineObject.attr('data-isDelected',false).removeClass('d-none')
          timelineObject.preview.removeClass('d-none')
          timelineObject.download.removeClass('d-none')
          timelineObject.trash.removeClass('d-none')
          timelineObject.restore.addClass('d-none')

          // Reload Counts
          reloadFileCount()

          // Toast
          Toast.create({title:'Restored',icon:'arrow-counterclockwise',color:'success',close:false})
        }
      })
    }
    const deleteFile = function(fileID){
      API.get("file/delete/?id="+fileID+"&csrf="+CSRF,{
        success:function(result,status,xhr){

          // Get Elements
          var filesObject = files.find('[data-fileID="'+fileID+'"]')
          filesObject.download = filesObject.find('[data-fileAction="download"]')
          filesObject.unpublish = filesObject.find('[data-fileAction="unpublish"]')
          filesObject.publish = filesObject.find('[data-fileAction="publish"]')
          filesObject.trash = filesObject.find('[data-fileAction="trash"]')
          filesObject.restore = filesObject.find('[data-fileAction="restore"]')
          var timelineObject = timeline.find('[data-fileID="'+fileID+'"]')
          timelineObject.preview = timelineObject.find('[data-fileAction="preview"]')
          timelineObject.publish = timelineObject.find('[data-fileAction="publish"]')
          timelineObject.unpublish = timelineObject.find('[data-fileAction="unpublish"]')
          timelineObject.download = timelineObject.find('[data-fileAction="download"]')
          timelineObject.trash = timelineObject.find('[data-fileAction="trash"]')
          timelineObject.restore = timelineObject.find('[data-fileAction="restore"]')

          // Edit Elements
          filesObject.attr('data-isDelected',true).appendTo(listTrash)
          filesObject.download.addClass('d-none')
          filesObject.unpublish.addClass('d-none')
          filesObject.publish.addClass('d-none')
          filesObject.trash.addClass('d-none')
          filesObject.restore.removeClass('d-none')
          timelineObject.attr('data-isDelected',true)
          timelineObject.preview.addClass('d-none')
          timelineObject.publish.addClass('d-none')
          timelineObject.unpublish.addClass('d-none')
          timelineObject.download.addClass('d-none')
          timelineObject.trash.addClass('d-none')
          timelineObject.restore.removeClass('d-none')
          if(!Authorization.trashed){
            timelineObject.addClass('d-none')
          }

          // Reload Counts
          reloadFileCount()

          // Toast
          Toast.create({title:'Deleted',icon:'trash',color:'success',close:false})
        }
      })
    }
    const addFileObject = function(file){
      let item = $(document.createElement('li')).addClass('list-group-item cursor-pointer user-select-none').attr('data-isDelected',file.isDeleted).attr('data-fileID',file.id)
      item.data = file
      item.css('transition','all 300ms').css('-webkit-transition','all 300ms').css('-ms-transition','all 300ms').css('-o-transition','all 300ms')
      item.flex = $(document.createElement('div')).addClass('d-flex align-items-center').appendTo(item)
      item.flex.icon = $(document.createElement('div')).addClass('flex-shrink-1 px-1').appendTo(item.flex)
      item.flex.icon.i = $(document.createElement('i')).appendTo(item.flex.icon)
      switch(file.type){
        case "aac":
        case "ai":
        case "bmp":
        case "cs":
        case "css":
        case "csv":
        case "doc":
        case "docx":
        case "exe":
        case "gif":
        case "heic":
        case "html":
        case "java":
        case "jpg":
        case "js":
        case "json":
        case "jsx":
        case "key":
        case "m4p":
        case "md":
        case "mdx":
        case "mov":
        case "mp3":
        case "mp4":
        case "otf":
        case "pdf":
        case "php":
        case "png":
        case "ppt":
        case "pptx":
        case "psd":
        case "py":
        case "raw":
        case "rb":
        case "sass":
        case "scss":
        case "sh":
        case "sql":
        case "svg":
        case "tiff":
        case "tsx":
        case "ttf":
        case "txt":
        case "wav":
        case "woff":
        case "xls":
        case "xlsx":
        case "xml":
        case "yml":
          item.flex.icon.i.addClass('bi-filetype-'+file.type)
          break;
        default:
          item.flex.icon.i.addClass('bi-file-earmark')
          break;
      }
      item.flex.name = $(document.createElement('div')).addClass('flex-grow-1 px-1 text-break').html(file.filename).appendTo(item.flex)
      item.flex.size = $(document.createElement('div')).addClass('flex-shrink-1 px-1').html(formatBytes(file.size)).appendTo(item.flex)
      item.flex.action = $(document.createElement('div')).addClass('flex-shrink-1 mx-1 btn-group border shadow').appendTo(item.flex)
      item.flex.action.download = $(document.createElement('button')).attr('data-fileAction','download').addClass('btn btn-sm btn-light d-none').appendTo(item.flex.action)
      item.flex.action.download.icon = $(document.createElement('i')).addClass('bi-arrow-bar-down').appendTo(item.flex.action.download)
      item.flex.action.download.click(function(){
        File.download(file.id)
      })
      item.flex.action.publish = $(document.createElement('button')).attr('data-fileAction','publish').addClass('btn btn-sm btn-light d-none').appendTo(item.flex.action)
      item.flex.action.publish.icon = $(document.createElement('i')).addClass('bi-share').appendTo(item.flex.action.publish)
      item.flex.action.publish.click(function(){
        publishFile(file.id)
      })
      item.flex.action.unpublish = $(document.createElement('button')).attr('data-fileAction','unpublish').addClass('btn btn-sm btn-primary d-none').appendTo(item.flex.action)
      item.flex.action.unpublish.icon = $(document.createElement('i')).addClass('bi-globe2').appendTo(item.flex.action.unpublish)
      item.flex.action.unpublish.click(function(){
        unpublishFile(file.id)
      })
      item.flex.action.trash = $(document.createElement('button')).attr('data-fileAction','trash').addClass('btn btn-sm btn-danger rounded-end d-none').appendTo(item.flex.action)
      item.flex.action.trash.icon = $(document.createElement('i')).addClass('bi-trash').appendTo(item.flex.action.trash)
      item.flex.action.trash.click(function(){
        deleteFile(file.id)
      })
      item.flex.action.restore = $(document.createElement('button')).attr('data-fileAction','restore').addClass('btn btn-sm btn-info rounded d-none').appendTo(item.flex.action)
      item.flex.action.restore.icon = $(document.createElement('i')).addClass('bi-arrow-counterclockwise').appendTo(item.flex.action.restore)
      item.flex.action.restore.click(function(){
        restoreFile(file.id)
      })
      item.hover(function(){
        item.addClass("text-bg-primary")
      }, function(){
        item.removeClass("text-bg-primary")
      });
      item.flex.icon.click(function(){
        File.preview(file.id)
      })
      item.flex.name.click(function(){
        File.preview(file.id)
      })
      item.flex.size.click(function(){
        File.preview(file.id)
      })
      if(file.isDeleted){
        item.appendTo(listTrash)
        item.flex.action.restore.removeClass('d-none')
      } else {
        item.appendTo(listFiles)
        item.flex.action.download.removeClass('d-none')
        if(file.isPublic){
          item.flex.action.unpublish.removeClass('d-none')
        } else {
          item.flex.action.publish.removeClass('d-none')
        }
        item.flex.action.trash.removeClass('d-none')
      }
      reloadFileCount()
    }
    const setFiles = function(data){
      for(const [id, file] of Object.entries(data)){
        addFileObject(file)
      }
    }
    const setDataset = function(data){
      const list = dataset.find('.list-group')
      for(const [datakey, values] of Object.entries(data)){
        let item = $(document.createElement('li')).addClass('list-group-item user-select-none').appendTo(list)
        item.flex = $(document.createElement('div')).addClass('d-flex align-items-center').appendTo(item)
        item.flex.datakey = $(document.createElement('div')).addClass('flex-shrink-1 pe-3 fw-bold').html(datakey+":").appendTo(item.flex)
        item.flex.values = $(document.createElement('div')).addClass('flex-grow-1').appendTo(item.flex)
        for(const [key, value] of Object.entries(values)){
          var badge = $(document.createElement('span')).addClass('badge bg-primary rounded-0 rounded-start ms-1 cursor-pointer').html(value).appendTo(item.flex.values)
          badge.delete = $(document.createElement('span')).addClass('badge bg-danger rounded-0 rounded-end me-1 cursor-pointer').attr('data-value',value).appendTo(item.flex.values)
          badge.delete.icon = $(document.createElement('i')).addClass('bi-trash').appendTo(badge.delete)
          badge.click(function(){
            copyToClipboard($(this))
          })
        }
      }
    }
    const setMeta = function(data){
      const list = meta.find('.list-group')
      for(const [datakey, values] of Object.entries(data)){
        let item = $(document.createElement('li')).addClass('list-group-item').appendTo(list)
        item.flex = $(document.createElement('div')).addClass('d-flex align-items-center').appendTo(item)
        item.flex.datakey = $(document.createElement('div')).addClass('flex-shrink-1 pe-3 fw-bold').html(datakey+":").appendTo(item.flex)
        item.flex.values = $(document.createElement('div')).addClass('flex-grow-1').appendTo(item.flex)
        for(const [key, value] of Object.entries(values)){
          const badge = $(document.createElement('span')).addClass('badge bg-primary mx-1 cursor-pointer').html(value).appendTo(item.flex.values)
          badge.click(function(){
            copyToClipboard($(this))
          })
        }
      }
    }
    const setCount = function(data){
      countUnread.html(data)
    }
    const setStatus = function(data){
      let color = 'secondary', icon = 'question', text = 'Unknown'
      status.removeClass('btn-light btn-info btn-primary btn-success btn-secondary')
      switch(data){
        case 0:
          color = 'info'
          icon = 'chat-text'
          text = 'New'
          break
        case 1:
          color = 'primary'
          icon = 'chat'
          text = 'Open'
          break
        case 2:
          color = 'success'
          icon = 'folder-check'
          text = 'Closed'
          break
        case 3:
          color = 'secondary'
          icon = 'folder-symlink'
          text = 'Merged'
          break
      }
      status.attr('data-status',data).addClass('btn-'+color).html('<i class="bi-'+icon+' me-2"></i>'+text)
    }
    Auth.isAuthorized('topic/details/trash',function(token){
      if(token){
        Authorization.trash = true
        trash.removeClass('d-none')
      } else {
        Authorization.trash = false
        trash.addClass('d-none')
      }
    })
    Auth.isAuthorized('topic/details/trashed',function(token){
      if(token){
        Authorization.trashed = true
        timeline.find('[data-isDeleted="true"]').removeClass('d-none')
      } else {
        Authorization.trashed = false
        timeline.find('[data-isDeleted="true"]').addClass('d-none')
      }
    })
    API.get("topic/get/?id=<?= $id ?>",{
      success:function(result,status,xhr){
        if(typeof result[0] !== "undefined"){
          topic.removeClass('d-none')
          topicData = result[0]
          reply.attr('data-linkTo','topics:'+topicData.id).click(function(){
            addReply($(this))
          })
          note.attr('data-linkTo','topics:'+topicData.id).click(function(){
            addNote($(this))
          })
          comment.attr('data-linkTo','topics:'+topicData.id).click(function(){
            addComment($(this))
          })
          setStatus(topicData.status)
          setCount(topicData.countUnread)
          setDataset(topicData.dataset)
          setMeta(topicData.meta)
          setFiles(topicData.files)
          setContacts(topicData.contacts)
          topic.find('button[aria-controls="offcanvasActivity"]').click(function(){
            Activity.show("topics_topics",topicData.id)
          })
          for(let [emlID, eml] of Object.entries(topicData.emls)){
            addEmlObject(eml)
          }
          for(let [commentID, comment] of Object.entries(topicData.comments)){
            addCommentObject(comment)
          }
          for(let [noteID, note] of Object.entries(topicData.notes)){
            addNoteObject(note)
          }
          timeline.timeline.sort()
        } else {
          notfound.removeClass('d-none')
        }
      },
      error:function(xhr,status,error){
    		denied.removeClass('d-none')
    	}
    })
  })
</script>
