<?php 
require_once '../app/Mage.php';
Mage::init();
$application=Mage::app('default');
$basePath=Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
include_once Mage::getBaseDir('lib').DS.'phtmlBean.php';
$postProcessView       =new phtmlBean();
$write                 =Mage::getSingleton('core/resource')->getConnection('core_write');
$userEmail             =$_REQUEST['email'];
$identityId            =$_REQUEST['identity'];
$userIdentity          =Mage::getModel('userprofile/customersettings')->SetCustomerDetail($userEmail);
$albumName             =$_POST['albname'];
$_SESSION['album_nm']  =$_POST['albname'];
$photoDescription      =$_POST['phototext'];
$_SESSION['photo_desc']=$_POST['phototext'];
$currentTime           =Mage::getModel('core/date')->timestamp(time());
$addedTime             =date('Y-m-d H:i:s', $currentTime);
$imageSizeLimit        =100000;
mkdir("../media/highresolution/$albumName/");
mkdir("../media/lowresolution/$albumName/");
for ($uploadCount=0;$uploadCount<count($_FILES['is_imgupload']['name']);$uploadCount++) {
    //Get the temp file path
    $temporaryImagePath=$_FILES['is_imgupload']['tmp_name'][$uploadCount];
    $imageSize         =$_FILES['is_imgupload']['size'][$uploadCount];
    $imageName         =$_FILES['is_imgupload']['name'][$uploadCount];
    $splitImageName    =explode('.', $imageName);
    $uploadFileName    =$splitImageName[0].time().".".$splitImageName[1];
    $hashedFile        =hash('md5', $uploadFileName);
    $fileName          =$hashedFile.".".$splitImageName[1];
    //Make sure we have a filepath
    if ($temporaryImagePath!="") {
        //Setup our new file path
        $newFilePath="../media/highresolution/".$albumName."/".$fileName;
        $lowResolutionFilePath="../media/lowresolution/".$albumName."/".$fileName;
        // check valid image extension
        $imageExtensions=array('gif', 'png', 'jpg');
        if (in_array(end(explode('.', $imageName)), $imageExtensions)) {
            // check the uploaded image file size
            if ($imageSize<$imageSizeLimit) {
                //Upload the file into the temp dir
                if (move_uploaded_file($temporaryImagePath, $newFilePath)) {
                    $originalImage     =imagecreatefromjpeg($newFilePath);
                    $imageDetails      =getimagesize($newFilePath);
                    $uploadedImageWidth=$imageDetails[0];
                    // current width as found in image file
                    $uploadedImageHeight=$imageDetails[1];
                    // current height as found in image file
                    $newWidth=1024;
                    // new image width
                    $newHeight=768;
                    // new image height
                    $lowResolutionWidth =512;
                    $lowResolutionHeight=384;
                    $highResolutionImage=imagecreatetruecolor($newWidth, $newHeight);
                    $lowResolutionImage =imagecreatetruecolor($lowResolutionWidth, $lowResolutionHeight);
                    imagecopyresampled($highResolutionImage, $originalImage, 0, 0, 0, 0, $newWidth, $newHeight, $uploadedImageWidth, $uploadedImageHeight);
                    imagecopyresampled($lowResolutionImage, $originalImage, 0, 0, 0, 0, $lowResolutionWidth, $lowResolutionHeight, $uploadedImageWidth, $uploadedImageHeight);
                    // This will just copy the new image over the original at the same filePath.
                    imagejpeg($highResolutionImage, $newFilePath, 100);
                    imagejpeg($lowResolutionImage, $lowResolutionFilePath, 100);
                    $insertAlbum="insert into sohyper_album(user_id,identity_id,album_name,photo_description,album_photo,insert_time,modified_time) 
                    	values ('".$userIdentity."','".$identityId."','".$albumName."','".$photoDescription."','".$fileName."','".$addedTime."','')";
                    $insertStatus=$write->query($insertAlbum);
                }
            } else {
                $_SESSION['limit']='Your File Size limit exceed';
            }
        } else {
            $_SESSION['invalidmsg']='Invalid File Extension';
        }
    }
}
if ($_SESSION['invalidmsg']!='') {
    header("location:".$basePath."index.php/customer/album?albnm=".$_SESSION['album_nm']."&description=".$_SESSION['photo_desc']."&invalidmsg=".$_SESSION['invalidmsg']);
    exit(0);
} elseif ($_SESSION['limit']!='') {
    header("location:".$basePath."index.php/customer/album?albnm=".$_SESSION['album_nm']."&description=".$_SESSION['photo_desc']."&limit=".$_SESSION['limit']);
    exit(0);
} else {
    header("location:".$basePath."index.php/customer/album/");
    exit(0);
}
?>