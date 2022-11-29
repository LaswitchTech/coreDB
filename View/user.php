<?php
$username = "";
if(isset($_GET['id'])){ $username = $_GET['id']; }
?>
<div class="row m-0 h-100" id="userContainer">
  <div class="col-sm-6 col-lg-4 p-2 ps-4">
    <div class="card shadow">
      <div class="card-header">
        <h5 class="card-title my-2 fw-light"><i class="bi-person-circle me-2"></i>User</h5>
      </div>
      <div class="card-body p-0">
        <div class="d-flex align-items-center flex-column rounded-bottom bg-image text-light py-4">
          <div class="shadow border border-light border-4 rounded-circle">
            <img src="<?= $this->coreDB->getGravatar($username) ?>" class="img-circle border border-primary border-4 rounded-circle" style="--bs-border-opacity: 0;" width="128" height="128" alt="Avatar">
          </div>
          <h5 class="fw-light mt-2"><?= $username ?></h5>
          <small>Member since <?= $this->coreDB->getTimeago($this->Auth->getUser("created")) ?></small>
        </div>
        <div class="d-flex justify-content-around py-3">
          <button class="btn btn-light shadow" type="button" aria-controls="offcanvasActivity"><i class="bi-activity me-2"></i>Activity</button>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-8 p-2 pe-4"></div>
</div>
<script>
  $(document).ready(function(){
    const userContainer = $('#userContainer')
    API.get("user/get/?id=<?= $username ?>",{success:function(result,status,xhr){
      if(typeof result[0] !== "undefined"){
        const userData = result[0]
        console.log(userData)
        userContainer.find('button[aria-controls="offcanvasActivity"]').click(function(){
          Activity.show("users",userData.id)
        })
      }
    }})
  })
</script>
