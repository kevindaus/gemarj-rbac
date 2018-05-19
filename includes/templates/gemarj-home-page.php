<?php

$allRoles = get_editable_roles();

$allRoleCollection = [];
foreach ( $allRoles as $key => $currentRole ) {
	if ( isset( $key ) && ! empty( $key ) ) {
		$allRoleCollection[ $key ] = $currentRole['name'];
	}
}
$allUsers = get_users();
$message  = null;

?>

<script type="application/javascript">
    jQuery(function ($) {
        var rolesSelectField = document.getElementById("currentSelectedUserAssignedRole");
        var assignedRole = jQuery("#currentSelectedUserAssignedRole").multipleSelect({
            selectAll: false
        });
        var multiSelectObj = jQuery("#selectedUser").multipleSelect({
            selectAll: false,
            single: true,
            onClick: function (view) {
                var currentUser = AllUsers[view.value];
                if (currentUser !== undefined) {
                    var roles = currentUser.roles;
                    console.log(roles)
                    // reset selection
                    jQuery("#currentSelectedUserAssignedRole option").removeAttr("selected");
                    assignedRole.multipleSelect("refresh")
                    /*compose query*/
                    if (Array.isArray(roles)) {
                        for (loopIndex = 0; loopIndex < roles.length; loopIndex++) {
                            var currentRoleName = roles[loopIndex];
                            var targetElement = "#currentSelectedUserAssignedRole option[value='" + currentRoleName + "']";
                            jQuery(targetElement).prop('selected', true);
                        }
                        assignedRole.multipleSelect("refresh")
                    } else {
                        Object.keys(roles).forEach(function(key,index) {
                            var targetElement = "#currentSelectedUserAssignedRole option[value='" + roles[key] + "']";
                            jQuery(targetElement).prop('selected', true);
                        });
                        assignedRole.multipleSelect("refresh");
                    }
                }

            }
        });

        jQuery.removeCookie("gemarj_message", {path: '/'});
    })
</script>
<style type="text/css">
    .select-fields {
        width: 300px;
    }

    #assign-role-table-container td {
        padding: 10px;
    }

    #dashboard_right_now li {
        width: 100%;
    }
</style>


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
								<?php wp_nonce_field( 'add_role' ) ?>
                                <input type="hidden" name="action" value="gemarj_rbac_assign_role">
                                <table id="assign-role-table-container">
                                    <tr>
                                        <td>
                                            <label for="">User : </label>
                                        </td>
                                        <td>
                                            <select required name="selectedUser" multiple="multiple" id="selectedUser"
                                                    class="select-fields">
												<?php foreach ( $allUsers as $currentUser ): ?>
                                                    <option
                                                            value="<?php echo $currentUser->ID ?>"
                                                    >
														<?php echo $currentUser->display_name ?>
                                                    </option>
												<?php endforeach; ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label for="">Role(s) to assign</label>
                                        </td>
                                        <td>
                                            <select required name="assignedRole[]" multiple="multiple"
                                                    id="currentSelectedUserAssignedRole"
                                                    class="select-fields">
												<?php foreach ( $allRoleCollection as $currentRole => $currentRoleName ): ?>
                                                    <option value="<?php echo $currentRole ?>"> <?php echo $currentRoleName ?> </option>
												<?php endforeach; ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <button type="submit">Submit</button>
                                        </td>
                                    </tr>
                                </table>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>