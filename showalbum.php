<?php
session_start();
require_once '../app/Mage.php';
Mage::init();
$write             =Mage::getSingleton('core/resource')->getConnection('core_write');
$userIdentity      =$_REQUEST['user_id'];
$identityId        =$_REQUEST['identityid'];
$userEmail         =$_REQUEST['email'];
$userDefaultDetails=Mage::getModel('userprofile/customersettings')->getdefalutcustomerdetails($userEmail);
$basePath          =Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
?>
<script src="<?php echo $basePath;?>js/kendoui/js/jquery.min.js"></script>
<link href="<?php echo $basePath;?>js/kendoui/styles/kendo.common.min.css" rel="stylesheet" />
<link href="<?php echo $basePath;?>js/kendoui/styles/kendo.rtl.min.css" rel="stylesheet" />
<link href="<?php echo $basePath;?>js/kendoui/styles/kendo.silver.min.css" rel="stylesheet" />
<script src="<?php echo $basePath;?>js/kendoui/js/kendo.all.min.js"></script>
<html>
<body>
<input  type="hidden" id="base_path" value="<?php echo $basePath;?>" />
<?php
if (isset($_GET['invalidmsg'])&&$_GET['invalidmsg']||$_GET['limit']) {
    $errorMessage=$_GET['invalidmsg'];
    $errorMessage.=$_GET['limit'];
}
if (!empty($_GET['invalidmsg'])||($_GET['limit'])) {
    echo '<span style="color:#ff0000">'.$errorMessage.'</span>';
}
echo '
	<div class="demo">
	<div class="fieldset">'.Mage::getModel('customer/photoalbum')->getBlockCall().'<h2 class="legend">View Album</h2>
      <form name="albview" id="albview" method="post">
        <input type="hidden" name="email" id="email" value="'.$userEmail.'"/>
     	<input type="hidden" name="identity" class="identity" value="'.$identityId.'" />
	    <table border="0" width="100%">
';
		$getAlbumDetails="SELECT * FROM sohyper_album where user_id='".$userIdentity."' and identity_id='".$identityId."'";
		$albumDetails=$write->query($getAlbumDetails);
		while ($albumData=$albumDetails->fetch()) {
		echo '
		  <tr>
	        <td align="right" width="10%"><input type="checkbox" name="chkalbum" value="1" class="checkbox"></td>
	        <td align="right" width="30%">Album Name</td>
	        <td align="right" width="30%">'.$albumData["album_name"].'</td>
	        <td align="right" width="30%">
	      	  <a href="'.Mage::getModel('customer/photoalbum')->redirectAlbumEdit().'?aid='.$albumData["album_id"].
	      	    '&action=edit&user_id='.$userIdentity.'&identity_id='.$identityId.'">
	          <button type="button" title="Edit" class="button"><span><span>Edit</span></span></button>
	      	  </a>&nbsp;&nbsp;
	      	  <a href="'.Mage::getModel('customer/photoalbum')->redirectAlbumEdit().'?delid='.$albumData["album_id"].'&action=delete&user_id='.$userIdentity.'&identity_id='.$identityId.'">
	      	    <button type="button" title="Delete" class="button"><span><span>Delete</span></span></button>
	      	  </a>
	        </td>
	      </tr>

	      <tr>
	        <td align="right" width="10%"></td>
	        <td align="right" width="30%">Photo Description</td>
	        <td align="right" width="30%">'.$albumData["photo_description"].'</td>
	        <td align="right" width="30%"></td>
	      </tr>

	       <tr>
	        <td align="right" width="10%"></td>
	        <td align="right" width="30%">Album Photo</td>
	        <td align="right" width="30%">
	          <a href="javascript:void(0)" id="imgup">
	            <img src="'.$basePath.'media/highresolution/'.$albumData["album_name"].'/'.$albumData["album_photo"].'" width="100" height="100">
	          </a>
	          </td>
	        <td align="right" width="30%"></td>
	       </tr>

	       <tr><td height="20"></td></tr>
		';
		}
		echo '
	    </table>
	  </form>
    </div>
  </div>

  <div class="demo">
    <div class="fieldset">
	  '.Mage::getModel('customer/photoalbum')->getBlockCall().'
	  <h2 class="legend">Create Album</h2>
       ';
    if (isset($_GET['errMsg'])&&$_GET['errMsg']!=''||$_GET['limit']!='') {
        $errorMessage=$_GET['errMsg'];
        $errorMessage.=$_GET['limit'];
    } else {
        $errorMessage='';
	}
	if (isset($_GET['albname'])&&$_GET['albname']!='') {
    	$albumName=$_GET['albname'];
	} else {
    	$albumName='';
	}
	if (isset($_GET['albname'])&&$_GET['albname']!='') {
    	$photoDescription=$_GET['desc'];
	} else {
    	$photoDescription='';
	}
echo '
	<form name="frmalbum" id="frmalbum" method="post" action="'.$basePath.'Album/photoalbum.php">
	  <input type="hidden" name="email" id="email" value="'.$userEmail.'"/>
	  <input type="hidden" name="identity" class="identity" value="" />
	  <table border="0" width="100%">
	    <tr>
          <td width="20%"></td>
  		  <td align="center" width="80%">
';
		  if (!empty($_GET['errMsg'])||($_GET['limit'])) {
    	      echo '<span style="color:#ff0000">'.$errorMessage.'</span>';
		  }
		  echo '
		  </td>
		</tr>
		<tr><td height="10px;"></td></tr>
		<tr>
		  <td align="right" width="20%">Album Name</td>
		  <td width="80%">
		    <input type="text" name="albtext" value="';
		      if (!empty($_GET['albname'])) {
    		      echo $albumName;
			  }
		    echo '">
		  </td>
	    </tr>
		<tr><td height="10px;"></td></tr>
		<tr>
		  <td align="right" width="20%">Photo Description</td>
		  <td width="80%">
		  <input type="text" name="photodesc" value="';
		  if (!empty($_GET['desc'])) {
    	  echo $photoDescription;
		  }
		  echo '">
		  </td>
		</tr>
	    <tr><td height="10px;"></td></tr>
		<tr>
		  <td align="right" width="20%">Album Photo</td>
		   <td width="80%"><input type="file" id="is_albimg" name="is_albimg[]" width="50px"></td></div>
		</div></tr>
		<tr id="files-root"><td></td></tr>
		<tr><td height="10px;"></td></tr><tr>
		<td align="right" width="20%"></td>
		<td width="80%"><button type="submit" title="Save Album" class="button"><span><span>Save Album</span></span></button></td>
		</tr>
	  </table>
	</form>';
		  ?>
	<script src="<?php echo $basePath;?>js/kendoScript.js"></script>
  </div>
</div>

</body>
</html>