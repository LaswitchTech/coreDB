<div id="dashboard">
  <div class="row row-cols-4">
    <div class="col">
      <div data-widget="box-primary" class="py-4 rounded shadow border bg-primary"></div>
      <div data-widget="box-dark" class="py-4 rounded shadow border bg-dark"></div>
    </div>
    <div class="col">
      <div data-widget="box-secondary" class="py-4 rounded shadow border bg-secondary"></div>
      <div data-widget="box-light" class="py-4 rounded shadow border bg-light"></div>
    </div>
    <div class="col">
      <div data-widget="box-success" class="py-4 rounded shadow border bg-success"></div>
      <div data-widget="box-info" class="py-4 rounded shadow border bg-info"></div>
    </div>
    <div class="col">
      <div data-widget="box-danger" class="py-4 rounded shadow border bg-danger"></div>
      <div data-widget="box-warning" class="py-4 rounded shadow border bg-warning"></div>
    </div>
    <div class="col">
      <div data-widget="box-warning" class="py-4 rounded shadow border bg-warning"></div>
      <div data-widget="box-danger" class="py-4 rounded shadow border bg-danger"></div>
    </div>
    <div class="col">
      <div data-widget="box-info" class="py-4 rounded shadow border bg-info"></div>
      <div data-widget="box-success" class="py-4 rounded shadow border bg-success"></div>
    </div>
    <div class="col">
      <div data-widget="box-light" class="py-4 rounded shadow border bg-light"></div>
      <div data-widget="box-secondary" class="py-4 rounded shadow border bg-secondary"></div>
    </div>
    <div class="col">
      <div data-widget="box-dark" class="py-4 rounded shadow border bg-dark"></div>
      <div data-widget="box-primary" class="py-4 rounded shadow border bg-primary"></div>
    </div>
  </div>
  <div class="row row-cols-3">
    <div class="col"><div data-widget="box-primary" class="py-4 rounded shadow border bg-primary"></div></div>
    <div class="col"><div data-widget="box-secondary" class="py-4 rounded shadow border bg-secondary"></div></div>
    <div class="col"><div data-widget="box-success" class="py-4 rounded shadow border bg-success"></div></div>
    <div class="col"><div data-widget="box-danger" class="py-4 rounded shadow border bg-danger"></div></div>
    <div class="col"><div data-widget="box-warning" class="py-4 rounded shadow border bg-warning"></div></div>
    <div class="col"><div data-widget="box-info" class="py-4 rounded shadow border bg-info"></div></div>
    <div class="col"><div data-widget="box-light" class="py-4 rounded shadow border bg-light"></div></div>
    <div class="col"><div data-widget="box-dark" class="py-4 rounded shadow border bg-dark"></div></div>
  </div>
  <div class="row row-cols-2">
    <div class="col"><div data-widget="box-primary" class="py-4 rounded shadow border bg-primary"></div></div>
    <div class="col"><div data-widget="box-secondary" class="py-4 rounded shadow border bg-secondary"></div></div>
    <div class="col"><div data-widget="box-success" class="py-4 rounded shadow border bg-success"></div></div>
    <div class="col"><div data-widget="box-danger" class="py-4 rounded shadow border bg-danger"></div></div>
    <div class="col"><div data-widget="box-warning" class="py-4 rounded shadow border bg-warning"></div></div>
    <div class="col"><div data-widget="box-info" class="py-4 rounded shadow border bg-info"></div></div>
    <div class="col"><div data-widget="box-light" class="py-4 rounded shadow border bg-light"></div></div>
    <div class="col"><div data-widget="box-dark" class="py-4 rounded shadow border bg-dark"></div></div>
  </div>
  <div class="row row-cols-1">
    <div class="col"><div data-widget="box-primary" class="py-4 rounded shadow border bg-primary"></div></div>
    <div class="col"><div data-widget="box-secondary" class="py-4 rounded shadow border bg-secondary"></div></div>
    <div class="col"><div data-widget="box-success" class="py-4 rounded shadow border bg-success"></div></div>
    <div class="col"><div data-widget="box-danger" class="py-4 rounded shadow border bg-danger"></div></div>
    <div class="col"><div data-widget="box-warning" class="py-4 rounded shadow border bg-warning"></div></div>
    <div class="col"><div data-widget="box-info" class="py-4 rounded shadow border bg-info"></div></div>
    <div class="col"><div data-widget="box-light" class="py-4 rounded shadow border bg-light"></div></div>
    <div class="col"><div data-widget="box-dark" class="py-4 rounded shadow border bg-dark"></div></div>
  </div>
</div>
<script>
  $(document).ready(function(){
    Dashboard.init("<?= $this->Auth->getUser('organization') ?>")
  })
</script>
