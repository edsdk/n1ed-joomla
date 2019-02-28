<?php
/**
* N1ED module for Joomla 3
* Developer: EdSDK
* Website: https://n1ed.com/
* License: GNU General Public License Version 2 or later
**/
defined('_JEXEC') or die('Restricted access');

class RespOk {

    public $ok = true;

}


class RespFail extends RespOk {

    public $message;

    public function __construct($message) {
        $this->ok = false;
        $this->message = $message;
    }

}

class RespUploadAddFile extends RespOk {

    public $file;

}

class RespUploadCommit extends RespOk {

    public $files;

}

class Settings {
    public $maxImageResizeWidth;
    public $maxImageResizeHeight;
}

class RespUploadInit extends RespOk {

    public $uploadId;
	public $settings;

	public function __construct($uploadId, $config) {
        $this->uploadId = $uploadId;
        $this->settings = new Settings();
        $this->settings->maxImageResizeWidth = $config->getMaxImageResizeWidth();
        $this->settings->maxImageResizeHeight = $config->getMaxImageResizeHeight();
    }

}
