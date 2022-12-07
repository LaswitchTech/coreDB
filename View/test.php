<div id="Container" class="p-4"></div>
<script>
  $(document).ready(function(){
    const Container = $('#Container')
    Table.create({card:{title:"Test",icon:"bug"},DataTable:{pagingType:'first_last_numbers'}},function(table){
      console.log(table)
    }).appendTo(Container).init()
  })
</script>
