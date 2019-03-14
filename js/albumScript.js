jQuery(document).ready(function () {
    var albumUserId = jQuery("#albumUserIdentity").val();
    var userEmail = jQuery("#albumUserEmail").val();
    var identityid = jQuery("#albumIdentityId").val();
    var basePath = jQuery("#albumbasePath").val();
    var mainuserid = jQuery('#mymainmenu .dd-selected-value').val();
    jQuery("#loadalbum").load(basePath + 'album/showalbum.php?identityid=' + identityid + '&user_id=' + albumUserId + '&email=' + userEmail,
        function (response, status, xhr) {
            if (status == "error") {
                jQuery("#loadalbum").html(msg + xhr.status + " " + xhr.statusText);
            }
        }
    );
});
