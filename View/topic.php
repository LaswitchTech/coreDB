<?php if(isset($_GET['id'])){ $id = $_GET['id']; } else { $id = ""; } ?>
<div class="row m-0 h-100 d-none" id="topic">
  <div class="col-sm-6 col-lg-4 p-2 ps-4">
    <div class="card shadow" id="details">
      <div class="card-header user-select-none">
        <h5 class="card-title my-2 fw-light"><i class="bi-chat-text me-2"></i>Details</h5>
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
        <h5 class="card-title my-2 fw-light"><i class="bi-chat-quote me-2"></i>Similar Topics</h5>
      </div>
      <div class="card-body collapse p-0" id="collapseTopics">
        <ul class="list-group list-group-flush rounded-bottom"></ul>
      </div>
    </div>
    <div class="card shadow mt-3" id="files">
      <div class="card-header user-select-none cursor-pointer" data-bs-toggle="collapse" href="#collapseFiles">
        <h5 class="card-title my-2 fw-light"><i class="bi-files me-2"></i>Files<span class="badge bg-light border float-end text-dark shadow"><i class="bi-files me-2"></i><span class="badge bg-primary" id="countFiles"></span></span></h5>
      </div>
      <div class="card-body collapse show p-0" id="collapseFiles">
        <ul class="list-group list-group-flush rounded-bottom">
          <li class="list-group-item user-select-none bg-light">
            <div class="d-flex justify-content-center align-items-center">
              <div class="btn-group border shadow w-100">
                <button class="btn btn-success" type="button" id="addFileBtn"><i class="bi-box-arrow-in-up"></i></button>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <div class="card shadow mt-3 d-none" id="trash">
      <div class="card-header user-select-none cursor-pointer" data-bs-toggle="collapse" href="#collapseTrash">
        <h5 class="card-title my-2 fw-light"><i class="bi-trash me-2"></i>Trash<span class="badge bg-light border float-end text-dark shadow"><i class="bi-files me-2"></i><span class="badge bg-danger" id="countTrash"></span></span></h5>
      </div>
      <div class="card-body collapse p-0" id="collapseTrash">
        <ul class="list-group list-group-flush rounded-bottom"></ul>
      </div>
    </div>
    <div class="card shadow mt-3" id="contacts">
      <div class="card-header user-select-none cursor-pointer" data-bs-toggle="collapse" href="#collapseContacts">
        <h5 class="card-title my-2 fw-light"><i class="bi-person-rolodex me-2"></i>Contacts</h5>
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
        <h5 class="card-title my-2 fw-light"><i class="bi-database me-2"></i>Dataset</h5>
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
        <h5 class="card-title my-2 fw-light"><i class="bi-clipboard-data me-2"></i>Meta</h5>
      </div>
      <div class="card-body collapse p-0" id="collapseMeta">
        <ul class="list-group list-group-flush rounded-bottom"></ul>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-8 p-2 pe-4">
    <div class="card shadow">
      <div class="card-header user-select-none">
        <h5 class="card-title my-2 fw-light"><i class="bi-clock me-2"></i>Timeline<span class="badge bg-light border float-end text-dark shadow"><i class="bi-star me-2"></i><span class="badge bg-primary" id="countUnread"></span></span></h5>
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
    const meta = $('#meta')
    const files = $('#files')
    const countFiles = $('#countFiles')
    const trash = $('#trash')
    const countTrash = $('#countTrash')
    const contacts = $('#contacts')
    const reply = $('#reply')
    const note = $('#note')
    const comment = $('#comment')
    const share = $('#share')
    const status = $('#status')
    const countUnread = $('#countUnread')
    let timeline = $('#timeline')
    timeline.timeline = Timeline.create()
    timeline.timeline.appendTo(timeline)
    const addComment = function(button,topicData){
      Modal.create({title:'Add Comment',size:'lg',color:"primary",icon:'chat-left-text',body:''},function(modal){
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
                addCommentObject(result,topicData)
                timeline.timeline.sort()
                Toast.create({title:'Saved',icon:'check2',color:'success',close:false})
              }
            })
            modal.bootstrap.hide()
          }
        })
      })
    }
    const addNote = function(button,topicData){
      Modal.create({title:'Add Note',size:'lg',color:"warning",icon:'sticky',body:''},function(modal){
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
                addNoteObject(result,topicData)
                timeline.timeline.sort()
                Toast.create({title:'Saved',icon:'check2',color:'success',close:false})
              }
            })
            modal.bootstrap.hide()
          }
        })
      })
    }
    const addEmlObject = function(eml,topicData){
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
          object.item.body.contacts = $(document.createElement('div')).addClass('border-bottom p-3 bg-light').appendTo(object.item.body)
          for(const [key, contact] of Object.entries(eml.contacts)){
            $(document.createElement('span')).addClass('badge bg-primary me-2 shadow').html(contact).appendTo(object.item.body.contacts)
          }
          object.item.body.content = $(document.createElement('div')).addClass('p-3 border-bottom').html(eml.body_stripped.replaceAll('<div><br></div>', '').replaceAll('<div></div>', '').replaceAll('\r\n', '<br>')).appendTo(object.item.body)
          object.item.body.files = $(document.createElement('div')).addClass('border-bottom p-3 d-flex flex-wrap bg-light')
          if(eml.files.length > 0){
            object.item.body.files.appendTo(object.item.body)
          }
          for(const [key, file] of Object.entries(eml.files)){
            if(typeof topicData.files[file] !== 'undefined'){
              let fileElement = $(document.createElement('div')).addClass('card m-1 cursor-pointer shadow').css('max-width','175px').appendTo(object.item.body.files)
              fileElement.css('transition','all 300ms').css('-webkit-transition','all 300ms').css('-ms-transition','all 300ms').css('-o-transition','all 300ms')
              fileElement.data = topicData.files[file]
              if(fileElement.data.isDeleted){
                fileElement.addClass("text-bg-danger").addClass('d-none').attr('data-isDelected',true)
              }
              fileElement.body = $(document.createElement('div')).addClass('card-body rounded-top text-center py-3 px-5')
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
                if(fileElement.data.isDeleted){
                  fileElement.removeClass("text-bg-danger")
                }
                fileElement.addClass("text-bg-primary")
              }, function(){
                fileElement.removeClass("text-bg-primary")
                if(fileElement.data.isDeleted){
                  fileElement.addClass("text-bg-danger")
                }
              });
              fileElement.click(function(){
                File.download(fileElement.data.id)
              })
            }
          }
          object.item.footer.addClass('d-flex justify-content-between align-items-center')
          object.item.footer.sender = $(document.createElement('div')).html('Sender:').appendTo(object.item.footer)
          object.item.footer.sender.badge = $(document.createElement('span')).addClass('badge bg-primary ms-2 shadow').html(eml.sender).appendTo(object.item.footer.sender)
          object.item.footer.controls = $(document.createElement('div')).addClass('btn-group border shadow').appendTo(object.item.footer)
          object.item.footer.controls.note = $(document.createElement('button')).addClass('btn btn-warning').attr('data-linkTo','emls:'+eml.id).html('Note').appendTo(object.item.footer.controls)
          object.item.footer.controls.note.icon = $(document.createElement('i')).addClass('bi-sticky me-1').prependTo(object.item.footer.controls.note)
          object.item.footer.controls.note.click(function(){
            addNote($(this),topicData)
          })
          object.item.footer.controls.comment = $(document.createElement('button')).addClass('btn btn-primary').attr('data-linkTo','emls:'+eml.id).html('Comment').appendTo(object.item.footer.controls)
          object.item.footer.controls.comment.icon = $(document.createElement('i')).addClass('bi-chat-left-text me-1').prependTo(object.item.footer.controls.comment)
          object.item.footer.controls.comment.click(function(){
            addComment($(this),topicData)
          })
          object.item.footer.controls.reply = $(document.createElement('button')).addClass('btn btn-light').html('Reply').appendTo(object.item.footer.controls)
          object.item.footer.controls.reply.icon = $(document.createElement('i')).addClass('bi-reply me-1').prependTo(object.item.footer.controls.reply)
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
    const addCommentObject = function(comment,topicData){
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
                    addNote($(this),topicData)
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
                    addNote($(this),topicData)
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
    const addNoteObject = function(note,topicData){
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
    const setContacts = function(data){
      const list = contacts.find('.list-group')
      for(const [key, value] of Object.entries(data)){
        let item = $(document.createElement('li')).addClass('list-group-item cursor-pointer').appendTo(list)
        item.data = data
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
      }
    }
    const addFileBtn = $('#addFileBtn')
    addFileBtn.click(function(){
      uploadFile()
    })
    const uploadFile = function(){
      const listFiles = files.find('.list-group')
      Modal.create({title:'Upload File',size:'lg',color:"success",icon:'box-arrow-in-up',body:''},function(modal){
        // modal.body.addClass('p-0')
        modal.body.inputGroup = $(document.createElement('div')).addClass('file-loading').appendTo(modal.body)
        // modal.body.inputGroup.label = $(document.createElement('label')).attr('for','addFileInput').addClass('form-label').appendTo(modal.body.inputGroup)
        modal.body.inputGroup.input = $(document.createElement('input')).attr('name','addFileInput[]').attr('multiple','multiple').attr('type','file').attr('id','addFileInput').addClass('form-control file').appendTo(modal.body.inputGroup)
        modal.body.inputError = $(document.createElement('div')).attr('id','addFileInputError').appendTo(modal.body)
        modal.body.inputGroup.input.fileinput({
          showPreview: true,
          showUpload: false,
          showRemove: false,
          browseOnZoneClick: true,
          elErrorContainer: '#addFileInputError',
          showCancel: false,
          // allowedFileExtensions: ["jpg", "png", "gif"],
        })
        // <div class="file-loading">
        //   <input id="input-b9" name="input-b9[]" multiple type="file"'>
        // </div>
        // <div id="kartik-file-errors"></div>
        //
        modal.footer.group.primary.click(function(){
          // API.post("topic/comment/?id="+topicData.id,comment,{
          //   success:function(result,status,xhr){
          //     addCommentObject(result,topicData)
          //     timeline.timeline.sort()
          //     Toast.create({title:'Saved',icon:'check2',color:'success',close:false})
          //   }
          // })
          modal.bootstrap.hide()
        })
      })
    }
    const publishFile = function(file){
      API.get("file/publish/?id="+file.data.id+"&csrf="+CSRF,{
        success:function(result,status,xhr){
          file.flex.action.unpublish.removeClass('d-none')
          file.flex.action.publish.addClass('d-none')
          Toast.create({title:'Published',icon:'globe2',color:'success',close:false})
        }
      })
    }
    const unpublishFile = function(file){
      API.get("file/unpublish/?id="+file.data.id+"&csrf="+CSRF,{
        success:function(result,status,xhr){
          file.flex.action.unpublish.addClass('d-none')
          file.flex.action.publish.removeClass('d-none')
          Toast.create({title:'Unpublished',icon:'globe2',color:'success',close:false})
        }
      })
    }
    const restoreFile = function(file){
      const listFiles = files.find('.list-group')
      const listTrash = trash.find('.list-group')
      API.get("file/restore/?id="+file.data.id+"&csrf="+CSRF,{
        success:function(result,status,xhr){
          file.appendTo(listFiles)
          if(result.isPublic){
            file.flex.action.unpublish.removeClass('d-none')
            file.flex.action.publish.addClass('d-none')
          } else {
            file.flex.action.unpublish.addClass('d-none')
            file.flex.action.publish.removeClass('d-none')
          }
          file.flex.action.trash.removeClass('d-none')
          file.flex.action.restore.addClass('d-none')
          Toast.create({title:'Restored',icon:'arrow-counterclockwise',color:'success',close:false})
        }
      })
    }
    const deleteFile = function(file){
      const listFiles = files.find('.list-group')
      const listTrash = trash.find('.list-group')
      API.get("file/delete/?id="+file.data.id+"&csrf="+CSRF,{
        success:function(result,status,xhr){
          file.appendTo(listTrash)
          file.flex.action.unpublish.addClass('d-none')
          file.flex.action.publish.addClass('d-none')
          file.flex.action.trash.addClass('d-none')
          file.flex.action.restore.removeClass('d-none')
          Toast.create({title:'Deleted',icon:'trash',color:'success',close:false})
        }
      })
    }
    const setFiles = function(data){
      const listFiles = files.find('.list-group')
      const listTrash = trash.find('.list-group')
      for(const [id, file] of Object.entries(data)){
        let item = $(document.createElement('li')).addClass('list-group-item cursor-pointer')
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
        item.flex.name = $(document.createElement('div')).addClass('flex-grow-1 px-1').html(file.filename).appendTo(item.flex)
        item.flex.size = $(document.createElement('div')).addClass('flex-shrink-1 px-1').html(formatBytes(file.size)).appendTo(item.flex)
        item.flex.action = $(document.createElement('div')).addClass('flex-shrink-1 mx-1 btn-group border shadow').appendTo(item.flex)
        item.flex.action.publish = $(document.createElement('button')).addClass('btn btn-sm btn-light rounded-start d-none').appendTo(item.flex.action)
        item.flex.action.publish.icon = $(document.createElement('i')).addClass('bi-globe2').appendTo(item.flex.action.publish)
        item.flex.action.publish.click(function(){
          publishFile(item)
        })
        item.flex.action.unpublish = $(document.createElement('button')).addClass('btn btn-sm btn-primary rounded-start d-none').appendTo(item.flex.action)
        item.flex.action.unpublish.icon = $(document.createElement('i')).addClass('bi-globe2').appendTo(item.flex.action.unpublish)
        item.flex.action.unpublish.click(function(){
          unpublishFile(item)
        })
        item.flex.action.trash = $(document.createElement('button')).addClass('btn btn-sm btn-danger rounded-end d-none').appendTo(item.flex.action)
        item.flex.action.trash.icon = $(document.createElement('i')).addClass('bi-trash').appendTo(item.flex.action.trash)
        item.flex.action.trash.click(function(){
          deleteFile(item)
        })
        item.flex.action.restore = $(document.createElement('button')).addClass('btn btn-sm btn-info rounded d-none').appendTo(item.flex.action)
        item.flex.action.restore.icon = $(document.createElement('i')).addClass('bi-arrow-counterclockwise').appendTo(item.flex.action.restore)
        item.flex.action.restore.click(function(){
          restoreFile(item)
        })
        item.hover(function(){
          item.addClass("text-bg-primary")
        }, function(){
          item.removeClass("text-bg-primary")
        });
        item.flex.icon.click(function(){
          File.download(file.id)
        })
        item.flex.name.click(function(){
          File.download(file.id)
        })
        item.flex.size.click(function(){
          File.download(file.id)
        })
        if(file.isDeleted){
          item.appendTo(listTrash)
          item.flex.action.restore.removeClass('d-none')
        } else {
          item.appendTo(listFiles)
          if(file.isPublic){
            item.flex.action.unpublish.removeClass('d-none')
          } else {
            item.flex.action.publish.removeClass('d-none')
          }
          item.flex.action.trash.removeClass('d-none')
        }
      }
      countFiles.html(listFiles.find('li').length)
      countTrash.html(listTrash.find('li').length)
    }
    const setDataset = function(data){
      const list = dataset.find('.list-group')
      for(const [datakey, values] of Object.entries(data)){
        let item = $(document.createElement('li')).addClass('list-group-item user-select-none').appendTo(list)
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
        trash.removeClass('d-none')
      }
    })
    API.get("topic/get/?id=<?= $id ?>",{
      success:function(result,status,xhr){
        if(typeof result[0] !== "undefined"){
          topic.removeClass('d-none')
          const topicData = result[0]
          console.log(topicData)
          note.attr('data-linkTo','topics:'+topicData.id).click(function(){
            addNote($(this),topicData)
          })
          comment.attr('data-linkTo','topics:'+topicData.id).click(function(){
            addComment($(this),topicData)
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
            addEmlObject(eml,topicData)
          }
          for(let [commentID, comment] of Object.entries(topicData.comments)){
            addCommentObject(comment,topicData)
          }
          for(let [noteID, note] of Object.entries(topicData.notes)){
            addNoteObject(note,topicData)
          }
          timeline.timeline.sort()
          Auth.isAuthorized('topic/details/trashed',function(token){
            if(token){
              timeline.find('[data-isDelected]').removeClass('d-none')
            }
          })
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
