<?php
/**
* N1ED module for Joomla 3
* Developer: EdSDK
* Website: https://n1ed.com/
* License: GNU General Public License Version 2 or later
**/
defined('_JEXEC') or die('Restricted access');

class plgEditorsN1edInstallerScript {

    function postflight($type, $parent) {
        
        // Prepare directory for N1ED files
        $n1ed_files = JPATH_ROOT . '/images/n1ed';

        if (!JFolder::exists($n1ed_files)) {
            JFolder::create($n1ed_files);
        }

        $n1ed_tmp = JPATH_ROOT . '/images/n1ed_tmp';

        if (!JFolder::exists($n1ed_tmp)) {
            JFolder::create($n1ed_tmp);
        }

        // Set N1ED as default editor for site
        $config_file = JPATH_CONFIGURATION . '/configuration.php';

        $current_config = & JFactory::getConfig();
        $current_config_data = $current_config->toArray();

        $config = new JRegistry('config');
        $config->loadArray($current_config_data);
        $config->set('editor', 'n1ed');

        chmod($config_file, 644);

        JFile::write(
            $config_file, $config->toString('PHP', array('class' => 'JConfig')
        ));

        chmod($config_file, 444);
    }

}
