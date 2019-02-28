<?php
/**
* N1ED module for Joomla 3
* Developer: EdSDK
* Website: https://n1ed.com/
* License: GNU General Public License Version 2 or later
**/
defined('_JEXEC') or die('Restricted access');

class Actions {

    protected $m_actions = [];

    public function __construct() {
        $this->m_actions[] = new ActionError();

        $this->m_actions[] = new ActionUploadInit();
        $this->m_actions[] = new ActionUploadAddFile();
        $this->m_actions[] = new ActionUploadRemoveFile();
        $this->m_actions[] = new ActionUploadCommit();
        $this->m_actions[] = new ActionUploadCancel();
    }

    public function getActionError() {
        return $this->getAction("error");
    }

    public function getAction($name) {
        for ($i=0; $i<count($this->m_actions); $i++)
            if ($this->m_actions[$i]->getName() === $name)
                return $this->m_actions[$i];
        return null;
    }

}
