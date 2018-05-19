<?php

$count             = 1;
$allRoles          = get_editable_roles();
$allRoleCollection = [];
foreach ( $allRoles as $key => $currentRole ) {
	if ( ! empty( $key ) ) {
		$allRoleCollection[ $key ] = $currentRole;
	}
}

?>
<style type="text/css">
    .form-table tr .action-btn {
        width: 15%;
    }

    .form-table td {
        padding: 0px;
    }

    .form-table .delete-link {
        padding: 2px 6.5px;
        cursor: pointer;
        border: none;
        background: none;
        color: blue;
        font-size: 10px;
    }

    .form-table form {
        margin-top: 9px;
    }

    .form-table strong {
        position: relative;
        top: -2px;
    }

    .create-role-submit-btn {
        border: none !important;
        float: left;
        height: 26.3px !important;
        border: none;
        margin-left: -1px;
        margin-top: 1.5px;
        cursor: pointer;
    }
</style>
<script type="application/javascript">
    jQuery(function () {
        jQuery('#todo').multipleSelect({
            width: '100%',
            filter: true
        });
        jQuery.removeCookie("gemarj_message", {path: '/'});
    });
</script>


<div id="">
    <div id="dashboard-widgets" class="metabox-holder">
        <div id="postbox-container-1" class="postbox-container">
            <div id="normal-sortables" class="meta-box-sortables ">
                <div id="dashboard_right_now" class="postbox ">
                    <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: At a Glance</span><span
                                class="toggle-indicator" aria-hidden="true"></span></button>
                    <h2 class="hndle "><span>Assign Role</span></h2>
                    <div class="inside">
                        <div class="main">
                            <form method="POST" action="/wp-admin/admin-post.php">
		                        <?php wp_nonce_field( 'update_role' ) ?>
                                <input type="hidden" name="action" value="gemarj_update_role">
                                <input type="hidden" name="roleNameOrig" value="<?php echo $_GET['roleNameOrig'] ?>">
                                <input type="text" name="roleRawName" placeholder="Role" style="    width: 85%;float: left;"
                                       value="<?php echo $_GET['roleRawName'] ?>">
                                <button type="submit" class="create-role-submit-btn">Submit</button>
                                <div style="clear: both;"></div>
                            </form>

                            <table class="form-table">
                                <tbody>
								<?php foreach ( $allRoleCollection as $key => $currentRole ): ?>
                                    <tr>
                                        <td>
                                            <strong>
												<?php echo $count ++ ?> ) <?php echo $currentRole['name'] ?>
                                            </strong>
                                        </td>
                                        <td class="action-btn">
                                            <form method="GET" action="/wp-admin/admin.php">
												<?php wp_nonce_field( 'edit_role' ) ?>
                                                <input type="hidden" name="page"  value="gemarj_menu" >
                                                <input type="hidden" name="roleNameOrig" value="<?php echo $key ?>">
                                                <input type="hidden" name="roleRawName"
                                                       value="<?php echo $currentRole['name'] ?>">
                                                <button type="submit" class="delete-link">[ edit ]</button>
                                                <input type="hidden" name="roleToDelete"
                                                       value="<?php echo $currentRole['name'] ?>">
                                            </form>
                                        </td>
                                        <td class="action-btn">
                                            <form method="POST" action="/wp-admin/admin-post.php">
												<?php wp_nonce_field( 'delete_role' ) ?>
                                                <input type="hidden" name="action" value="gemarj_delete_role">
                                                <input type="hidden" name="role_to_delete" value="<?php echo $key ?>">
                                                <button type="submit" class="delete-link">[ delete ]</button>
                                            </form>
                                        </td>
                                    </tr>
								<?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



