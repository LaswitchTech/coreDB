<p><h1 class="display-5 pt-5">Account Recovery</h1></p>
<div class="form-floating mt-5 mb-3">
  <input type="text" class="form-control shadow" id="token" name="token" value="<?php if(isset($_GET['token'])){ echo base64_decode($_GET['token']); } ?>" placeholder="Token">
  <label for="token">Token</label>
</div>
<div class="form-floating mb-3">
  <input type="email" class="form-control shadow" id="account" name="username" autocomplete="username" placeholder="Email address">
  <label for="account">Email address</label>
</div>
<div class="form-floating mb-3">
  <input type="password" class="form-control shadow" id="password" name="password" autocomplete="new-password" placeholder="New Password">
  <label for="password">New Password</label>
</div>
<div class="form-floating mb-3">
  <input type="password" class="form-control shadow" id="confirm" name="confirm" autocomplete="new-password" placeholder="Confirm Password">
  <label for="confirm">Confirm Password</label>
</div>
<div class="d-flex justify-content-between py-3">
  <button class="shadow btn btn-lg btn-primary flex-grow-1 mx-1" id="submit" type="button"><i class="bi-arrow-counterclockwise me-2"></i>Recover</button>
  <a href="/signin" class="btn btn-lg btn-light shadow mx-1"><i class="bi-box-arrow-in-right me-2"></i>Sign In</a>
