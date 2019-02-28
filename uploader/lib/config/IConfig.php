<?php
/**
* N1ED module for Joomla 3
* Developer: EdSDK
* Website: https://n1ed.com/
* License: GNU General Public License Version 2 or later
**/
defined('_JEXEC') or die('Restricted access');

interface IConfig {

    public function setTestConfig($testConf);

    public function getBaseDir();
    public function getTmpDir();

    public function getMaxUploadFileSize();
    public function getAllowedExtensions();
    public function getJpegQuality();

    public function getMaxImageResizeWidth();
    public function getMaxImageResizeHeight();

    public function getCrossDomainUrl();

    public function doKeepUploads();

    public function isTestAllowed();

    public function getRelocateFromHosts();

}