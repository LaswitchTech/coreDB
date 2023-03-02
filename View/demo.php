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
          text += '// ' + Gravatar.url('<?= $this->Auth->getUser("username") ?>')
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
          text += '})'
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
          text += "})"
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
          text += "})"
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
          strech:true,
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
          text += "})"
          card.body.code = Code.create({
            language: 'javascript',
            clipboard:true,
            fullscreen:true,
            code:text,
          }).appendTo(card.body)
        }).appendTo(codeContainer)

        // Timeline
        let timelineContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        timelineContainer.card = Card.create({
          icon: 'clock-history',
          title: 'Timeline',
          hideFooter: true,
          strech: true,
          classBody: 'flex-column justify-content-start',
        },function(card){
          card.body.description = $(document.createElement('p')).appendTo(card.body)
          card.body.description.html('This component generates timelines. Have a look at the console using <kbd>F12</kbd>.')
          card.body.container = $(document.createElement('div')).appendTo(card.body)
          card.body.timeline = Timeline.create({
            class: {
              timeline: null,
              item: null,
              icon: null,
              header: null,
              body: null,
              footer: null,
            },
            order: 'ASC',
          },function(timeline){
            console.log('Timeline', timeline)
            // Insert filter controls
            timeline.filters.appendTo(card.body.container)
            // Insert an object on the timeline
            timeline.object({
        			 id: null,
        			 datetime: '2023-03-02 08:00:00',
        			 order: null,
               icon: 'circle',
        			 color: 'secondary',
        			 type: 'demo',
        			 label: true,
        			 header: null,
        			 body: 'Lorem ipsum represents a long-held tradition for designers, typographers and the like. Some people hate it and argue for its demise, but others ignore the hate as they create awesome tools to help create filler text for everyone from bacon lovers to Charlie Sheen fans.',
        			 footer: null,
            },function(object){
              console.log('Object', object)
            })
          }).appendTo(card.body.container)
          let text = ''
          text += 'Timeline.create({' + "\r\n"
          text += '  class: {' + "\r\n"
          text += '    timeline: null,' + "\r\n"
          text += '    item: null,' + "\r\n"
          text += '    icon: null,' + "\r\n"
          text += '    header: null,' + "\r\n"
          text += '    body: null,' + "\r\n"
          text += '    footer: null,' + "\r\n"
          text += '  },' + "\r\n"
          text += '  order: "ASC",' + "\r\n"
          text += '},function(timeline){' + "\r\n"
          text += '  console.log("Timeline", timeline)' + "\r\n"
          text += '  // Insert filter controls' + "\r\n"
          text += '  timeline.filters.appendTo(card.body)' + "\r\n"
          text += '  // Insert an object on the timeline' + "\r\n"
          text += '  timeline.object({' + "\r\n"
        	text += '    id: null,' + "\r\n"
        	text += '    datetime: "2023-03-02 08:00:00",' + "\r\n"
        	text += '    order: null,' + "\r\n"
          text += '    icon: "circle",' + "\r\n"
        	text += '    color: "secondary",' + "\r\n"
        	text += '    type: "demo",' + "\r\n"
        	text += '    label: true,' + "\r\n"
        	text += '    header: null,' + "\r\n"
        	text += '    body: "Lorem ipsum represents a long-held tradition for designers, typographers and the like. Some people hate it and argue for its demise, but others ignore the hate as they create awesome tools to help create filler text for everyone from bacon lovers to Charlie Sheen fans.",' + "\r\n"
        	text += '    footer: null,' + "\r\n"
          text += '  },function(object){' + "\r\n"
          text += '    console.log("Object", object)' + "\r\n"
          text += '  })' + "\r\n"
          text += '})'
          card.body.code = Code.create({
            language: 'javascript',
            clipboard:true,
            fullscreen:true,
            code:text,
          }).appendTo(card.body)
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
          card.body.description = $(document.createElement('p')).appendTo(card.body)
          card.body.description.html('This component generates tables. Have a look at the console using <kbd>F12</kbd>.')
          const records = [
            {name:'Airi Satou',position:'Accountant',office:'Tokyo',age:'33'},
            {name:'Angelica Ramos',position:'Chief Executive Officer (CEO)',office:'London',age:'47'},
            {name:'Ashton Cox',position:'Junior Technical Author',office:'San Francisco',age:'66'},
            {name:'Bradley Greer',position:'Software Engineer',office:'London',age:'41'},
            {name:'Brenden Wagner',position:'Software Engineer',office:'San Francisco',age:'28'},
            {name:'Brielle Williamson',position:'Integration Specialist',office:'New York',age:'61'},
            {name:'Bruno Nash',position:'Software Engineer',office:'London',age:'38'},
            {name:'Caesar Vance',position:'Pre-Sales Support',office:'New York',age:'21'},
            {name:'Cara Stevens',position:'Sales Assistant',office:'New York',age:'46'},
            {name:'Cedric Kelly',position:'Senior Javascript Developer',office:'Edinburgh',age:'22'},
          ]
          card.body.table = Table.create({
            card:{title:"Demo",icon:"easel2"},
            actions:{
              remove:{
                label:"Remove",
                icon:"trash",
                action:function(event, table, node, row, data){
                  table.delete(row)
                },
              },
            },
            columnDefs:[
              { target: 0, visible: true, responsivePriority: 1, title: "Name", name: "name", data: "name" },
              { target: 1, visible: true, responsivePriority: 1000, title: "Position", name: "position", data: "position" },
              { target: 2, visible: true, responsivePriority: 1000, title: "Office", name: "office", data: "office" },
              { target: 3, visible: true, responsivePriority: 2, title: "Age", name: "age", data: "age" },
            ],
            buttons:[
              {
        				extend: 'collection',
        				text: '<i class="bi-plus-lg me-2"></i>Add',
        				action:function(e, dt, node, config){
        					console.log(e, dt, node, config)
                  dt.row.add(records[randomNumber(0,9)]).draw()
        				},
        			}
            ],
          },function(table){
            table.card.addClass('mb-3')
            for(const [key, record] of Object.entries(records)){
              table.add(record)
            }
            console.log('Table', table)
          }).appendTo(card.body).init()
          let text = ''
          text += "Table.create({" + "\r\n"
          text += "  card:{title:'Demo',icon:'easel2'}," + "\r\n"
          text += "  actions:{" + "\r\n"
          text += "    remove:{" + "\r\n"
          text += "      label:'Remove'," + "\r\n"
          text += "      icon:'trash'," + "\r\n"
          text += "      action:function(event, table, node, row, data){" + "\r\n"
          text += "        table.delete(row)" + "\r\n"
          text += "      }," + "\r\n"
          text += "    }," + "\r\n"
          text += "  }," + "\r\n"
          text += "  columnDefs:[" + "\r\n"
          text += "    { target: 0, visible: true, responsivePriority: 1, title: 'Name', name: 'name', data: 'name' }," + "\r\n"
          text += "    { target: 1, visible: true, responsivePriority: 1000, title: 'Position', name: 'position', data: 'position' }," + "\r\n"
          text += "    { target: 2, visible: true, responsivePriority: 1000, title: 'Office', name: 'office', data: 'office' }," + "\r\n"
          text += "    { target: 3, visible: true, responsivePriority: 2, title: 'Age', name: 'age', data: 'age' }," + "\r\n"
          text += "  ]," + "\r\n"
          text += "  buttons:[" + "\r\n"
          text += "    {" + "\r\n"
          text += "      extend: 'collection'," + "\r\n"
          text += "      text: '<i class=\"bi-plus-lg me-2\"></i>Add'," + "\r\n"
          text += "      action:function(e, dt, node, config){" + "\r\n"
          text += "        dt.row.add(records[randomNumber(0,9)]).draw()" + "\r\n"
          text += "      }," + "\r\n"
          text += "    }" + "\r\n"
          text += "  ]," + "\r\n"
          text += "},function(table){" + "\r\n"
          text += "  console.log('Table', table)" + "\r\n"
          text += "}).init()"
          card.body.code = Code.create({
            language: 'javascript',
            clipboard:true,
            fullscreen:true,
            code:text,
          }).appendTo(card.body)
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
          card.body.description = $(document.createElement('p')).appendTo(card.body)
          card.body.description.html('This utility allow you to control the application clock. Have a look at the console using <kbd>F12</kbd>.')
          let text = ''
          text += "// Get Clock status" + "\r\n"
          text += "Clock.status()" + "\r\n" + "\r\n"
          text += "// Start Clock" + "\r\n"
          text += "Clock.start()" + "\r\n" + "\r\n"
          text += "// Stop Clock" + "\r\n"
          text += "Clock.stop()" + "\r\n" + "\r\n"
          text += "// Execute a Clock cycle" + "\r\n"
          text += "Clock.exec()" + "\r\n" + "\r\n"
          text += "// Clear all callbacks from the Clock" + "\r\n"
          text += "Clock.clear()" + "\r\n" + "\r\n"
          text += "// Add a callback to the Clock" + "\r\n"
          text += "Clock.add(function(){" + "\r\n"
          text += "  // Code" + "\r\n"
          text += "})"
          card.body.code = Code.create({
            language: 'javascript',
            clipboard:true,
            fullscreen:true,
            code:text,
          }).appendTo(card.body)
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
          card.body.description = $(document.createElement('p')).appendTo(card.body)
          card.body.description.html('This utility provides an easy way to exchange data with the application API. Have a look at the console using <kbd>F12</kbd>.')
          let text = ''
          text += "// setDefaults allows you to configure the API callbacks (beforeSend,complete,error,success)" + "\r\n"
          text += "API.setDefaults({" + "\r\n"
          text += "  beforeSend:function(xhr){}," + "\r\n"
          text += "  complete:function(xhr,status){}," + "\r\n"
          text += "  error:function(xhr,status,error){}," + "\r\n"
          text += "  success:function(result,status,xhr){}," + "\r\n"
          text += "})" + "\r\n" + "\r\n"
          text += "// setAuth allows you to change the API Authentication Method. Default is SESSION based" + "\r\n"
          text += "API.setAuth(type (bearer,basic), username (token,username), password (null,password))" + "\r\n" + "\r\n"
          text += "// get allows you to perform a GET request to the Application API" + "\r\n"
          text += "API.get(url, data, config ({" + "\r\n"
          text += "  beforeSend:function(xhr){}," + "\r\n"
          text += "  complete:function(xhr,status){}," + "\r\n"
          text += "  error:function(xhr,status,error){}," + "\r\n"
          text += "  success:function(result,status,xhr){}," + "\r\n"
          text += "}))" + "\r\n" + "\r\n"
          text += "// post allows you to perform a POST request to the Application API" + "\r\n"
          text += "API.post(url (string), data (object), config ({" + "\r\n"
          text += "  beforeSend:function(xhr){}," + "\r\n"
          text += "  complete:function(xhr,status){}," + "\r\n"
          text += "  error:function(xhr,status,error){}," + "\r\n"
          text += "  success:function(result,status,xhr){}," + "\r\n"
          text += "}))"
          card.body.code = Code.create({
            language: 'javascript',
            clipboard:true,
            fullscreen:true,
            code:text,
          }).appendTo(card.body)
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
          card.body.description = $(document.createElement('p')).appendTo(card.body)
          card.body.description.html('This utility allows you to retrieve an authorization. Have a look at the console using <kbd>F12</kbd>.')
          let text = ''
          text += "// isAuthorized allows you to request an authorization" + "\r\n"
          text += "Auth.isAuthorized(name (string), level (int, 0), callback (function, null))"
          card.body.code = Code.create({
            language: 'javascript',
            clipboard:true,
            fullscreen:true,
            code:text,
          }).appendTo(card.body)
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
          card.body.description = $(document.createElement('p')).appendTo(card.body)
          card.body.description.html('This utility provides a CRUD to Cookies. Have a look at the console using <kbd>F12</kbd>.')
          let text = ''
          text += "// Create a Cookie" + "\r\n"
          text += "Cookie.create(name, value, days = 30)" + "\r\n" + "\r\n"
          text += "// Read a Cookie" + "\r\n"
          text += "Cookie.read(name)" + "\r\n" + "\r\n"
          text += "// Update a Cookie" + "\r\n"
          text += "Cookie.update(name, value, days = 30)" + "\r\n" + "\r\n"
          text += "// Delete a Cookie" + "\r\n"
          text += "Cookie.delete(name)"
          card.body.code = Code.create({
            language: 'javascript',
            clipboard:true,
            fullscreen:true,
            code:text,
          }).appendTo(card.body)
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
          card.body.description = $(document.createElement('p')).appendTo(card.body)
          card.body.description.html('This component generates dropdown menus. Have a look at the console using <kbd>F12</kbd>.')
          let text = ''
          text += "// status Allows you to access a status" + "\r\n"
          text += "// auth contains the Authentication status" + "\r\n"
          text += "// debug contains the Debug status" + "\r\n"
          text += "// maintenance contains the Maintenance status" + "\r\n"
          text += "// user contains the User's status" + "\r\n"
          text += "SystemStatus.status(name (auth,debug,maintenance,user,null))"
          card.body.code = Code.create({
            language: 'javascript',
            clipboard:true,
            fullscreen:true,
            code:text,
          }).appendTo(card.body)
        }).appendTo(statusContainer)

        // Search
        let searchContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        searchContainer.card = Card.create({
          icon: 'search',
          title: 'Search',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-start flex-column',
        },function(card){
          card.body.description = $(document.createElement('p')).appendTo(card.body)
          card.body.description.html('This utility allow you to add search support to any object. Have a look at the console using <kbd>F12</kbd>.')
          let text = ''
          text += "// Add the fonctionnality to your object container" + "\r\n"
          text += "Search.add(container)" + "\r\n"
          text += "// Configure object as searchable" + "\r\n"
          text += "Search.set(object)"
          card.body.code = Code.create({
            language: 'javascript',
            clipboard:true,
            fullscreen:true,
            code:text,
          }).appendTo(card.body)
        }).appendTo(searchContainer)
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
          text += "})"
          card.body.code = Code.create({
            language: 'javascript',
            clipboard:true,
            fullscreen:true,
            code:text,
          }).appendTo(card.body)
        }).appendTo(toastContainer)

        // Activity
        let activityContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        activityContainer.card = Card.create({
          icon: 'activity',
          title: 'Activity',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-start flex-column',
        },function(card){
          card.body.description = $(document.createElement('p')).appendTo(card.body)
          card.body.description.html('This elements provides support for activities on any object. Have a look at the console using <kbd>F12</kbd>.')
          let text = ''
          text += "// show Opens an offcanvas on the side of the screen with a timeline of activities of an object" + "\r\n"
          text += "Activity.show(type (null), id (null))"
          card.body.code = Code.create({
            language: 'javascript',
            clipboard:true,
            fullscreen:true,
            code:text,
          }).appendTo(card.body)
        }).appendTo(activityContainer)

        // File
        let fileContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        fileContainer.card = Card.create({
          icon: 'file-earmark',
          title: 'File',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-start flex-column',
        },function(card){
          card.body.description = $(document.createElement('p')).appendTo(card.body)
          card.body.description.html('This element provides some functionnalities to handle files. Have a look at the console using <kbd>F12</kbd>.')
          let text = ''
          text += "// upload Opens an upload form to upload files in the application" + "\r\n"
          text += "File.upload(beforeSend (function(object)), success (function(object)))" + "\r\n" + "\r\n"
          text += "// preview Opens a preview window for a file" + "\r\n"
          text += "File.preview(id (int))" + "\r\n" + "\r\n"
          text += "// download Triggers a download for a file" + "\r\n"
          text += "File.download(id (int))" + "\r\n" + "\r\n"
          text += "// Extras" + "\r\n"
          text += "// formatBytes Allows you to format a number of bytes to a human readable size" + "\r\n"
          text += "File.formatBytes(bytes (int))" + "\r\n" + "\r\n"
          text += "// base64toSimple Allows you to convert a base64 value to a string" + "\r\n"
          text += "File.base64toSimple(data)" + "\r\n" + "\r\n"
          text += "// base64toBlob Allows you to convert a base64 value to a blob" + "\r\n"
          text += "File.base64toBlob(data,contentType)"
          card.body.code = Code.create({
            language: 'javascript',
            clipboard:true,
            fullscreen:true,
            code:text,
          }).appendTo(card.body)
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
            linkTo: {pages:'demo'},
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
          text += "})"
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
          text += "})"
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

        // Feed
        let feedContainer = $(document.createElement('div')).addClass('col mb-3').appendTo(card.body.row)
        feedContainer.card = Card.create({
          icon: 'chat-square-text',
          title: 'Feed',
          hideFooter: true,
          strech: true,
          classBody: 'justify-content-start flex-column',
        },function(card){
          card.body.description = $(document.createElement('p')).appendTo(card.body)
          card.body.description.html('This component generates feeds. Have a look at the console using <kbd>F12</kbd>.')
          card.body.feed = Feed.create({
            linkTo: {pages:'demo'},
            note: true,
            share: true,
            like: true,
            edit: true,
            comment: true,
          },function(feed){
            feed.addClass('mb-3')
            console.log('Feed', feed)
          }).appendTo(card.body)
          let text = ''
          text += "Feed.create({" + "\r\n"
          text += "  linkTo: {pages:'demo'}," + "\r\n"
          text += "  note: true," + "\r\n"
          text += "  share: true," + "\r\n"
          text += "  like: true," + "\r\n"
          text += "  edit: true," + "\r\n"
          text += "  comment: true," + "\r\n"
          text += "},function(feed){" + "\r\n"
          text += "  console.log('Feed', feed)" + "\r\n"
          text += "})"
          card.body.code = Code.create({
            language: 'javascript',
            clipboard:true,
            fullscreen:true,
            code:text,
          }).appendTo(card.body)
        }).appendTo(feedContainer)
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
          let text = 'const Style = getComputedStyle(document.body);'
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
          text += '}'
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