</div>
<script>
  $(document).ready(function(){
    let submit = $('button#submit')
    let account = $('input#account')
    let token = $('input#token')
    let password = $('input#password')
    let confirm = $('input#confirm')
    const email = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    const pwdLength = /^.{8,}$/;
    const pwdUpper = /[A-Z]+/;
    const pwdLower = /[a-z]+/;
    const pwdNumber = /[0-9]+/;
    const pwdSpecial = /[!@#$%^&()'[\]"?+-/*={}.,;:_]+/;
    const validate = {
      account: function(){
        const value = account.val()
        if(email.test(value)){
          $('#account-restriction').addClass('active')
          account.removeClass('border-danger').addClass('border-primary')
        } else {
          $('#account-restriction').removeClass('active')
          account.removeClass('border-primary').addClass('border-danger')
        }
        return (email.test(value))
      },
      token: function(){
        const value = token.val()
        if(pwdLength.test(value)){
          $('#token-restriction').addClass('active')
          token.removeClass('border-danger').addClass('border-primary')
        } else {
          $('#token-restriction').removeClass('active')
          token.removeClass('border-primary').addClass('border-danger')
        }
        return (pwdLength.test(value))
      },
      password: function(){
        const value = password.val()
        if(pwdLength.test(value)){
          $('#pwd-restriction-length').addClass('active')
          password.removeClass('border-danger').addClass('border-primary')
        } else {
          $('#pwd-restriction-length').removeClass('active')
          password.removeClass('border-primary').addClass('border-danger')
        }
        if(pwdUpper.test(value) && pwdLower.test(value)){
          $('#pwd-restriction-upperlower').addClass('active')
          password.removeClass('border-danger').addClass('border-primary')
        } else {
          $('#pwd-restriction-upperlower').removeClass('active')
          password.removeClass('border-primary').addClass('border-danger')
        }
        if(pwdNumber.test(value)){
          $('#pwd-restriction-number').addClass('active');
          password.removeClass('border-danger').addClass('border-primary')
        } else {
          $('#pwd-restriction-number').removeClass('active')
          password.removeClass('border-primary').addClass('border-danger')
        }
        if(pwdSpecial.test(value)){
          $('#pwd-restriction-special').addClass('active')
          password.removeClass('border-danger').addClass('border-primary')
        } else {
          $('#pwd-restriction-special').removeClass('active')
          password.removeClass('border-primary').addClass('border-danger')
        }
        return (pwdLength.test(value) && pwdUpper.test(value) && pwdLower.test(value) && pwdNumber.test(value) && pwdSpecial.test(value))
      },
      confirm: function(){
        const value = {
          password: password.val(),
          confirm: confirm.val(),
        }
        if(value.password === value.confirm){
          $('#pwd-restriction-match').addClass('active');
          confirm.removeClass('border-danger').addClass('border-primary')
        } else {
          $('#pwd-restriction-match').removeClass('active');
          confirm.removeClass('border-primary').addClass('border-danger')
        }
        return (value.password === value.confirm)
      },
    }
    token.popover = $(document.createElement('ul')).addClass('list-group list-group-flush rounded')
    token.popover.restriction = $(document.createElement('li')).addClass('list-group-item').attr('id','token-restriction').html('Must provide a token').appendTo(token.popover)
    token.bs = new bootstrap.Popover(token,{trigger:'focus',placement:'top',fallbackPlacements:['top','bottom'],customClass:'popover-body-p-0',html:true,content:token.popover})
    token.keyup(function(){
      validate.token()
    });
    account.popover = $(document.createElement('ul')).addClass('list-group list-group-flush rounded')
    account.popover.restriction = $(document.createElement('li')).addClass('list-group-item').attr('id','account-restriction').html('Must be a valide email').appendTo(account.popover)
    account.bs = new bootstrap.Popover(account,{trigger:'focus',placement:'top',fallbackPlacements:['top','bottom'],customClass:'popover-body-p-0',html:true,content:account.popover})
    account.keyup(function(){
      validate.account()
    });
    password.popover = $(document.createElement('ul')).addClass('list-group list-group-flush rounded')
    password.popover.restrictionLength = $(document.createElement('li')).addClass('list-group-item').attr('id','pwd-restriction-length').html('Be at least 8 characters in length').appendTo(password.popover)
    password.popover.restrictionUpperlower = $(document.createElement('li')).addClass('list-group-item').attr('id','pwd-restriction-upperlower').html('Contain at least 1 lowercase and 1 uppercase letter').appendTo(password.popover)
    password.popover.restrictionNumber = $(document.createElement('li')).addClass('list-group-item').attr('id','pwd-restriction-number').html('Contain at least 1 number (0–9)').appendTo(password.popover)
    password.popover.restrictionSpecial = $(document.createElement('li')).addClass('list-group-item').attr('id','pwd-restriction-special').html('Contain at least 1 special character (!@#$%^&()\'[]"?+-/*)').appendTo(password.popover)
    password.bs = new bootstrap.Popover(password,{trigger:'focus',placement:'top',fallbackPlacements:['top','bottom'],customClass:'popover-body-p-0',html:true,content:password.popover})
    password.keyup(function(){
      validate.password()
      validate.confirm()
    });
    confirm.popover = $(document.createElement('ul')).addClass('list-group list-group-flush rounded')
    confirm.popover.restrictionMatch = $(document.createElement('li')).addClass('list-group-item').attr('id','pwd-restriction-match').html('Is the same as above').appendTo(confirm.popover)
    confirm.bs = new bootstrap.Popover(confirm,{trigger:'focus',placement:'top',fallbackPlacements:['top','bottom'],customClass:'popover-body-p-0',html:true,content:confirm.popover})
    confirm.keyup(function(){
      validate.password()
      validate.confirm()
    });
    validate.password()
    validate.confirm()
    validate.account()
    validate.token()
    submit.click(function(){
      const value = {
        password: password.val(),
        confirm: confirm.val(),
        account: account.val(),
        token: token.val(),
      }
      if(validate.password()){
        if(validate.confirm()){
          if(validate.account()){
            if(validate.token()){
              API.post("user/recovered/?id="+value.account,{token:value.token,password:value.password,confirm:value.confirm},{success:function(result,status,xhr){
                Toast.create({title:result,icon:'check-lg',color:'success',close:false})
                setTimeout(function(){
                  window.open(window.location.protocol+"//"+window.location.hostname,"_self")
                }, 5000)
              }})
            }
          }
        }
      }
    })
  })
</script>
