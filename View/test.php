<!-- <div id="commentsContainer" class="card shadow m-4">
  <div class="card-header">
    <h5 class="card-title d-flex align-items-center my-2 fw-light" style="line-height:32px;"><i class="bi-chat-text me-2"></i>Comments</h5>
  </div>
  <div class="card-body"></div>
</div>
<script>
  $(document).ready(function(){
    const commentsContainer = $('#commentsContainer')
    commentsContainer.comments = Comments.create({
      id: null,
			linkTo: {pages:'test'},
      note: true,
      share: true,
      like: true,
      edit: true,
      delete: true,
    },function(comments){
      console.log('Comments',comments)
      comments.button.appendTo(commentsContainer.find('.card-body'))
    }).appendTo(commentsContainer.find('.card-body'))
  })
</script> -->
<div id="feedContainer" class="card shadow m-4">
  <div class="card-header">
    <h5 class="card-title d-flex align-items-center my-2 fw-light" style="line-height:32px;"><i class="bi-chat-text me-2"></i>Feed</h5>
  </div>
  <div class="card-body"></div>
</div>
<script>
  $(document).ready(function(){
    const feedContainer = $('#feedContainer')
    const feed = Feed.create({
      linkTo: {pages:'test'},
      note: true,
      share: true,
      like: true,
      edit: true,
      comment: true,
    },function(feed){
      // console.log(feed)
      feed.post({
        id:1,
        created: '2023-02-26 11:46:00',
        owner: {users:'louis@laswitchtech.com'},
        likes: [],
        content: 'PHA+PHNwYW4gc3R5bGU9ImNvbG9yOiByZ2IoMTA4LCAxMTcsIDEyNSk7IGZvbnQtZmFtaWx5OiBzeXN0ZW0tdWksIC1hcHBsZS1zeXN0ZW0sICZxdW90O1NlZ29lIFVJJnF1b3Q7LCBSb2JvdG8sICZxdW90O0hlbHZldGljYSBOZXVlJnF1b3Q7LCAmcXVvdDtOb3RvIFNhbnMmcXVvdDssICZxdW90O0xpYmVyYXRpb24gU2FucyZxdW90OywgQXJpYWwsIHNhbnMtc2VyaWYsICZxdW90O0FwcGxlIENvbG9yIEVtb2ppJnF1b3Q7LCAmcXVvdDtTZWdvZSBVSSBFbW9qaSZxdW90OywgJnF1b3Q7U2Vnb2UgVUkgU3ltYm9sJnF1b3Q7LCAmcXVvdDtOb3RvIENvbG9yIEVtb2ppJnF1b3Q7OyBmb250LXNpemU6IDE2cHg7Ij5Mb3JlbSBpcHN1bSByZXByZXNlbnRzIGEgbG9uZy1oZWxkIHRyYWRpdGlvbiBmb3IgZGVzaWduZXJzLCB0eXBvZ3JhcGhlcnMgYW5kIHRoZSBsaWtlLiBTb21lIHBlb3BsZSBoYXRlIGl0IGFuZCBhcmd1ZSBmb3IgaXRzIGRlbWlzZSwgYnV0IG90aGVycyBpZ25vcmUgdGhlIGhhdGUgYXMgdGhleSBjcmVhdGUgYXdlc29tZSB0b29scyB0byBoZWxwIGNyZWF0ZSBmaWxsZXIgdGV4dCBmb3IgZXZlcnlvbmUgZnJvbSBiYWNvbiBsb3ZlcnMgdG8gQ2hhcmxpZSBTaGVlbiBmYW5zLjwvc3Bhbj48YnI+PC9wPg==',
      },function(post){
        // console.log(post)
      })
      feed.post({
        id:3,
        created: '2023-02-28 11:46:00',
        owner: {users:'louis_ouellet@hotmail.com'},
        likes: [],
        content: 'PHA+PHNwYW4gc3R5bGU9ImNvbG9yOiByZ2IoMTA4LCAxMTcsIDEyNSk7IGZvbnQtZmFtaWx5OiBzeXN0ZW0tdWksIC1hcHBsZS1zeXN0ZW0sICZxdW90O1NlZ29lIFVJJnF1b3Q7LCBSb2JvdG8sICZxdW90O0hlbHZldGljYSBOZXVlJnF1b3Q7LCAmcXVvdDtOb3RvIFNhbnMmcXVvdDssICZxdW90O0xpYmVyYXRpb24gU2FucyZxdW90OywgQXJpYWwsIHNhbnMtc2VyaWYsICZxdW90O0FwcGxlIENvbG9yIEVtb2ppJnF1b3Q7LCAmcXVvdDtTZWdvZSBVSSBFbW9qaSZxdW90OywgJnF1b3Q7U2Vnb2UgVUkgU3ltYm9sJnF1b3Q7LCAmcXVvdDtOb3RvIENvbG9yIEVtb2ppJnF1b3Q7OyBmb250LXNpemU6IDE2cHg7Ij5Mb3JlbSBpcHN1bSByZXByZXNlbnRzIGEgbG9uZy1oZWxkIHRyYWRpdGlvbiBmb3IgZGVzaWduZXJzLCB0eXBvZ3JhcGhlcnMgYW5kIHRoZSBsaWtlLiBTb21lIHBlb3BsZSBoYXRlIGl0IGFuZCBhcmd1ZSBmb3IgaXRzIGRlbWlzZSwgYnV0IG90aGVycyBpZ25vcmUgdGhlIGhhdGUgYXMgdGhleSBjcmVhdGUgYXdlc29tZSB0b29scyB0byBoZWxwIGNyZWF0ZSBmaWxsZXIgdGV4dCBmb3IgZXZlcnlvbmUgZnJvbSBiYWNvbiBsb3ZlcnMgdG8gQ2hhcmxpZSBTaGVlbiBmYW5zLjwvc3Bhbj48YnI+PC9wPg==',
      },function(post){
        // console.log(post)
      })
      feed.post({
        id:2,
        created: '2023-02-27 11:46:00',
        owner: {users:'louis@albcie.com'},
        likes: [],
        content: 'PHA+PHNwYW4gc3R5bGU9ImNvbG9yOiByZ2IoMTA4LCAxMTcsIDEyNSk7IGZvbnQtZmFtaWx5OiBzeXN0ZW0tdWksIC1hcHBsZS1zeXN0ZW0sICZxdW90O1NlZ29lIFVJJnF1b3Q7LCBSb2JvdG8sICZxdW90O0hlbHZldGljYSBOZXVlJnF1b3Q7LCAmcXVvdDtOb3RvIFNhbnMmcXVvdDssICZxdW90O0xpYmVyYXRpb24gU2FucyZxdW90OywgQXJpYWwsIHNhbnMtc2VyaWYsICZxdW90O0FwcGxlIENvbG9yIEVtb2ppJnF1b3Q7LCAmcXVvdDtTZWdvZSBVSSBFbW9qaSZxdW90OywgJnF1b3Q7U2Vnb2UgVUkgU3ltYm9sJnF1b3Q7LCAmcXVvdDtOb3RvIENvbG9yIEVtb2ppJnF1b3Q7OyBmb250LXNpemU6IDE2cHg7Ij5Mb3JlbSBpcHN1bSByZXByZXNlbnRzIGEgbG9uZy1oZWxkIHRyYWRpdGlvbiBmb3IgZGVzaWduZXJzLCB0eXBvZ3JhcGhlcnMgYW5kIHRoZSBsaWtlLiBTb21lIHBlb3BsZSBoYXRlIGl0IGFuZCBhcmd1ZSBmb3IgaXRzIGRlbWlzZSwgYnV0IG90aGVycyBpZ25vcmUgdGhlIGhhdGUgYXMgdGhleSBjcmVhdGUgYXdlc29tZSB0b29scyB0byBoZWxwIGNyZWF0ZSBmaWxsZXIgdGV4dCBmb3IgZXZlcnlvbmUgZnJvbSBiYWNvbiBsb3ZlcnMgdG8gQ2hhcmxpZSBTaGVlbiBmYW5zLjwvc3Bhbj48YnI+PC9wPg==',
      },function(post){
        // console.log(post)
      })
    }).appendTo(feedContainer.find('.card-body'))
  })
</script>
<!-- <div id="datatableContainer" class="p-4"></div>
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
</script> -->
