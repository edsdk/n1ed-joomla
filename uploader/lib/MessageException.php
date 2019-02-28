<?php
/**
* N1ED module for Joomla 3
* Developer: EdSDK
* Website: https://n1ed.com/
* License: GNU General Public License Version 2 or later
**/
defined('_JEXEC') or die('Restricted access');

class MessageException extends Exception {

    protected $m_message;

    public function __construct($message) {
        $this->m_message = (array)$message;
    }

    public function getFailMessage() {
        return $this->m_message;
    }

}
