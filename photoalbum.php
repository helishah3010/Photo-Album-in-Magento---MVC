<?php
require_once '../app/Mage.php';
Mage::init();
$app=Mage::app('default');
$basePath=Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
require_once Mage::getBaseDir('lib').DS.'phtmlBean.php';
$postProcessView    =new phtmlBean();
$write              =Mage::getSingleton('core/resource')->getConnection('core_write');
$userEmail          =$_REQUEST['email'];
$identityId         =$_REQUEST['identity'];
$userIdentity       =Mage::getModel('userprofile/customersettings')->SetCustomerDetail($userEmail);
$albumName          =$_POST['albtext'];
$_SESSION['albname']=$_POST['albtext'];
$photoDescription   =$_POST['photodesc'];
$_SESSION['desc']   =$_POST['photodesc'];
$currentTime        =Mage::getModel('core/date')->timestamp(time());
$addedTime          =date('Y-m-d H:i:s', $currentTime);
$imageSizeLimit     =1000000;
mkdir('../media/highresolution/'.$albumName.'/', 0777, true);
mkdir('../media/lowresolution/'.$albumName.'/', 0777, true);

for ($uploadCount=0;$uploadCount<count($_FILES['is_albimg']['name']);$uploadCount++) {

    //Get the temp file path
    $temporaryImagePath=$_FILES['is_albimg']['tmp_name'][$uploadCount];
    $imageSize         =$_FILES['is_albimg']['size'][$uploadCount];
    $imageName         =$_FILES['is_albimg']['name'][$uploadCount];
    $splitImageName    =explode('.', $imageName);
    $uploadFileName    =$splitImageName[0].time().".".$splitImageName[1];
    $hashedFile        =hash('md5', $uploadFileName);
    $fileName          =$hashedFile.".".$splitImageName[1];

    if ($temporaryImagePath!="") {
        //Setup our new file path
        $newFilePath          ="../media/highresolution/".$albumName."/".$fileName;
        $lowResolutionFilePath="../media/lowresolution/".$albumName."/".$fileName;
        $imageExtensions      =array('gif', 'png', 'jpg');
        if (in_array(end(explode('.', $imageName)), $imageExtensions)) {

            if ($imageSize<$imageSizeLimit) {

                //Upload the file into the temp dir
                if (move_uploaded_file($temporaryImagePath, $newFilePath)) {

                    //Handle other code here.
                    $originalImage     =imagecreatefromjpeg($newFilePath);
                    $imageDetails      =getimagesize($newFilePath);
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
                    imagejpeg($highResolutionImage, $newFilePath, 100);
                    imagejpeg($lowResolutionImage, $lowResolutionFilePath, 100);
                    $insertAlbum="insert into sohyper_album(user_id,identity_id,album_name,photo_description,album_photo,insert_time,modified_time) values ('".$userIdentity."','".$identityId."','".$albumName."','".$photoDescription."','".$fileName."','".$addedTime."','')";
                    $statusKey=$write->query($insertAlbum);
                }
            } else {
                $_SESSION['limit']='Your File Size limit exceed';
            }
        } else {
            $_SESSION['errMsg']='Invalid File Extension';
        }
    }
}
if ($_SESSION['errMsg']!='') {
    header("location:".$basePath."index.php/customer/album?albname=".$_SESSION['albname']."&desc=".$_SESSION['desc']."&errMsg=".$_SESSION['errMsg']);
    exit(0);
} elseif ($_SESSION['limit']!='') {
    header("location:".$basePath."index.php/customer/album?albname=".$_SESSION['albname']."&desc=".$_SESSION['desc']."&limit=".$_SESSION['limit']);
    exit(0);
} else {
	header("location:".$basePath."index.php/customer/album/");
	exit(0);;
}
?>