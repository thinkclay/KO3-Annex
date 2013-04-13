    <div class="one-fourth">
      <h3>Popular Posts</h3>
      <ul class="post-list">
        <li>
          <h4><a href="#">Vivamus sagittis lacus vel augue laoreet rutrum dolor auctor.</a></h4>
          <div class="meta">14 Jun, 2012</div>
        </li>
        <li>
          <h4><a href="#">Scelerisque nisl consectetur et.</a></h4>
          <div class="meta">22 May, 2012</div>
        </li>
        <li>
          <h4><a href="#">Pellentesque ornare sem lacinia quam venenatis vestibulum.</a></h4>
          <div class="meta">13 Apr, 2012</div>
        </li>
      </ul>
    </div>
    <div class="one-fourth">
      <h3>Twitter</h3>
      <div id="twitter-wrapper">
        <div id="twitter"></div>
        <span class="username"><a href="http://twitter.com/elemisdesign">→ Follow @elemisdesign</a></span> </div>
    </div>
    <div class="one-fourth">
      <h3>A Little About Me</h3>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</p>
      <p>Donec id elit non porta gravida at eget metus. Nullam quis risus eget urna mollis ornare vel.</p>
    </div>
    <div class="one-fourth last">
      <h3>Contact Form</h3>
      <!-- Begin Form -->
      <div class="form-container">
        <div class="response"></div>
        <form class="forms" action="contact/form-handler.php" method="post">
          <fieldset>
            <ol>
              <li class="form-row text-input-row">
                <input type="text" name="name" class="text-input required defaultText" title="Name*"/>
              </li>
              <li class="form-row text-input-row">
                <input type="text" name="email" class="text-input required email defaultText" title="Email*"/>
              </li>
              <li class="form-row text-area-row">
                <textarea name="message" class="text-area required defaultText"></textarea>
              </li>
              <li class="form-row hidden-row">
                <input type="hidden" name="hidden" value="" />
              </li>
              <li class="button-row">
                <input type="submit" value="Submit" name="submit" class="btn-submit" />
              </li>
            </ol>
            <input type="hidden" name="v_error" id="v-error" value="Required" />
            <input type="hidden" name="v_email" id="v-email" value="Enter a valid email" />
          </fieldset>
        </form>
      </div>
      <!-- End Form -->
    </div>
    <div class="clear"></div>
