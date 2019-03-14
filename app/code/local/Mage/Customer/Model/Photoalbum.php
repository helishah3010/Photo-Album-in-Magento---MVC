
Notice: Undefined offset: 1005 in D:\Projects\sohyper\workspace\tools-formatter\UniversalIndentGUI_win32\indenters\phpStylist.php on line 1298
<?php
class Mage_Customer_Model_Photoalbum extends Mage_Core_Model_Abstract {

    public $getDatabaseConnection;

    public $basePath;

    public function _construct() {
        parent::_construct();
        $this->_init("customer/photoalbum");
        $this->write=Mage::getSingleton('core/resource')->getConnection('core_write');
        $this->basepath=Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
    }

    public function redirectAlbumEdit() {
        $albumPath=$this->basepath.'customer/albumphoto';
        return $albumPath;
    }

    public function redirectAlbum() {
        $albumPath=$this->basepath.'customer/album';
        return $albumPath;
    }

    public function getBlockCall() {
        $this->getBlockHtml("formkey");
    }

    public function getDatabaseConnection() {
        return Mage::getSingleton('core/resource')->getConnection('core_write');
    }

    public function getAlbumDetails($userIdentity, $identityId) {
        $connectDatabase=self::getDatabaseConnection();
        $getAlbumDetails="SELECT * FROM sohyper_album where user_id='".$userIdentity."' and identity_id='".$identityId."'";
        $albumDetails=$connectDatabase->query($getAlbumDetails);
        return $albumDetails;
    }

    public function getInsertTime($albumIdentity) {
        $connectDatabase=self::getDatabaseConnection();
        $getInsertTime="select insert_time from sohyper_album where album_id='".$albumIdentity."'";
        $albumTime=$connectDatabase->query($getInsertTime);
        $insertStatus=$albumTime->fetch();
        return $insertStatus;
    }

    public function getAlbumName($userIdentity, $identityId, $albumIdentity) {
        $connectDatabase=self::getDatabaseConnection();
        $selectAlbumName="SELECT * FROM sohyper_album where user_id='".$userIdentity."' and identity_id='".$identityId."' and album_id='".$albumIdentity."'";
        $checkAlbumName=$connectDatabase->query($selectAlbumName);
        $getAlbumName=$checkAlbumName->fetch();
        return $getAlbumName;
    }

    public function allAlbumNames($getAlbumName) {
        $connectDatabase=self::getDatabaseConnection();
        $allAlbumNames="SELECT album_name FROM sohyper_album WHERE album_name='".$getAlbumName['album_name']."'";
        $checkAllNames=$connectDatabase->query($allAlbumNames);
        return $checkAllNames;
    }

    public function checkPhotoData($photoDescription, $userIdentity, $identityId, $albumIdentity) {
        $connectDatabase=self::getDatabaseConnection();
        $updatePhotoData="UPDATE sohyper_album SET photo_description='".$photoDescription."' WHERE user_id='".$userIdentity."' and identity_id='".$identityId."' and album_id='".$albumIdentity."'";
        $checkPhotoData=$connectDatabase->query($updatePhotoData);
        return $checkPhotoData;
    }

    public function editAlbumData($albumName, $photoDescription, $fileName, $insertTime, $updateTime, $userIdentity, $identityId, $albumIdentity) {
        $connectDatabase=self::getDatabaseConnection();
        $updateAlbum="UPDATE sohyper_album SET album_name='".$albumName."', photo_description='".$photoDescription."', album_photo='".$fileName."', insert_time='".$insertTime."', modified_time='".$updateTime."'
    	WHERE user_id='".$userIdentity."' and identity_id='".$identityId."' and album_id='".$albumIdentity."'";
        $editAlbum=$connectDatabase->query($updateAlbum);
        return $editAlbum;
    }

    public function updateAlbum($albumName, $insertTime, $updateTime, $getAlbumName) {
        $connectDatabase=self::getDatabaseConnection();
        $updateAlbum="UPDATE sohyper_album SET album_name='".$albumName."', insert_time='".$insertTime."', modified_time='".$updateTime."'
    	        WHERE album_name='".$getAlbumName['album_name']."'";
        $editAlbum=$connectDatabase->query($updateAlbum);
        return $editAlbum;
    }

    public function updateAlbumData($albumName, $photoDescription, $insertTime, $updateTime, $userIdentity, $identityId, $albumIdentity) {
        $connectDatabase=self::getDatabaseConnection();
        $updateAlbum="UPDATE sohyper_album SET album_name='".$albumName."', photo_description='".$photoDescription."', insert_time='".$insertTime."', modified_time='".$updateTime."'
    	WHERE user_id='".$userIdentity."' and identity_id='".$identityId."' and album_id='".$albumIdentity."'";
        $editAlbum=$connectDatabase->query($updateAlbum);
        return $editAlbum;
    }

    public function updateStatus($albumIdentity) {
        $connectDatabase=self::getDatabaseConnection();
        $getImage="SELECT album_name,album_photo FROM sohyper_album WHERE album_id='".$albumIdentity."'";
        $selectStatus=$connectDatabase->query($getImage);
        $albumImage=$selectStatus->fetch();
        return $albumImage;
    }

    public function deleteAlbum($albumIdentity) {
        $connectDatabase=self::getDatabaseConnection();
        $deleteImage="DELETE FROM sohyper_album WHERE album_id='".$albumIdentity."'";
        $deletStatus=$connectDatabase->query($deleteImage);
        return $deletStatus;
    }

    public function fetchAlbumData($userIdentity, $albumId, $identityId) {
        $connectDatabase=self::getDatabaseConnection();
        $getAlbumDetails="SELECT * FROM sohyper_album where user_id='".$userIdentity."' and album_id='".$albumId."' and identity_id='".$identityId."'";
        $albumData=$connectDatabase->query($getAlbumDetails);
        return $albumData;
    }

    public function insertStatus($userIdentity, $identityId, $albumName, $photoDescription, $fileName, $addedTime) {
        $connectDatabase=self::getDatabaseConnection();
        $insertAlbum="insert into sohyper_album(user_id,identity_id,album_name,photo_description,album_photo,insert_time,modified_time) values ('".$userIdentity."','".$identityId."','".$albumName."','".$photoDescription."','".$fileName."','".$addedTime."','')";
        $statusKey=$connectDatabase->query($insertAlbum);
        return $statusKey;
    }
}
