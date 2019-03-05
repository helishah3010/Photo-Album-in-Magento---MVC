<?php
require_once '../app/Mage.php';
Mage::init();
$applications=Mage::app('default');
$basePath=Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
require_once Mage::getBaseDir('lib').DS.'phtmlBean.php';
$postProcessView =new phtmlBean();
$write           =Mage::getSingleton('core/resource')->getConnection('core_write');
$albumIdentity   =$_REQUEST['aid'];
$getInsertTime   ="select insert_time from sohyper_album where album_id='".$albumIdentity."'";
$albumTime       =$write->query($getInsertTime);
$insertStatus    =$albumTime->fetch();
$insertTime      =$insertStatus['insert_time'];
$userEmail       =$_REQUEST['email'];
$identityId      =$_REQUEST['identity'];
$userIdentity    =Mage::getModel('userprofile/customersettings')->SetCustomerDetail($userEmail);
$albumName       =$_POST['txtalbname'];
$photoDescription=$_POST['txtalbdesc'];
$currentTime     =Mage::getModel('core/date')->timestamp(time());
$updateTime      =date('Y-m-d H:i:s', $currentTime);
mkdir('../Connectors/upload_images/'.$albumName.'/', 0777, true);
$fileTemporaryName =$_FILES['is_albimg']['tmp_name'];
$fileName          =$_FILES['is_albimg']['name'];
$splitFileName     =explode('.', $fileName);
$uploadFileName    =$splitFileName[0].time().".".$splitFileName[1];
$hashedFile        =hash('md5', $uploadFileName);
$fileName          =$hashedFile.".".$splitFileName[1];
$highResolutionPath='../media/highresolution/'.$albumName.'/'.$fileName;
$lowResolutionPath ='../media/lowresolution/'.$albumName.'/'.$fileName;
if (move_uploaded_file($fileTemporaryName, $highResolutionPath)) {
    $originalImage     =imagecreatefromjpeg($highResolutionPath);
    $imageDetails      =getimagesize($highResolutionPath);
    $uploadedImageWidth=$imageDetails[0];
    // current width as found in image file
    $uploadedImageHeight=$imageDetails[1];
    // current height as found in image file
    $highResolutionWidth=1024;
    // new image width
    $newHeight=768;
    // new image height
    $lowResolutionWidth =512;
    $lowResolutionHeight=384;
    $highResolutionImage=imagecreatetruecolor($highResolutionWidth, $newHeight);
    $lowResolutionImage =imagecreatetruecolor($lowResolutionWidth, $lowResolutionHeight);
    imagecopyresampled($highResolutionImage, $originalImage, 0, 0, 0, 0, $highResolutionWidth, $newHeight, $uploadedImageWidth, $uploadedImageHeight);
    imagecopyresampled($lowResolutionImage, $originalImage, 0, 0, 0, 0, $lowResolutionWidth, $lowResolutionHeight, $uploadedImageWidth, $uploadedImageHeight);
    // This will just copy the new image over the original at the same filePath.
    imagejpeg($highResolutionImage, $highResolutionPath, 100);
    imagejpeg($lowResolutionImage, $lowResolutionPath, 100);
    $updateAlbum="UPDATE sohyper_album SET album_name='".$albumName."', photo_description='".$photoDescription."', album_photo='".$fileName."', insert_time='".$insertTime."', modified_time='".$updateTime."'
    	WHERE user_id='".$userIdentity."' and identity_id='".$identityId."' and album_id='".$albumIdentity."'";
    $editAlbum=$write->query($updateAlbum);
} else {
    $updateAlbum="UPDATE sohyper_album SET album_name='".$albumName."', photo_description='".$photoDescription."', insert_time='".$insertTime."', modified_time='".$updateTime."'
    	WHERE user_id='".$userIdentity."' and identity_id='".$identityId."' and album_id='".$albumIdentity."'";
    $editAlbum=$write->query($updateAlbum);
}
header("location:".$basePath."index.php/customer/album/");
exit(0);
?>