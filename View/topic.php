<?php
$id = "";
if(isset($_GET['id'])){ $id = $_GET['id']; }
?>
<div class="row m-0 h-100" id="topic">
  <div class="col-sm-6 col-lg-4 p-2 ps-4">
    <div class="card shadow">
      <div class="card-header">
        <h5 class="card-title my-2 fw-light"><i class="bi-chat-text me-2"></i>Details</h5>
      </div>
      <div class="card-body p-0">
        <div class="d-flex align-items-center flex-column rounded-bottom bg-image text-light py-4">
          <i class="bi-chat" style="font-size:120px"></i>
          <h5 class="fw-light mt-2"><strong>ID: </strong><?= $id ?></h5>
        </div>
        <div class="d-flex justify-content-around py-3">
          <button class="btn btn-light shadow" type="button" aria-controls="offcanvasActivity"><i class="bi-activity me-2"></i>Activity</button>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-8 p-2 pe-4">
    <div class="card shadow">
      <div class="card-header">
        <h5 class="card-title my-2 fw-light"><i class="bi-clock me-2"></i>Timeline</h5>
      </div>
      <div class="card-body p-0">
        <div id="timeline" class="p-3"></div>
      </div>
    </div>
  </div>
</div>
<script>
  $(document).ready(function(){
    const topic = $('#topic')
    let timeline = $('#timeline')
    timeline.timeline = Timeline.create()
    timeline.timeline.appendTo(timeline)
    API.get("topic/get/?id=<?= $id ?>",{success:function(result,status,xhr){
      if(typeof result[0] !== "undefined"){
        const topicData = result[0]
        // console.log(topicData)
        topic.find('button[aria-controls="offcanvasActivity"]').click(function(){
          Activity.show("topics_topics",topicData.id)
        })
        for(const [id, eml] of Object.entries(topicData.emls)){
          console.log(eml)
          if(timeline.timeline.find('[data-id="'+id+'"]').length <= 0){
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
      				object.attr('data-id',id)
              object.icon.addClass('cursor-pointer').attr('data-bs-toggle','collapse').attr('data-bs-target','#eml'+id).attr('aria-expanded',false)
              object.item.header.addClass('cursor-pointer').attr('data-bs-toggle','collapse').attr('data-bs-target','#eml'+id).attr('aria-expanded',false)
              object.item.body.attr('id','eml'+id).addClass('collapse p-0')
              object.item.body.contacts = $(document.createElement('div')).addClass('border-bottom p-3').appendTo(object.item.body)
              for(const [key, contact] of Object.entries(eml.contacts)){
                $(document.createElement('span')).addClass('badge bg-primary me-2').html(contact).appendTo(object.item.body.contacts)
              }
              object.item.body.content = $(document.createElement('div')).addClass('p-3 border-bottom').html(eml.body_stripped.replaceAll('<div><br></div>', '').replaceAll('<div></div>', '').replaceAll('\r\n', '<br>')).appendTo(object.item.body)
              object.item.body.files = $(document.createElement('div')).addClass('border-bottom p-3 d-flex flex-wrap')
              if(eml.files.length > 0){
                object.item.body.files.appendTo(object.item.body)
              }
              for(const [key, file] of Object.entries(eml.files)){
                let fileElement = $(document.createElement('div')).addClass('card mx-1 cursor-pointer shadow').css('max-width','175px').appendTo(object.item.body.files)
                fileElement.data = topicData.files[file]
                fileElement.body = $(document.createElement('div')).addClass('card-body text-center py-3 px-5')
                fileElement.icon = $(document.createElement('i')).css('font-size','54px').appendTo(fileElement.body)
                switch(fileElement.data.type){
                  case "pdf":
                    fileElement.icon.addClass('bi-file-earmark-pdf')
                    fileElement.body.appendTo(fileElement)
                  default:
                    break;
                }
                fileElement.footer = $(document.createElement('div')).addClass('card-footer').appendTo(fileElement)
                fileElement.footer.name = $(document.createElement('div')).addClass('fw-bold text-break').html(fileElement.data.filename).appendTo(fileElement.footer)
                fileElement.footer.size = $(document.createElement('div')).addClass('text-muted').html(formatBytes(fileElement.data.size)).appendTo(fileElement.footer)
                // <div class="card" style="width: 18rem;">
                //   <img src="..." class="card-img-top" alt="...">
                //   <div class="card-footer">
                //     Card footer
                //   </div>
                // </div>
              }
              object.item.footer.addClass('d-flex justify-content-between align-items-center')
              object.item.footer.sender = $(document.createElement('div')).html('Sender:').appendTo(object.item.footer)
              object.item.footer.sender.badge = $(document.createElement('span')).addClass('badge bg-primary ms-2').html(eml.sender).appendTo(object.item.footer.sender)
              object.item.footer.controls = $(document.createElement('div')).addClass('btn-group').appendTo(object.item.footer)
              object.item.footer.controls.reply = $(document.createElement('button')).addClass('btn btn-light border shadow').html('Reply').appendTo(object.item.footer.controls)
              object.item.footer.controls.reply.icon = $(document.createElement('i')).addClass('bi-reply me-1').prependTo(object.item.footer.controls.reply)
              object.attr('data-search',object.text().toString().toUpperCase())
              object.collapse = new bootstrap.Collapse(object.item.body,{toggle: false})
              object.item.body.on('show.bs.collapse',function(){
                object.icon.removeClass('bi-envelope').addClass('bi-envelope-open')
                object.item.header.addClass('shadow-sm')
                if(eml.isRead > 0){}
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
                object.icon.removeClass('bi-envelope-open').addClass('bi-envelope')
              })
              if(eml.isRead <= 0){
                object.duration = 2000
                object.item.effect("highlight",{color:Theme.primary,duration:object.duration},function(){})
                object.animate = setInterval(function(){
                  object.item.effect("highlight",{color:Theme.primary,duration:object.duration},function(){})
                }, object.duration)
              }
      			})
          }
        }
      }
    }})
  })
</script>
