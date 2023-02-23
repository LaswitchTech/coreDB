<div class="row m-0 px-3 gap-4">
  <div class="col-12" id="welcome"></div>
  <script>
    $(document).ready(function(){
      const welcome = $('#welcome')
      var welcomeCard = Card.create({
        icon: 'signpost-split',
        title: 'Welcome',
        body: '<p><strong>Welcome to coreDB</strong></p><p>You will find demos of every coreDB\'s Components, Utilities, Elements and Themes here.</p>',
        hideFooter: true,
      }).appendTo(welcome)
    })
  </script>
  <div class="col-12" id="components"></div>
  <script>
    $(document).ready(function(){
      const components = $('#components')
      var componentsCard = Card.create({
        icon: 'window-split',
        title: 'Components',
        hideFooter: true,
  			close:true,
  			collapsed: false,
  			collapse: true,
  			fullscreen: true,
      },function(card){

        // Row
        card.body.row = $(document.createElement('div')).addClass('row row-cols-2 d-flex align-items-stretch mt-3').appendTo(card.body)

        // Gravatar
        let gravatarContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        gravatarContainer.card = Card.create({
          icon: 'person-square',
          title: 'Gravatar',
          hideFooter: true,
          strech:true,
          classBody: 'justify-content-center align-items-center',
        },function(card){
          card.body.gravatar = $(document.createElement('img')).addClass('img-circle rounded-circle shadow-sm img-bordered-sm').attr('alt','Gravatar').attr('src',Gravatar.url('<?= $this->Auth->getUser("username") ?>')).appendTo(card.body)
          console.log(Gravatar.url('Gravatar','<?= $this->Auth->getUser("username") ?>'))
        }).appendTo(gravatarContainer)

        // Dropdown
        let dropdownContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        dropdownContainer.card = Card.create({
          icon: 'menu-down',
          title: 'Dropdown',
          hideFooter: true,
          strech:true,
          classBody: 'justify-content-center align-items-center',
        },function(card){
          card.body.dropdown = Dropdown.create({
            lorem: {
              label: 'Lorem Ipsum',
              icon: 'exclamation-circle',
              bgColor: 'warning',
              textColor: 'info',
              visible: function(){
                return true
              },
              action: function(btn,dropdown){
                console.log(btn,dropdown)
              },
            },
          }, function(dropdown){
            console.log('Dropdown',dropdown)
          }).appendTo(card.body)
        }).appendTo(dropdownContainer)

        // Card
        let cardContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        cardContainer.card = Card.create({
          icon: 'card-heading',
          title: 'Card',
          hideFooter: true,
          strech:true,
          classBody: 'justify-content-center align-items-center',
        },function(card){
          card.body.card = Card.create({
            icon: 'circle',
            title: 'Title',
            body: 'Lorem ipsum represents a long-held tradition for designers, typographers and the like. Some people hate it and argue for its demise, but others ignore the hate as they create awesome tools to help create filler text for everyone from bacon lovers to Charlie Sheen fans.',
            hideFooter: false,
            close:true,
            collapsed: false,
            collapse: true,
            fullscreen: true,
            classCard: 'w-100',
          }, function(card){
            card.collapse.addClass('w-100')
            console.log('Card',card)
          }).appendTo(card.body)
        }).appendTo(cardContainer)

        // Modal
        let modalContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        modalContainer.card = Card.create({
          icon: 'window-stack',
          title: 'Modal',
          hideFooter: true,
          strech:true,
          classBody: 'justify-content-center align-items-center',
        },function(card){
          card.body.btn = $(document.createElement('button')).addClass('btn btn-light shadow border').html('Launch Modal').appendTo(card.body)
          card.body.btn.icon = $(document.createElement('i')).addClass('bi-rocket me-1').prependTo(card.body.btn)
          card.body.btn.click(function(){
            card.body.modal = Modal.create({
              title: 'Modal',
              icon: 'window-stack',
              color: 'primary',
            },function(modal){
              console.log('Modal',modal)
  						modal.footer.group.primary.click(function(){
                alert('Hello!')
  						})
  					})
          })
        }).appendTo(modalContainer)

        // Code
        let codeContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        codeContainer.card = Card.create({
          icon: 'code-slash',
          title: 'Code',
          hideFooter: true,
          classBody: 'justify-content-center align-items-center',
        },function(card){
          card.body.code = Code.create({
            language: 'php',
            clipboard:true,
            fullscreen:true,
            code:'echo "Hello Wolrd!";',
          }, function(code){
            code.addClass('w-100')
            console.log('Code',code)
          }).appendTo(card.body)
        }).appendTo(codeContainer)

        // Toast
        let toastContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        toastContainer.card = Card.create({
          icon: 'exclamation-triangle',
          title: 'Toast',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-center align-items-center',
        },function(card){
          card.body.btn = $(document.createElement('button')).addClass('btn btn-light shadow border').html('Launch Toast').appendTo(card.body)
          card.body.btn.icon = $(document.createElement('i')).addClass('bi-rocket me-1').prependTo(card.body.btn)
          card.body.btn.click(function(){
            card.body.toast = Toast.create({
              title: 'Lorem Ipsum',
              body: 'Lorem ipsum represents a long-held tradition for designers, typographers and the like. Some people hate it and argue for its demise, but others ignore the hate as they create awesome tools to help create filler text for everyone from bacon lovers to Charlie Sheen fans.',
              icon: 'exclamation-triangle',
              color: 'primary',
              close: true,
            },function(toast){
              console.log('Toast',toast)
            })
          })
        }).appendTo(toastContainer)

        // Feed
        let feedContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        feedContainer.card = Card.create({
          icon: 'chat-square-text',
          title: 'Feed',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-center align-items-center',
        },function(card){
        }).appendTo(feedContainer)

        // Timeline
        let timelineContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        timelineContainer.card = Card.create({
          icon: 'clock-history',
          title: 'Timeline',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-center align-items-center',
        },function(card){
        }).appendTo(timelineContainer)

        // Table
        let tableContainer = $(document.createElement('div')).addClass('col-12 mb-3').appendTo(card.body.row)
        tableContainer.card = Card.create({
          icon: 'table',
          title: 'Table',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-center align-items-center',
        },function(card){
        }).appendTo(tableContainer)
      }).appendTo(components)
    })
  </script>
  <div class="col-12" id="utilities"></div>
  <script>
    $(document).ready(function(){
      const utilities = $('#utilities')
      var utilitiesCard = Card.create({
        icon: 'tools',
        title: 'Utilities',
        hideFooter: true,
  			close:true,
  			collapsed: false,
  			collapse: true,
  			fullscreen: true,
      },function(card){

        // Row
        card.body.row = $(document.createElement('div')).addClass('row row-cols-2 d-flex align-items-stretch mt-3').appendTo(card.body)

        // Clock
        let clockContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        clockContainer.card = Card.create({
          icon: 'clock',
          title: 'Clock',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-center align-items-center',
        },function(card){
        }).appendTo(clockContainer)

        // API
        let apiContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        apiContainer.card = Card.create({
          icon: 'braces-asterisk',
          title: 'API',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-center align-items-center',
        },function(card){
        }).appendTo(apiContainer)

        // Auth
        let authContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        authContainer.card = Card.create({
          icon: 'shield-shaded',
          title: 'Auth',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-center align-items-center',
        },function(card){
        }).appendTo(authContainer)

        // Cookie
        let cookieContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        cookieContainer.card = Card.create({
          icon: 'database-exclamation',
          title: 'Cookie',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-center align-items-center',
        },function(card){
        }).appendTo(cookieContainer)

        // SystemStatus
        let statusContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        statusContainer.card = Card.create({
          icon: 'patch-question',
          title: 'SystemStatus',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-center align-items-center',
        },function(card){
        }).appendTo(statusContainer)
      }).appendTo(utilities)
    })
  </script>
  <div class="col-12" id="elements"></div>
  <script>
    $(document).ready(function(){
      const elements = $('#elements')
      var elementsCard = Card.create({
        icon: 'window-desktop',
        title: 'Elements',
        hideFooter: true,
  			close:true,
  			collapsed: false,
  			collapse: true,
  			fullscreen: true,
      },function(card){

        // Row
        card.body.row = $(document.createElement('div')).addClass('row row-cols-2 d-flex align-items-stretch mt-3').appendTo(card.body)

        // Notification
        let notificationContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        notificationContainer.card = Card.create({
          icon: 'bell',
          title: 'Notification',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-center align-items-center',
        },function(card){
        }).appendTo(notificationContainer)

        // Activity
        let activityContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        activityContainer.card = Card.create({
          icon: 'activity',
          title: 'Activity',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-center align-items-center',
        },function(card){
        }).appendTo(activityContainer)

        // Dashboard
        let dashboardContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        dashboardContainer.card = Card.create({
          icon: 'speedometer2',
          title: 'Dashboard',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-center align-items-center',
        },function(card){
        }).appendTo(dashboardContainer)

        // File
        let fileContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        fileContainer.card = Card.create({
          icon: 'file-earmark',
          title: 'File',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-center align-items-center',
        },function(card){
        }).appendTo(fileContainer)

        // Notes
        let noteContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        noteContainer.card = Card.create({
          icon: 'sticky',
          title: 'Note',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-center align-items-center',
        },function(card){
        }).appendTo(noteContainer)

        // Comments
        let commentContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        commentContainer.card = Card.create({
          icon: 'chat-text',
          title: 'Comment',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-center align-items-center',
        },function(card){
        }).appendTo(commentContainer)
      }).appendTo(elements)
    })
  </script>
  <div class="col-12" id="themes"></div>
  <script>
    $(document).ready(function(){
      const themes = $('#themes')
      var themesCard = Card.create({
        icon: 'palette',
        title: 'Themes',
        hideFooter: true,
  			close:true,
  			collapsed: false,
  			collapse: true,
  			fullscreen: true,
      },function(card){

        // Row
        card.body.row = $(document.createElement('div')).addClass('row row-cols-2 d-flex align-items-stretch mt-3').appendTo(card.body)

        // Style
        let styleContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        styleContainer.card = Card.create({
          icon: 'filetype-css',
          title: 'Style',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-start flex-column',
        },function(card){
          card.body.description = $(document.createElement('p')).appendTo(card.body)
          card.body.description.html('This constant contains all css styles of the body. Have a look at the console using <kbd>F12</kbd>.')
          let text = 'const Style = getComputedStyle(document.body);' + "\r\n"
          card.body.code = Code.create({
            language: 'javascript',
            clipboard:true,
            fullscreen:true,
            code:text,
          }, function(code){
            code.addClass('w-100')
            console.log('Style',Style)
          }).appendTo(card.body)
        }).appendTo(styleContainer)

        // Theme
        let themeContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        themeContainer.card = Card.create({
          icon: 'palette2',
          title: 'Theme',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-start flex-column',
        },function(card){
          card.body.description = $(document.createElement('p')).appendTo(card.body)
          card.body.description.html('This constant contains all bootstrap colors. Have a look at the console using <kbd>F12</kbd>.')
          let text = ''
          text += 'const Theme = {' + "\r\n"
          text += '  primary: Style.getPropertyValue("--bs-primary"),' + "\r\n"
          text += '  secondary: Style.getPropertyValue("--bs-secondary"),' + "\r\n"
          text += '  success: Style.getPropertyValue("--bs-success"),' + "\r\n"
          text += '  info: Style.getPropertyValue("--bs-info"),' + "\r\n"
          text += '  warning: Style.getPropertyValue("--bs-warning"),' + "\r\n"
          text += '  danger: Style.getPropertyValue("--bs-danger"),' + "\r\n"
          text += '  light: Style.getPropertyValue("--bs-light"),' + "\r\n"
          text += '  dark: Style.getPropertyValue("--bs-dark"),' + "\r\n"
          text += '}' + "\r\n"
          card.body.code = Code.create({
            language: 'javascript',
            clipboard:true,
            fullscreen:true,
            code:text,
          }, function(code){
            code.addClass('w-100')
            console.log('Theme',Theme)
          }).appendTo(card.body)
        }).appendTo(themeContainer)
      }).appendTo(themes)
    })
  </script>
