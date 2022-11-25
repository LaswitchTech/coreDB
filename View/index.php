<div id="dashboard"></div>
<script>
  $(document).ready(function(){
    Dashboard.init("<?= $this->Auth->getUser('organization') ?>")
  })
</script>
