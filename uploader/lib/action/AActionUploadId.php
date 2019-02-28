<?php
/**
* N1ED module for Joomla 3
* Developer: EdSDK
* Website: https://n1ed.com/
* License: GNU General Public License Version 2 or later
**/
defined('_JEXEC') or die('Restricted access');

abstract class AActionUploadId extends AAction {

    protected function validateUploadId($req) {
        if ($req->uploadId === null)
            throw new MessageException(Message::createMessage(Message::UPLOAD_ID_NOT_SET));

        $dir = $this->m_config->getTmpDir() . "/" . $req->uploadId;
        if (!file_exists($dir) || !is_dir($dir))
            throw new MessageException(Message::createMessage(Message::UPLOAD_ID_INCORRECT));
    }

}
