<p><h1 class="display-5 pt-5">Forgot Password?</h1></p>
<div class="form-floating mt-5 mb-3">
  <input type="email" class="form-control shadow" id="account" name="username" autocomplete="username" placeholder="name@example.com">
  <label for="account">Email address</label>
</div>
<div class="d-flex justify-content-between py-3">
  <button class="shadow btn btn-lg btn-primary flex-grow-1 mx-1" id="submit" type="button"><i class="bi-arrow-counterclockwise me-2"></i>Reset Password</button>
  <a href="/signin" class="btn btn-lg btn-light shadow mx-1"><i class="bi-box-arrow-in-right me-2"></i>Sign In</a>
</div>
<script>
  $(document).ready(function(){
    let submit = $('button#submit')
    submit.click(function(){
      let account = $('input#account').val()
      API.get("user/recover/?id="+account,{success:function(result,status,xhr){
        Toast.create({title:result,icon:'check-lg',color:'success',close:false})
      }})
    })
  })
</script>
