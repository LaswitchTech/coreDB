<div class="row m-0 h-100 px-3">
  <div class="col-12 p-2">
    <div id="topicsList">
    </div>
  </div>
</div>
<script>
  $(document).ready(function(){
    const topicsList = $('#topicsList')
    const topicsListTable = Table.create({
      card:{title:"Topics",icon:"chat-dots"},
      showButtonsLabel: false,
      selectTools:true,
      buttons:[
        {
          extend: 'selected',
          text: '<i class="bi-intersect"></i>',
          action:function(e, dt, node, config){},
        }
      ],
      actions:{
        details:{
          label:"Details",
          icon:"eye",
          action:function(event, table, node, row, data){
            window.location.href = window.location.origin+'/topics/details?id='+data.id;
          },
        },
        comment:{
          label:"Comment",
          icon:"chat-left-quote",
          action:function(event, table, node, row, data){},
        },
        note:{
          label:"Note",
          icon:"sticky",
          action:function(event, table, node, row, data){},
        },
      },
      columnDefs:[
        { target: 0, visible: true, width: '100px', responsivePriority: 1, title: "ID", name: "id", data: "id" },
        { target: 1, visible: true, width: '100px', responsivePriority: 2, title: "Status", name: "status", data: "status", render: function(data,type,row,meta){
          let color = '', icon = '', text = ''
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
          return '<span class="badge bg-'+color+'"><i class="bi-'+icon+' me-2"></i>'+text+'</span>'
        } },
        { target: 2, visible: true, responsivePriority: 1000, title: "Meta", name: "meta", data: "meta", render: function(data,type,row,meta){
          let html = ''
          for(const [category, tags] of Object.entries(data)){
            for(const [key, tag] of Object.entries(tags)){
              if(tag !== ''){
                html += '<span class="badge bg-primary mx-1"><i class="bi-tag me-2"></i>'+category+': '+tag+'</span>'
              }
            }
          }
          return html
        } },
        { target: 3, visible: false, responsivePriority: 1000, title: "Dataset", name: "dataset", data: "dataset", render: function(data,type,row,meta){
          let html = ''
          for(const [category, tags] of Object.entries(data)){
            for(const [key, tag] of Object.entries(tags)){
              if(tag !== ''){
                html += '<span class="badge bg-primary mx-1"><i class="bi-tag me-2"></i>'+category+': '+tag+'</span>'
              }
            }
          }
          return html
        } },
        { target: 4, visible: false, responsivePriority: 3, title: "Contacts", name: "contacts", data: "contacts", render: function(data,type,row,meta){
          let html = ''
          for(const [key, contact] of Object.entries(data)){
            html += '<span class="badge bg-primary mx-1"><i class="bi-at me-2"></i>'+contact+'</span>'
          }
          return html
        } },
      ],
      dblclick: function(event, table, node, data){
        window.location.href = window.location.origin+'/topics/details?id='+data.id
      },
    },function(table){
      let url = 'topic/list'
      <?php
        switch($this->getRoute()){
          case "/topics/open":
            echo "url += '?status=2';" . PHP_EOL;
            break;
          default:
            echo "url += '?status=4';" . PHP_EOL;
            break;
        }
      ?>
      API.get(url,{success:function(result,status,xhr){
        for(const [key, topic] of Object.entries(result)){
          table.add(topic)
        }
      }})
    }).appendTo(topicsList).init()
  })
</script>
