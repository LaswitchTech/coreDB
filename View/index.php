<div id="dashboard">
  <div class="row row-cols-4">
    <div class="col">
      <div data-widget="box-primary" class="py-2 bg-primary"></div>
      <div data-widget="box-primary" class="py-2 bg-primary"></div>
    </div>
    <div class="col">
      <div data-widget="box-secondary" class="py-2 bg-secondary"></div>
      <div data-widget="box-secondary" class="py-2 bg-secondary"></div>
    </div>
    <div class="col">
      <div data-widget="box-success" class="py-2 bg-success"></div>
      <div data-widget="box-success" class="py-2 bg-success"></div>
    </div>
    <div class="col">
      <div data-widget="box-danger" class="py-2 bg-danger"></div>
      <div data-widget="box-danger" class="py-2 bg-danger"></div>
    </div>
    <div class="col">
      <div data-widget="box-warning" class="py-2 bg-warning"></div>
      <div data-widget="box-warning" class="py-2 bg-warning"></div>
    </div>
    <div class="col">
      <div data-widget="box-info" class="py-2 bg-info"></div>
      <div data-widget="box-info" class="py-2 bg-info"></div>
    </div>
    <div class="col">
      <div data-widget="box-light" class="py-2 bg-light"></div>
      <div data-widget="box-light" class="py-2 bg-light"></div>
    </div>
    <div class="col">
      <div data-widget="box-dark" class="py-2 bg-dark"></div>
      <div data-widget="box-dark" class="py-2 bg-dark"></div>
    </div>
  </div>
  <div class="row row-cols-3">
    <div class="col"><div data-widget="box-primary" class="py-5 bg-primary"></div></div>
    <div class="col"><div data-widget="box-secondary" class="py-5 bg-secondary"></div></div>
    <div class="col"><div data-widget="box-success" class="py-5 bg-success"></div></div>
    <div class="col"><div data-widget="box-danger" class="py-5 bg-danger"></div></div>
    <div class="col"><div data-widget="box-warning" class="py-5 bg-warning"></div></div>
    <div class="col"><div data-widget="box-info" class="py-5 bg-info"></div></div>
    <div class="col"><div data-widget="box-light" class="py-5 bg-light"></div></div>
    <div class="col"><div data-widget="box-dark" class="py-5 bg-dark"></div></div>
  </div>
  <div class="row row-cols-2">
    <div class="col"><div data-widget="box-primary" class="py-5 bg-primary"></div></div>
    <div class="col"><div data-widget="box-secondary" class="py-5 bg-secondary"></div></div>
    <div class="col"><div data-widget="box-success" class="py-5 bg-success"></div></div>
    <div class="col"><div data-widget="box-danger" class="py-5 bg-danger"></div></div>
    <div class="col"><div data-widget="box-warning" class="py-5 bg-warning"></div></div>
    <div class="col"><div data-widget="box-info" class="py-5 bg-info"></div></div>
    <div class="col"><div data-widget="box-light" class="py-5 bg-light"></div></div>
    <div class="col"><div data-widget="box-dark" class="py-5 bg-dark"></div></div>
  </div>
  <div class="row row-cols-1">
    <div class="col"><div data-widget="box-primary" class="py-5 bg-primary"></div></div>
    <div class="col"><div data-widget="box-secondary" class="py-5 bg-secondary"></div></div>
    <div class="col"><div data-widget="box-success" class="py-5 bg-success"></div></div>
    <div class="col"><div data-widget="box-danger" class="py-5 bg-danger"></div></div>
    <div class="col"><div data-widget="box-warning" class="py-5 bg-warning"></div></div>
    <div class="col"><div data-widget="box-info" class="py-5 bg-info"></div></div>
    <div class="col"><div data-widget="box-light" class="py-5 bg-light"></div></div>
    <div class="col"><div data-widget="box-dark" class="py-5 bg-dark"></div></div>
  </div>
</div>
<script>
  $.holdReady(true)
  $(document).ready(function(){
    Dashboard.init("<?= $this->Auth->getUser('organization') ?>")
  })
</script>
