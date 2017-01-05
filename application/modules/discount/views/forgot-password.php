<div class="login-container">
  <center><img src="<?php print $url?>img/width-logo2.png" width="300" alt="SatoERP"></center>
	<div class="well-login">
    <?php print $this->form_eksternal->form_open()?>
<!--		<div class="control-group">
			<div class="controls">
				<div>
          Username : dummy<br />
          Password : dummy
				</div>
			</div>
		</div>-->
    <?php
    if($pesan){
    ?>
    <blockquote class="quote_blue">
        <?php print $pesan?>
    </blockquote>
    <?php
    }
    ?>
		<div class="control-group">
			<div class="controls">
				<div>
          <?php print $this->form_eksternal->form_input('email', '', 'placeholder="Email" class="login-input mail"')?>
				</div>
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<div>
          <a href="<?php print   site_url("login")?>">Back to login</a>
				</div>
			</div>
		</div>
		<div class="clearfix">
      <?php print $this->form_eksternal->form_submit('reset', 'Reset Password', 'class="btn btn-inverse login-btn"')?>
		</div>
<!--		<div class="remember-me">
			<input class="rem_me" type="checkbox" value=""> Remeber Me
		</div>-->
    </form>
	</div>
</div>