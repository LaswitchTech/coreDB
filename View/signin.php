<p><h1 class="display-5 pt-5">Sign in</h1></p>
<div class="form-floating mt-5 mb-3">
  <input type="email" class="form-control shadow" id="floatingInput" name="username" autocomplete="username" placeholder="name@example.com">
  <label for="floatingInput">Email address</label>
</div>
<div class="form-floating mb-3">
  <input type="password" class="form-control shadow" id="floatingPassword" name="password" autocomplete="current-password" placeholder="Password">
  <label for="floatingPassword">Password</label>
</div>
<div class="d-flex justify-content-between">
  <div class="form-check form-check-inline mb-3 mx-1">
    <input class="form-check-input" type="checkbox" value="remember" name="remember" id="flexCheckDefault">
    <label class="form-check-label" for="flexCheckDefault">Remember me</label>
  </div>
  <a href="/forgot" class="mx-1 text-decoration-none">Forgot password?</a>
</div>
<div class="d-flex justify-content-between py-3">
  <button class="shadow btn btn-lg btn-primary flex-grow-1 mx-1" type="submit"><i class="bi-box-arrow-in-right me-2"></i>Sign in</button>
  <a href="/register" class="btn btn-lg btn-light shadow mx-1"><i class="bi-check2-square me-2"></i>Register</a>
</div>
