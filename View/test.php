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
<!-- <div id="feedContainer" class="card shadow m-4">
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
</script> -->
<div id="timelineContainer" class="p-4"></div>
<script>
  $(document).ready(function(){
    const timelineContainer = $('#timelineContainer')
    timelineContainer.timeline = Timeline.create({
      class: {
        timeline: null,
        item: 'text-bg-light',
        icon: null,
        header: null,
        body: null,
        footer: null,
      },
    },function(timeline){
      console.log('Timeline', timeline)
      timeline.object({
        icon: 'circle',
  			color: 'secondary',
  			type: null,
  			datetime: Date.parse(new Date()),
  			header: null,
  			body: 'Lorem ipsum represents a long-held tradition for designers, typographers and the like. Some people hate it and argue for its demise, but others ignore the hate as they create awesome tools to help create filler text for everyone from bacon lovers to Charlie Sheen fans.',
  			footer: null,
  			order: null,
  			label: true,
  			id:null,
      },function(object){
        console.log('Object', object)
      })
    }).appendTo(timelineContainer)
  })
</script>
