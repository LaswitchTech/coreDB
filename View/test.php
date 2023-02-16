<div id="feedContainer" class="card shadow m-4">
  <div class="card-header">
    <h5 class="card-title d-flex align-items-center my-2 fw-light" style="line-height:32px;"><i class="bi-chat-text me-2"></i>Feed</h5>
  </div>
  <div class="card-body">
    <!-- <div class="feed">
      <div class="post">
        <div class="user-block">
          <img class="img-circle img-bordered-sm" src="img/logo.png" alt="user image">
          <span class="username">
            <a href="#" class="text-decoration-none">Jonathan Burke Jr.</a>
            <a href="#" class="float-end link-secondary btn-tool"><i class="bi-x-lg"></i></a>
          </span>
          <span class="description">Shared publicly - 7:30 PM today</span>
        </div>
        <p class="content">
          Lorem ipsum represents a long-held tradition for designers,
          typographers and the like. Some people hate it and argue for
          its demise, but others ignore the hate as they create awesome
          tools to help create filler text for everyone from bacon lovers
          to Charlie Sheen fans.
        </p>
        <p class="controls">
          <a href="#" class="link-secondary text-decoration-none text-sm me-2"><i class="bi-share me-1"></i>Share</a>
          <a href="#" class="link-secondary text-decoration-none text-sm me-2"><i class="bi-sticky me-1"></i>Note</a>
          <a href="#" class="link-secondary text-decoration-none text-sm"><i class="bi-hand-thumbs-up me-1"></i>Like</a>
          <span class="float-end">
            <a href="#" class="link-secondary text-decoration-none text-sm me-2">
              <i class="bi-person-vcard me-1"></i>Contacts<span class="ms-1">(5)</span>
            </a>
            <a href="#" class="link-secondary text-decoration-none text-sm me-2">
              <i class="bi-file-earmark me-1"></i>Files<span class="ms-1">(5)</span>
            </a>
            <a href="#" class="link-secondary text-decoration-none text-sm" data-bs-toggle="collapse" data-bs-target="#comments">
              <i class="bi-chat-text me-1"></i>Comments<span class="ms-1">(5)</span>
            </a>
          </span>
        </p>
        <ul class="comments collapse list-group rounded-0 mb-3" id="comments">
          <li class="list-group-item">
            <div class="user-block">
              <img class="img-circle img-bordered-sm" src="img/logo.png" alt="user image">
              <span class="username">
                <a href="#" class="text-decoration-none">Jonathan Burke Jr.</a>
              </span>
              <span class="description">Shared publicly - 7:30 PM today</span>
            </div>
            <p class="comment">
              Lorem ipsum represents a long-held tradition for designers,
              typographers and the like. Some people hate it and argue for
              its demise, but others ignore the hate as they create awesome
              tools to help create filler text for everyone from bacon lovers
              to Charlie Sheen fans.
            </p>
            <p class="controls">
              <a href="#" class="link-secondary text-decoration-none text-sm me-2"><i class="bi-share me-1"></i>Share</a>
              <a href="#" class="link-secondary text-decoration-none text-sm me-2"><i class="bi-sticky-fill text-warning me-1"></i>Note</a>
            </p>
          </li>
          <li class="list-group-item">
            <div class="user-block">
              <img class="img-circle img-bordered-sm" src="img/logo.png" alt="user image">
              <span class="username">
                <a href="#" class="text-decoration-none">Jonathan Burke Jr.</a>
              </span>
              <span class="description">Shared publicly - 7:30 PM today</span>
            </div>
            <p class="comment">
              Lorem ipsum represents a long-held tradition for designers,
              typographers and the like. Some people hate it and argue for
              its demise, but others ignore the hate as they create awesome
              tools to help create filler text for everyone from bacon lovers
              to Charlie Sheen fans.
            </p>
            <p class="controls">
              <a href="#" class="link-secondary text-decoration-none text-sm me-2"><i class="bi-share me-1"></i>Share</a>
              <a href="#" class="link-secondary text-decoration-none text-sm me-2"><i class="bi-sticky me-1"></i>Note</a>
            </p>
          </li>
        </ul>
        <input class="form-control form-control-sm" type="text" placeholder="Type a comment">
      </div>
    </div> -->
  </div>
