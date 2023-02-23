<div id="noteContainer" class="card shadow m-4"></div>
<script>
  $(document).ready(function(){
    const noteContainer = $('#noteContainer')
    Note.button().appendTo(noteContainer)
  })
</script>
<div id="commentsContainer" class="card shadow m-4">
  <div class="card-header">
    <h5 class="card-title d-flex align-items-center my-2 fw-light" style="line-height:32px;"><i class="bi-chat-text me-2"></i>Comments</h5>
  </div>
  <div class="card-body">
    <ul class="comments list-group rounded-0 mb-3" style="">
      <li class="list-group-item border-0 p-0 d-flex mt-3">
        <div class="flex-shrink avatar-block px-3">
          <img class="img-circle rounded-circle shadow-sm img-bordered-sm" width="65" alt="Avatar" src="https://www.gravatar.com/avatar/e05b4330e145079f1d73aa859b23ab86?d=mp">
        </div>
        <div class="flex-fill">
          <div class="card shadow">
            <div class="card-body">
              <h5 class="username user-select-none m-0">
                <a class="text-decoration-none link-dark" href="/users/details?id=louis@laswitchtech.com">louis@laswitchtech.com</a>
              </h5>
              <p class="timeago user-select-none text-secondary" title="2/20/2023, 1:53:15 PM" data-bs-placement="top" style="font-size: 13px;">
                <i class="bi-clock me-1"></i><time datetime="2/20/2023, 1:53:15 PM" title="2/20/2023, 1:53:15 PM">15 minutes ago</time>
              </p>
              <p class="content comment">
                Lorem ipsum represents a long-held tradition for designers, typographers and the like. Some people hate it and argue for its demise, but others ignore the hate as they create awesome tools to help create filler text for everyone from bacon lovers to Charlie Sheen fans.
              </p>
              <p class="controls user-select-none m-0 text-secondary">
                <a class="link-secondary text-decoration-none text-sm cursor-pointer me-2"><i class="bi-hand-thumbs-up me-1"></i>Like</a>
                <a class="link-secondary text-decoration-none text-sm cursor-pointer me-2"><i class="bi-share me-1"></i>Share</a>
                <a class="link-secondary text-decoration-none text-sm cursor-pointer me-2"><i class="bi-sticky me-1"></i>Note</a>
              </p>
            </div>
          </div>
        </div>
      </li>
      <li class="list-group-item border-0 p-0 d-flex mt-3">
        <div class="flex-shrink avatar-block px-3">
          <img class="img-circle rounded-circle shadow-sm img-bordered-sm" width="65" alt="Avatar" src="https://www.gravatar.com/avatar/e05b4330e145079f1d73aa859b23ab86?d=mp">
        </div>
        <div class="flex-fill">
          <div class="card shadow">
            <div class="card-body">
              <h5 class="username user-select-none m-0">
                <a class="text-decoration-none link-dark" href="/users/details?id=louis@laswitchtech.com">louis@laswitchtech.com</a>
              </h5>
              <p class="timeago user-select-none text-secondary" title="2/20/2023, 1:53:15 PM" data-bs-placement="top" style="font-size: 13px;">
                <i class="bi-clock me-1"></i><time datetime="2/20/2023, 1:53:15 PM" title="2/20/2023, 1:53:15 PM">15 minutes ago</time>
              </p>
              <p class="content comment">
                Lorem ipsum represents a long-held tradition for designers, typographers and the like. Some people hate it and argue for its demise, but others ignore the hate as they create awesome tools to help create filler text for everyone from bacon lovers to Charlie Sheen fans.
              </p>
              <p class="controls user-select-none m-0 text-secondary">
                <a class="link-secondary text-decoration-none text-sm cursor-pointer me-2"><i class="bi-hand-thumbs-up me-1"></i>Like</a>
                <a class="link-secondary text-decoration-none text-sm cursor-pointer me-2"><i class="bi-share me-1"></i>Share</a>
                <a class="link-secondary text-decoration-none text-sm cursor-pointer me-2"><i class="bi-sticky me-1"></i>Note</a>
              </p>
            </div>
          </div>
        </div>
      </li>
    </ul>
  </div>
</div>
<script>
  $(document).ready(function(){
    const commentsContainer = $('#commentsContainer')
  })
</script>
