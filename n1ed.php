<?php
/**
 * N1ED module for Joomla 3
 * Developer: EdSDK
 * Website: https://n1ed.com/
 * License: GNU General Public License Version 2 or later
**/
/**
 * @package     Joomla.Plugin
 * @subpackage  Editors.none
 *
 * @copyright   Copyright (c) 2019 EdSDK (https://n1ed.com/). All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * N1ED Plugin
 *
 * @since  1.5
 */
class plgEditorN1ed extends JPlugin
{
  protected $autoloadLanguage = true;

	/**
	 * Method to handle the onInitEditor event.
	 *  - Initialises the Editor
	 *
	 * @return  void
	 *
	 * @since 1.5
	 */
	public function onInit()
	{
    // Check API key

    if(!$this->params->get('api_key'))
    {

      $plugin_data = $this->getItemByElement('n1ed', 'plugin', 'editors');

      JFactory::getApplication()->enqueueMessage(JText::sprintf('PLG_N1ED_KEY_WARNING', '/administrator/index.php?option=com_plugins&task=plugin.edit&extension_id=' . $plugin_data['extension_id']), 'warning');
		}
		
    if($this->params->get('api_key'))
    {
			// Add N1ED core

			JHtml::_('script', 'https://cdn.n1ed.com/cdn/' . $this->params->get('api_key') . '/n1ed.js?cms=j3', array('version' => 'auto'), array('defer' => 'defer'));
			
			JFactory::getDocument()->addStyleDeclaration("

				/* Hide editor textarea */

				.n1ed-field {
					display: none !important;
				}

				/* Fix preview modal */

				#sbox-window {
					z-index: 70000;
			");

			JFactory::getDocument()->addScriptDeclaration("

				(function($, Joomla, window, document){
					
					$(document).ready(function(){

						$('.n1ed-field:not(.n1ed-field-processed)').each(function(i) {
						
							var editor_id      = $(this).attr('id');
							var editor_element = $(this).get(0);
	
							Joomla.editors.instances[editor_id] = {
								'id': 			editor_id,
								'element':  editor_element,
								'getValue': function ()     { return this.element.value; },
								'setValue': function (text) { return this.element.value = text; },
							};

							$(this).addClass('n1ed-field-processed');
						});
					});

				}(jQuery, Joomla, window, document));
			");
    }
	}

  /**
	 * Get the editor content.
	 *
	 * @param   string  $id  The id of the editor field.
	 *
	 * @return  string
	 *
	 * @deprecated 4.0 Use directly the returned code
	 */
	public function onGetContent($id)
	{
		return 'Joomla.editors.instances[' . json_encode($id) . '].getValue();';
	}

	/**
	 * Set the editor content.
	 *
	 * @param   string  $id    The id of the editor field.
	 * @param   string  $html  The content to set.
	 *
	 * @return  string
	 *
	 * @deprecated 4.0 Use directly the returned code
	 */
	public function onSetContent($id, $html)
	{
		return 'Joomla.editors.instances[' . json_encode($id) . '].setValue(' . json_encode($html) . ');';
	}

	/**
	 * Display the editor area.
	 *
	 * @param   string   $name     The control name.
	 * @param   string   $content  The contents of the text area.
	 * @param   string   $width    The width of the text area (px or %).
	 * @param   string   $height   The height of the text area (px or %).
	 * @param   integer  $col      The number of columns for the textarea.
	 * @param   integer  $row      The number of rows for the textarea.
	 * @param   boolean  $buttons  True and the editor buttons will be displayed.
	 * @param   string   $id       An optional ID for the textarea (note: since 1.6). If not supplied the name is used.
	 * @param   string   $asset    The object asset
	 * @param   object   $author   The author.
	 * @param   array    $params   Associative array of editor parameters.
	 *
	 * @return  string
	 */
	public function onDisplay($name, $content, $width, $height, $col, $row, $buttons = true, $id = null, $asset = null, $author = null, $params = array())
	{
		if (empty($id))
		{
			$id = $name;
		}
        
		$editor = '
								<div class="n1ed-field-wrapper">
									<textarea class="n1ed-field" n1ed="true" name="' . $name . '" id="' . $id . '">' . $content . '</textarea>
								</div>	
							';
    
    // Add "Preview" button.

		// $editor .= '<div class="xtd-buttons-wrapper">'
		// 							. $this->_displayButtons($name, $buttons, $asset, $author) . 
		// 						'</div>';
		
		return $editor;
	}
	
	/**
	 * Displays the editor buttons.
	 *
	 * @param   string  $name     The control name.
	 * @param   mixed   $buttons  [array with button objects | boolean true to display buttons]
	 * @param   string  $asset    The object asset
	 * @param   object  $author   The author.
	 *
	 * @return  void|string HTML
	 */
	public function _displayButtons($name, $buttons, $asset, $author)
	{
		if (is_array($buttons) || (is_bool($buttons) && $buttons))
		{
			$buttons = $this->_subject->getButtons($name, $buttons, $asset, $author);

			return JLayoutHelper::render('joomla.editors.buttons', $buttons);
		}
	}
	
  /**
   * Helper for get info about extensions.
   * 
   * @param string $element The extension name.
   * @param string $type    The extension type (plugin, module, component).
   * @param string $folder  The extension folder (editors, captcha...).
   */
  private function getItemByElement($element, $type, $folder = null)
  {
    $dbo = JFactory::getDbo();
    $query = $dbo->getQuery(true);
    $query->select('extension_id, manifest_cache');
    $query->from('#__extensions');

    $query->where('element = '.$dbo->quote($element));
    $query->where('type = '.$dbo->quote($type));

    if($type == 'plugin' && !empty($folder))
    {
      $query->where('folder = '.$dbo->quote($folder));
    }

    $dbo->setQuery($query);
    $extensionRecord = $dbo->loadAssoc();

    if(!is_null($extensionRecord))
    {
      $manifestData = json_decode($extensionRecord['manifest_cache']);
      $extensionRecord['manifest'] = $manifestData;
    }

    return $extensionRecord;
	}
	
}