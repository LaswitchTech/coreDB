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
          classBody: 'justify-content-start flex-column',
        },function(card){
          card.body.description = $(document.createElement('p')).appendTo(card.body)
          card.body.description.html('This component generates gravatar urls for images. Have a look at the console using <kbd>F12</kbd>.')
          card.body.gravatar = $(document.createElement('img')).addClass('img-circle rounded-circle shadow-sm img-bordered-sm mb-3 mx-auto').attr('height',256).attr('width',256).attr('alt','Gravatar').attr('src',Gravatar.url('<?= $this->Auth->getUser("username") ?>',{size:256})).appendTo(card.body)
          let text = ''
          text += 'Gravatar.url("<?= $this->Auth->getUser("username") ?>")' + "\r\n"
          text += '// ' + Gravatar.url('<?= $this->Auth->getUser("username") ?>') + "\r\n"
          card.body.code = Code.create({
            language: 'javascript',
            clipboard:true,
            fullscreen:true,
            code:text,
          }, function(code){
            console.log('Gravatar',Gravatar.url('<?= $this->Auth->getUser("username") ?>'))
          }).appendTo(card.body)
        }).appendTo(gravatarContainer)

        // Dropdown
        let dropdownContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        dropdownContainer.card = Card.create({
          icon: 'menu-down',
          title: 'Dropdown',
          hideFooter: true,
          strech:true,
          classBody: 'justify-content-start flex-column',
        },function(card){
          card.body.description = $(document.createElement('p')).appendTo(card.body)
          card.body.description.html('This component generates dropdown menus. Have a look at the console using <kbd>F12</kbd>.')
          card.body.dropdown = Dropdown.create({
            lorem: {
              label: "Lorem Ipsum",
              icon: "exclamation-circle",
              bgColor: "warning",
              textColor: "info",
              visible: function(){
                return true
              },
              action: function(btn,dropdown){
                console.log(btn,dropdown)
              },
            },
          }, function(dropdown){
            dropdown.addClass('mx-auto mb-3 border rounded shadow')
            console.log('Dropdown',dropdown)
          }).appendTo(card.body)
          let text = ''
          text += 'Dropdown.create({' + "\r\n"
          text += '  lorem: {' + "\r\n"
          text += '    label: "Lorem Ipsum",' + "\r\n"
          text += '    icon: "exclamation-circle",' + "\r\n"
          text += '    bgColor: "warning",' + "\r\n"
          text += '    textColor: "info",' + "\r\n"
          text += '    visible: function(){' + "\r\n"
          text += '      return true' + "\r\n"
          text += '    },' + "\r\n"
          text += '    action: function(btn,dropdown){' + "\r\n"
          text += '      console.log(btn,dropdown)' + "\r\n"
          text += '    },' + "\r\n"
          text += '  },' + "\r\n"
          text += '}, function(dropdown){' + "\r\n"
          text += '  dropdown.addClass("mx-auto")' + "\r\n"
          text += '})' + "\r\n"
          card.body.code = Code.create({
            language: 'javascript',
            clipboard:true,
            fullscreen:true,
            code:text,
          }).appendTo(card.body)
        }).appendTo(dropdownContainer)

        // Card
        let cardContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        cardContainer.card = Card.create({
          icon: 'card-heading',
          title: 'Card',
          hideFooter: true,
          strech:true,
          classBody: 'justify-content-start flex-column',
        },function(card){
          card.body.description = $(document.createElement('p')).appendTo(card.body)
          card.body.description.html('This component generates cards. Have a look at the console using <kbd>F12</kbd>.')
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
            card.collapse.addClass('w-100 mx-auto mb-3')
            console.log('Card',card)
          }).appendTo(card.body)
          let text = ''
          text += "Card.create({" + "\r\n"
          text += "  icon: 'circle'," + "\r\n"
          text += "  title: 'Title'," + "\r\n"
          text += "  body: 'Lorem ipsum represents a long-held tradition for designers, typographers and the like. Some people hate it and argue for its demise, but others ignore the hate as they create awesome tools to help create filler text for everyone from bacon lovers to Charlie Sheen fans.'," + "\r\n"
          text += "  hideFooter: false," + "\r\n"
          text += "  close:true," + "\r\n"
          text += "  collapsed: false," + "\r\n"
          text += "  collapse: true," + "\r\n"
          text += "  fullscreen: true," + "\r\n"
          text += "  classCard: 'w-100'," + "\r\n"
          text += "}, function(card){" + "\r\n"
          text += "  console.log('Card',card)" + "\r\n"
          text += "})" + "\r\n"
          card.body.code = Code.create({
            language: 'javascript',
            clipboard:true,
            fullscreen:true,
            code:text,
          }).appendTo(card.body)
        }).appendTo(cardContainer)

        // Modal
        let modalContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        modalContainer.card = Card.create({
          icon: 'window-stack',
          title: 'Modal',
          hideFooter: true,
          strech:true,
          classBody: 'justify-content-start flex-column',
        },function(card){
          card.body.description = $(document.createElement('p')).appendTo(card.body)
          card.body.description.html('This component generates modals. Have a look at the console using <kbd>F12</kbd>.')
          card.body.btn = $(document.createElement('button')).addClass('btn btn-light shadow border mx-auto mb-3').html('Launch Modal').appendTo(card.body)
          card.body.btn.icon = $(document.createElement('i')).addClass('bi-rocket me-1').prependTo(card.body.btn)
          card.body.btn.click(function(){
            card.body.modal = Modal.create({
              title: 'Modal',
              body: 'Lorem ipsum represents a long-held tradition for designers, typographers and the like. Some people hate it and argue for its demise, but others ignore the hate as they create awesome tools to help create filler text for everyone from bacon lovers to Charlie Sheen fans.',
              icon: 'window-stack',
              color: 'primary',
            },function(modal){
              console.log('Modal',modal)
  						modal.footer.group.primary.click(function(){
                alert('Hello!')
  						})
  					})
          })
          let text = ''
          text += "Modal.create({" + "\r\n"
          text += "  title: 'Modal'," + "\r\n"
          text += "  body: 'Lorem ipsum represents a long-held tradition for designers, typographers and the like. Some people hate it and argue for its demise, but others ignore the hate as they create awesome tools to help create filler text for everyone from bacon lovers to Charlie Sheen fans.'," + "\r\n"
          text += "  icon: 'window-stack'," + "\r\n"
          text += "  color: 'primary'," + "\r\n"
          text += "},function(modal){" + "\r\n"
          text += "  modal.footer.group.primary.click(function(){" + "\r\n"
          text += "    alert('Hello!')" + "\r\n"
          text += "  })" + "\r\n"
          text += "})" + "\r\n"
          card.body.code = Code.create({
            language: 'javascript',
            clipboard:true,
            fullscreen:true,
            code:text,
          }).appendTo(card.body)
        }).appendTo(modalContainer)

        // Code
        let codeContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        codeContainer.card = Card.create({
          icon: 'code-slash',
          title: 'Code',
          hideFooter: true,
          classBody: 'justify-content-start flex-column',
        },function(card){
          card.body.description = $(document.createElement('p')).appendTo(card.body)
          card.body.description.html('This component generates code blocks. Have a look at the console using <kbd>F12</kbd>.')
          card.body.code = Code.create({
            language: 'php',
            clipboard:true,
            fullscreen:true,
            code:"echo 'Hello Wolrd!';",
          }, function(code){
            code.addClass('mb-3')
            console.log('Code',code)
          }).appendTo(card.body)
          let text = ''
          text += "Code.create({" + "\r\n"
          text += "  language: 'php'," + "\r\n"
          text += "  clipboard:true," + "\r\n"
          text += "  fullscreen:true," + "\r\n"
          text += "  code:'echo 'Hello Wolrd!';'," + "\r\n"
          text += "}, function(code){" + "\r\n"
          text += "  console.log('Code',code)" + "\r\n"
          text += "})" + "\r\n"
          card.body.code = Code.create({
            language: 'javascript',
            clipboard:true,
            fullscreen:true,
            code:text,
          }).appendTo(card.body)
        }).appendTo(codeContainer)

        // Feed
        let feedContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        feedContainer.card = Card.create({
          icon: 'chat-square-text',
          title: 'Feed',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-start flex-column',
        },function(card){
          // card.body.description = $(document.createElement('p')).appendTo(card.body)
          // card.body.description.html('This component generates dropdown menus. Have a look at the console using <kbd>F12</kbd>.')
          // let text = ''
          // text += "" + "\r\n"
          // text += "  " + "\r\n"
          // text += "    " + "\r\n"
          // card.body.code = Code.create({
          //   language: 'javascript',
          //   clipboard:true,
          //   fullscreen:true,
          //   code:text,
          // }).appendTo(card.body)
        }).appendTo(feedContainer)

        // Timeline
        let timelineContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        timelineContainer.card = Card.create({
          icon: 'clock-history',
          title: 'Timeline',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-start flex-column',
        },function(card){
          // card.body.description = $(document.createElement('p')).appendTo(card.body)
          // card.body.description.html('This component generates dropdown menus. Have a look at the console using <kbd>F12</kbd>.')
          // let text = ''
          // text += "" + "\r\n"
          // text += "  " + "\r\n"
          // text += "    " + "\r\n"
          // card.body.code = Code.create({
          //   language: 'javascript',
          //   clipboard:true,
          //   fullscreen:true,
          //   code:text,
          // }).appendTo(card.body)
        }).appendTo(timelineContainer)

        // Table
        let tableContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        tableContainer.card = Card.create({
          icon: 'table',
          title: 'Table',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-start flex-column',
        },function(card){
          // card.body.description = $(document.createElement('p')).appendTo(card.body)
          // card.body.description.html('This component generates dropdown menus. Have a look at the console using <kbd>F12</kbd>.')
          // let text = ''
          // text += "" + "\r\n"
          // text += "  " + "\r\n"
          // text += "    " + "\r\n"
          // card.body.code = Code.create({
          //   language: 'javascript',
          //   clipboard:true,
          //   fullscreen:true,
          //   code:text,
          // }).appendTo(card.body)
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
          classBody: 'justify-content-start flex-column',
        },function(card){
          // card.body.description = $(document.createElement('p')).appendTo(card.body)
          // card.body.description.html('This component generates dropdown menus. Have a look at the console using <kbd>F12</kbd>.')
          // let text = ''
          // text += "" + "\r\n"
          // text += "  " + "\r\n"
          // text += "    " + "\r\n"
          // card.body.code = Code.create({
          //   language: 'javascript',
          //   clipboard:true,
          //   fullscreen:true,
          //   code:text,
          // }).appendTo(card.body)
        }).appendTo(clockContainer)

        // API
        let apiContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        apiContainer.card = Card.create({
          icon: 'braces-asterisk',
          title: 'API',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-start flex-column',
        },function(card){
          // card.body.description = $(document.createElement('p')).appendTo(card.body)
          // card.body.description.html('This component generates dropdown menus. Have a look at the console using <kbd>F12</kbd>.')
          // let text = ''
          // text += "" + "\r\n"
          // text += "  " + "\r\n"
          // text += "    " + "\r\n"
          // card.body.code = Code.create({
          //   language: 'javascript',
          //   clipboard:true,
          //   fullscreen:true,
          //   code:text,
          // }).appendTo(card.body)
        }).appendTo(apiContainer)

        // Auth
        let authContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        authContainer.card = Card.create({
          icon: 'shield-shaded',
          title: 'Auth',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-start flex-column',
        },function(card){
          // card.body.description = $(document.createElement('p')).appendTo(card.body)
          // card.body.description.html('This component generates dropdown menus. Have a look at the console using <kbd>F12</kbd>.')
          // let text = ''
          // text += "" + "\r\n"
          // text += "  " + "\r\n"
          // text += "    " + "\r\n"
          // card.body.code = Code.create({
          //   language: 'javascript',
          //   clipboard:true,
          //   fullscreen:true,
          //   code:text,
          // }).appendTo(card.body)
        }).appendTo(authContainer)

        // Cookie
        let cookieContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        cookieContainer.card = Card.create({
          icon: 'database-exclamation',
          title: 'Cookie',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-start flex-column',
        },function(card){
          // card.body.description = $(document.createElement('p')).appendTo(card.body)
          // card.body.description.html('This component generates dropdown menus. Have a look at the console using <kbd>F12</kbd>.')
          // let text = ''
          // text += "" + "\r\n"
          // text += "  " + "\r\n"
          // text += "    " + "\r\n"
          // card.body.code = Code.create({
          //   language: 'javascript',
          //   clipboard:true,
          //   fullscreen:true,
          //   code:text,
          // }).appendTo(card.body)
        }).appendTo(cookieContainer)

        // SystemStatus
        let statusContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        statusContainer.card = Card.create({
          icon: 'patch-question',
          title: 'SystemStatus',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-start flex-column',
        },function(card){
          // card.body.description = $(document.createElement('p')).appendTo(card.body)
          // card.body.description.html('This component generates dropdown menus. Have a look at the console using <kbd>F12</kbd>.')
          // let text = ''
          // text += "" + "\r\n"
          // text += "  " + "\r\n"
          // text += "    " + "\r\n"
          // card.body.code = Code.create({
          //   language: 'javascript',
          //   clipboard:true,
          //   fullscreen:true,
          //   code:text,
          // }).appendTo(card.body)
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

        // Toast
        let toastContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        toastContainer.card = Card.create({
          icon: 'exclamation-triangle',
          title: 'Toast',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-start flex-column',
        },function(card){
          card.body.description = $(document.createElement('p')).appendTo(card.body)
          card.body.description.html('This element generates toasts. Have a look at the console using <kbd>F12</kbd>.')
          card.body.btn = $(document.createElement('button')).addClass('btn btn-light shadow border mx-auto mb-3').html('Launch Toast').appendTo(card.body)
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
          let text = ''
          text += "Toast.create({" + "\r\n"
          text += "  title: 'Lorem Ipsum'," + "\r\n"
          text += "  body: 'Lorem ipsum represents a long-held tradition for designers, typographers and the like. Some people hate it and argue for its demise, but others ignore the hate as they create awesome tools to help create filler text for everyone from bacon lovers to Charlie Sheen fans.'," + "\r\n"
          text += "  icon: 'exclamation-triangle'," + "\r\n"
          text += "  color: 'primary'," + "\r\n"
          text += "  close: true," + "\r\n"
          text += "},function(toast){" + "\r\n"
          text += "  console.log('Toast',toast)" + "\r\n"
          text += "})" + "\r\n"
          card.body.code = Code.create({
            language: 'javascript',
            clipboard:true,
            fullscreen:true,
            code:text,
          }).appendTo(card.body)
        }).appendTo(toastContainer)

        // Notification
        let notificationContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        notificationContainer.card = Card.create({
          icon: 'bell',
          title: 'Notification',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-start flex-column',
        },function(card){
          // card.body.description = $(document.createElement('p')).appendTo(card.body)
          // card.body.description.html('This constant contains all css styles of the body. Have a look at the console using <kbd>F12</kbd>.')
          // let text = ''
          // text += "" + "\r\n"
          // text += "  " + "\r\n"
          // text += "    " + "\r\n"
          // card.body.code = Code.create({
          //   language: 'javascript',
          //   clipboard:true,
          //   fullscreen:true,
          //   code:text,
          // }).appendTo(card.body)
        }).appendTo(notificationContainer)

        // Activity
        let activityContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        activityContainer.card = Card.create({
          icon: 'activity',
          title: 'Activity',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-start flex-column',
        },function(card){
          // card.body.description = $(document.createElement('p')).appendTo(card.body)
          // card.body.description.html('This component generates dropdown menus. Have a look at the console using <kbd>F12</kbd>.')
          // let text = ''
          // text += "" + "\r\n"
          // text += "  " + "\r\n"
          // text += "    " + "\r\n"
          // card.body.code = Code.create({
          //   language: 'javascript',
          //   clipboard:true,
          //   fullscreen:true,
          //   code:text,
          // }).appendTo(card.body)
        }).appendTo(activityContainer)

        // Dashboard
        let dashboardContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        dashboardContainer.card = Card.create({
          icon: 'speedometer2',
          title: 'Dashboard',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-start flex-column',
        },function(card){
          // card.body.description = $(document.createElement('p')).appendTo(card.body)
          // card.body.description.html('This component generates dropdown menus. Have a look at the console using <kbd>F12</kbd>.')
          // let text = ''
          // text += "" + "\r\n"
          // text += "  " + "\r\n"
          // text += "    " + "\r\n"
          // card.body.code = Code.create({
          //   language: 'javascript',
          //   clipboard:true,
          //   fullscreen:true,
          //   code:text,
          // }).appendTo(card.body)
        }).appendTo(dashboardContainer)

        // File
        let fileContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        fileContainer.card = Card.create({
          icon: 'file-earmark',
          title: 'File',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-start flex-column',
        },function(card){
          // card.body.description = $(document.createElement('p')).appendTo(card.body)
          // card.body.description.html('This component generates dropdown menus. Have a look at the console using <kbd>F12</kbd>.')
          // let text = ''
          // text += "" + "\r\n"
          // text += "  " + "\r\n"
          // text += "    " + "\r\n"
          // card.body.code = Code.create({
          //   language: 'javascript',
          //   clipboard:true,
          //   fullscreen:true,
          //   code:text,
          // }).appendTo(card.body)
        }).appendTo(fileContainer)

        // Notes
        let noteContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        noteContainer.card = Card.create({
          icon: 'sticky',
          title: 'Note',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-start flex-column',
        },function(card){
          card.body.description = $(document.createElement('p')).appendTo(card.body)
          card.body.description.html('This element allows you to add notes on objects. Have a look at the console using <kbd>F12</kbd>.')
          card.body.note = Note.create('link',{
      			color: 'secondary',
      			colored: 'true',
            addClass: 'mx-auto mb-3',
          },function(object){
            console.log('Note',object)
          }).appendTo(card.body)
          let text = ''
          text += "Note.create('link',{" + "\r\n"
          text += "  color: 'secondary'," + "\r\n"
          text += "  colored: true," + "\r\n"
          text += "},function(object){" + "\r\n"
          text += "  console.log('Note',object)" + "\r\n"
          text += "})" + "\r\n"
          card.body.code = Code.create({
            language: 'javascript',
            clipboard:true,
            fullscreen:true,
            code:text,
          }).appendTo(card.body)
        }).appendTo(noteContainer)

        // Comments
        let commentContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        commentContainer.card = Card.create({
          icon: 'chat-text',
          title: 'Comment',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-start flex-column',
        },function(card){
          card.body.description = $(document.createElement('p')).appendTo(card.body)
          card.body.description.html('This element allows you to add comments to any objects. Have a look at the console using <kbd>F12</kbd>.')
          card.body.button = $(document.createElement('p')).addClass('text-center').appendTo(card.body)
          card.body.comments = Comments.create({
      			linkTo: {pages:'demo'},
            note: true,
            share: true,
            like: true,
            edit: true,
          },function(comments){
            console.log('Comments',comments)
            // Insert comments button
            comments.button.appendTo(card.body.button)
            comments.addClass('mb-3')
          }).appendTo(card.body)
          let text = ''
          text += "Comments.create({" + "\r\n"
          text += "  linkTo: {pages:'demo'}," + "\r\n"
          text += "  note: true," + "\r\n"
          text += "  share: true," + "\r\n"
          text += "  like: true," + "\r\n"
          text += "  edit: true," + "\r\n"
          text += "},function(comments){" + "\r\n"
          text += "  console.log('Comments',comments)" + "\r\n"
          text += "  // Insert comments button" + "\r\n"
          text += "  comments.button.appendTo(card.body.button)" + "\r\n"
          text += "})" + "\r\n"
          card.body.code = Code.create({
            language: 'javascript',
            clipboard:true,
            fullscreen:true,
            code:text,
          }).appendTo(card.body)
        }).appendTo(commentContainer)

        // Share
        let shareContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        shareContainer.card = Card.create({
          icon: 'share',
          title: 'Share',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-start flex-column',
        },function(card){
          // card.body.description = $(document.createElement('p')).appendTo(card.body)
          // card.body.description.html('This component generates dropdown menus. Have a look at the console using <kbd>F12</kbd>.')
          // let text = ''
          // text += "" + "\r\n"
          // text += "  " + "\r\n"
          // text += "    " + "\r\n"
          // card.body.code = Code.create({
          //   language: 'javascript',
          //   clipboard:true,
          //   fullscreen:true,
          //   code:text,
          // }).appendTo(card.body)
        }).appendTo(shareContainer)
      }).appendTo(elements)
    })
  </script>
  <div class="col-12 mb-4" id="themes"></div>
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
            console.log('Theme',Theme)
          }).appendTo(card.body)
        }).appendTo(themeContainer)
      }).appendTo(themes)
    })
  </script>
</div>