</div>
<script>
  $(document).ready(function(){
    const feedContainer = $('#feedContainer')
    const feed = Feed.create({},function(feed){
      console.log(feed)
      feed.post({
        id:1,
        username: 'louis@laswitchtech.com',
        content: 'Lorem ipsum represents a long-held tradition for designers, typographers and the like. Some people hate it and argue for its demise, but others ignore the hate as they create awesome tools to help create filler text for everyone from bacon lovers to Charlie Sheen fans.',
      },function(post){
        console.log(post)
      })
      feed.post({
        id:2,
        username: 'louis@albcie.com',
        content: 'Lorem ipsum represents a long-held tradition for designers, typographers and the like. Some people hate it and argue for its demise, but others ignore the hate as they create awesome tools to help create filler text for everyone from bacon lovers to Charlie Sheen fans.',
      },function(post){
        console.log(post)
      })
      feed.post({
        id:3,
        username: 'louis_ouellet@hotmail.com',
        content: 'Lorem ipsum represents a long-held tradition for designers, typographers and the like. Some people hate it and argue for its demise, but others ignore the hate as they create awesome tools to help create filler text for everyone from bacon lovers to Charlie Sheen fans.',
      },function(post){
        console.log(post)
        post.comment({
          id:1,
          username: 'louis@laswitchtech.com',
          content: 'Lorem ipsum represents a long-held tradition for designers, typographers and the like. Some people hate it and argue for its demise, but others ignore the hate as they create awesome tools to help create filler text for everyone from bacon lovers to Charlie Sheen fans.',
        },function(comment){
          console.log(comment)
        })
        post.comment({
          id:2,
          username: 'louis@albcie.com',
          content: 'Lorem ipsum represents a long-held tradition for designers, typographers and the like. Some people hate it and argue for its demise, but others ignore the hate as they create awesome tools to help create filler text for everyone from bacon lovers to Charlie Sheen fans.',
        },function(comment){
          console.log(comment)
        })
        post.comment({
          id:3,
          username: 'louis_ouellet@hotmail.com',
          content: 'Lorem ipsum represents a long-held tradition for designers, typographers and the like. Some people hate it and argue for its demise, but others ignore the hate as they create awesome tools to help create filler text for everyone from bacon lovers to Charlie Sheen fans.',
        },function(comment){
          console.log(comment)
        })
      })
    }).appendTo(feedContainer.find('.card-body'))
  })
</script>
<div id="selectContainer" class="card shadow m-4">
  <select>
    <option></option>
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
    <option value="5">5</option>
  </select>
</div>
<script>
  $(document).ready(function(){
    $('#selectContainer select').select2({
      placeholder: "Please select a country"
    })
  })
</script>
<div id="datatableContainer" class="p-4"></div>
<script>
  $(document).ready(function(){
    const datatableContainer = $('#datatableContainer')
    const record = {
      permission:'dashboard',
      column1:'Lorem Ipsum',
      column2:'Lorem Ipsum',
      column3:'Lorem Ipsum',
      column4:'Lorem Ipsum',
      column5:'Lorem Ipsum',
      column6:'Lorem Ipsum',
      column7:'Lorem Ipsum',
      column8:'Lorem Ipsum',
      column9:'Lorem Ipsum',
      column10:'Lorem Ipsum',
      column11:'Lorem Ipsum',
      column12:'Lorem Ipsum',
      level:1,
    }
    Table.create({
      card:{title:"Test",icon:"bug"},
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
      // DataTable:{pagingType:'first_last_numbers'},
      columnDefs:[
        { target: 0, visible: true, responsivePriority: 1, title: "Permission", name: "permission", data: "permission" },
        { target: 1, visible: true, responsivePriority: 1000, title: "Column1", name: "column1", data: "column1" },
        { target: 2, visible: true, responsivePriority: 1000, title: "Column2", name: "column2", data: "column2" },
        { target: 3, visible: true, responsivePriority: 1000, title: "Column3", name: "column3", data: "column3" },
        { target: 4, visible: true, responsivePriority: 1000, title: "Column4", name: "column4", data: "column4" },
        { target: 5, visible: true, responsivePriority: 1000, title: "Column5", name: "column5", data: "column5" },
        { target: 6, visible: true, responsivePriority: 1000, title: "Column6", name: "column6", data: "column6" },
        { target: 7, visible: true, responsivePriority: 1000, title: "Column7", name: "column7", data: "column7" },
        { target: 8, visible: true, responsivePriority: 1000, title: "Column8", name: "column8", data: "column8" },
        { target: 9, visible: true, responsivePriority: 1000, title: "Column9", name: "column9", data: "column9" },
        { target: 10, visible: true, responsivePriority: 1000, title: "Column10", name: "column10", data: "column10" },
        { target: 11, visible: true, responsivePriority: 1000, title: "Column11", name: "column11", data: "column11" },
        { target: 12, visible: true, responsivePriority: 1000, title: "Column12", name: "column12", data: "column12" },
        { target: 13, visible: true, responsivePriority: 2, title: "Level", name: "level", data: "level" },
      ],
      buttons:[
        {
  				extend: 'collection',
  				text: '<i class="bi-plus-lg me-2"></i>Add',
  				action:function(e, dt, node, config){
  					console.log(e, dt, node, config)
            // table.add(record)
            dt.row.add(record).draw()
  				},
  			}
      ],
    },function(table){
      table.add(record)
      console.log(table)
    }).appendTo(datatableContainer).init()
  })
</script>
