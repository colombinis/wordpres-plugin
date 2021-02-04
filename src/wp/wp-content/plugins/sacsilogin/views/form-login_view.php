<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$lostpassword_url = wp_lostpassword_url();
$nonce_field = wp_nonce_field('ajax-login-nonce', self::$security,false,false);

?>

<form id="login" method="post">
    <h2>Site Login</h2>
    <p class="status"></p>
    <input id="username" type="text" name="username" placeholder="User@email.com">
    <input id="password" type="password" name="password" placeholder="Your password">
    <input class="submit_button" type="submit" value="Login" name="submit">
    <?= $nonce_field ?> <span class="me-olvide">  <a href="<?= $lostpassword_url;?>">olvide mi pass</a></a>
</form>