<?php
/**
* N1ED module for Joomla 3
* Developer: EdSDK
* Website: https://n1ed.com/
* License: GNU General Public License Version 2 or later
**/
defined('_JEXEC') or die('Restricted access');

abstract class AAction {

    protected $m_config;

    public function setConfig($config) { $this->m_config = $config; }

	public abstract function getName();
	public abstract function run($req);

	protected function validateBoolean($b, $defaultValue) { return $b === null ? $defaultValue : $b; }
	protected function validateInteger($i, $defaultValue) { return $i === null ? $defaultValue : $i; }
	protected function validateString($s, $defaultValue) { return $s === null ? $defaultValue : $s; }

}