</div>

<!--
<var>y</var> = <var>m</var><var>x</var> + <var>b</var>
To switch directories, type <kbd>cd</kbd> followed by the name of the directory.<br>
To edit settings, press <kbd><kbd>Ctrl</kbd> + <kbd>,</kbd></kbd>
<samp>This text is meant to be treated as sample output from a computer program.</samp> -->


<div id="commentsContainer" class="card shadow m-4">
  <div class="card-header">
    <h5 class="card-title d-flex align-items-center my-2 fw-light" style="line-height:32px;"><i class="bi-chat-text me-2"></i>Comments</h5>
  </div>
  <div class="card-body">
    <ul class="comments list-group rounded-0 mb-3" style="">
      <li class="list-group-item border-0 p-0 d-flex mt-3">
        <div class="flex-shrink avatar-block px-3">
          <img class="img-circle rounded-circle shadow-sm img-bordered-sm" width="65" alt="Avatar" src="https://www.gravatar.com/avatar/e05b4330e145079f1d73aa859b23ab86?d=mp">
        </div>
        <div class="flex-fill">
          <div class="card shadow">
            <div class="card-body">
              <h5 class="username user-select-none m-0">
                <a class="text-decoration-none link-dark" href="/users/details?id=louis@laswitchtech.com">louis@laswitchtech.com</a>
              </h5>
              <p class="timeago user-select-none text-secondary" title="2/20/2023, 1:53:15 PM" data-bs-placement="top" style="font-size: 13px;">
                <i class="bi-clock me-1"></i><time datetime="2/20/2023, 1:53:15 PM" title="2/20/2023, 1:53:15 PM">15 minutes ago</time>
              </p>
              <p class="content comment">
                Lorem ipsum represents a long-held tradition for designers, typographers and the like. Some people hate it and argue for its demise, but others ignore the hate as they create awesome tools to help create filler text for everyone from bacon lovers to Charlie Sheen fans.
              </p>
              <p class="controls user-select-none m-0 text-secondary">
                <a class="link-secondary text-decoration-none text-sm cursor-pointer me-2"><i class="bi-hand-thumbs-up me-1"></i>Like</a>
                <a class="link-secondary text-decoration-none text-sm cursor-pointer me-2"><i class="bi-share me-1"></i>Share</a>
                <a class="link-secondary text-decoration-none text-sm cursor-pointer me-2"><i class="bi-sticky me-1"></i>Note</a>
              </p>
            </div>
          </div>
        </div>
      </li>
      <li class="list-group-item border-0 p-0 d-flex mt-3">
        <div class="flex-shrink avatar-block px-3">
          <img class="img-circle rounded-circle shadow-sm img-bordered-sm" width="65" alt="Avatar" src="https://www.gravatar.com/avatar/e05b4330e145079f1d73aa859b23ab86?d=mp">
        </div>
        <div class="flex-fill">
          <div class="card shadow">
            <div class="card-body">
              <h5 class="username user-select-none m-0">
                <a class="text-decoration-none link-dark" href="/users/details?id=louis@laswitchtech.com">louis@laswitchtech.com</a>
              </h5>
              <p class="timeago user-select-none text-secondary" title="2/20/2023, 1:53:15 PM" data-bs-placement="top" style="font-size: 13px;">
                <i class="bi-clock me-1"></i><time datetime="2/20/2023, 1:53:15 PM" title="2/20/2023, 1:53:15 PM">15 minutes ago</time>
              </p>
              <p class="content comment">
                Lorem ipsum represents a long-held tradition for designers, typographers and the like. Some people hate it and argue for its demise, but others ignore the hate as they create awesome tools to help create filler text for everyone from bacon lovers to Charlie Sheen fans.
              </p>
              <p class="controls user-select-none m-0 text-secondary">
                <a class="link-secondary text-decoration-none text-sm cursor-pointer me-2"><i class="bi-hand-thumbs-up me-1"></i>Like</a>
                <a class="link-secondary text-decoration-none text-sm cursor-pointer me-2"><i class="bi-share me-1"></i>Share</a>
                <a class="link-secondary text-decoration-none text-sm cursor-pointer me-2"><i class="bi-sticky me-1"></i>Note</a>
              </p>
            </div>
          </div>
        </div>
      </li>
    </ul>
  </div>
</div>
<div id="feedContainer" class="card shadow m-4">
  <div class="card-header">
    <h5 class="card-title d-flex align-items-center my-2 fw-light" style="line-height:32px;"><i class="bi-chat-text me-2"></i>Feed</h5>
  </div>
  <div class="card-body"></div>
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
