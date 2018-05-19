<?php
$customErrorMessage      = get_option( 'gemarj-rbac-error-message', 'You are not allowed to view this content' );

?>

<h3>Error Message</h3>
<p>
	Customized error message
</p>

<form method="POST" action="/wp-admin/admin-post.php">
	<?php wp_nonce_field( 'gemarj_rbac_update_settings' ) ?>
    <input type="hidden" name="action" value="gemarj_rbac_update_options">
    <textarea name="custom_error_message" id="" cols="80" rows="10"><?php echo $customErrorMessage?></textarea>
    <br>
    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
</form>
