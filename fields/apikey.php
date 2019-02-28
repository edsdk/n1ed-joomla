<?php
/**
 * N1ED module for Joomla 3
 * Developer: EdSDK
 * Website: https://n1ed.com/
 * License: GNU General Public License version 2 or later
**/
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (c) 2019 EdSDK (https://n1ed.com/). All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;
defined('_JEXEC') or die('Restricted access');

/**
 * Form Field class for the N1ED API Key.
 *
 * @package     Joomla.Plugin
 * @subpackage  Editors.none
 * 
 * @copyright   Copyright (c) 2019 EdSDK (https://n1ed.com/). All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
class JFormFieldApikey extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.7.0
	 */
	protected $type = 'Text';

	/**
	 * Name of the layout being used to render the field
	 *
	 * @var    string
	 * @since  3.7
	 */
	protected $layout = 'joomla.form.field.text';

	/**
	 * Method to get certain otherwise inaccessible properties from the form field object.
	 *
	 * @param   string  $name  The property name for which to get the value.
	 *
	 * @return  mixed  The property value or null.
	 *
	 * @since   3.2
	 */
	public function __get($name)
	{
		return parent::__get($name);
	}

	/**
	 * Method to set certain otherwise inaccessible properties of the form field object.
	 *
	 * @param   string  $name   The property name for which to set the value.
	 * @param   mixed   $value  The value of the property.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function __set($name, $value)
	{
		parent::__set($name, $value);
	}

	/**
	 * Method to attach a JForm object to the field.
	 *
	 * @param   SimpleXMLElement  $element  The SimpleXMLElement object representing the `<field>` tag for the form field object.
	 * @param   mixed             $value    The form field value to validate.
	 * @param   string            $group    The field name group control value. This acts as an array container for the field.
	 *                                      For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                      full field name would end up being "bar[foo]".
	 *
	 * @return  boolean  True on success.
	 *
	 * @see     JFormField::setup()
	 * @since   3.2
	 */
	public function setup(SimpleXMLElement $element, $value, $group = null)
	{
		return parent::setup($element, $value, $group);
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   1.7.0
	 */
	protected function getInput()
	{
    $layout_data = $this->getLayoutData();

    if(isset($layout_data['value'])) {

      $api_key_valid_message = '';

      $api_key = $layout_data['value'];

      if($api_key != '') {
    
        $args = http_build_query(
          array(
            'apiKey' => $api_key
          )
        );
        
        $context  = stream_context_create(
          array('http' =>
            array(
              'method'  => 'POST',
              'header'  => 
                            "Content-Type: application/x-www-form-urlencoded\r\n",
              'content' => $args
            )
          )
        );
      
        $result = file_get_contents('https://o.n1ed.com/conf/check', false, $context);
      
        $status_line = $http_response_header[0];
      
        preg_match('{HTTP\/\S*\s(\d{3})}', $status_line, $match);
      
        $status = $match[1];
      
        if ($status !== "200") {
          $api_key_valid_message = 'Check API key error. Response status: ' . $status;
        }
      
        if($result) {
          
          $result = json_decode($result);
      
          if(isset($result->data) && $result->data == false) {
            $api_key_valid_message = 'N1ED configuration not found. Check it on <a href="https://n1ed.com" target="_blank">https://n1ed.com/</a>';
          }
        }

      } else {
        $api_key_valid_message = 'Please specify N1ED API key in order to edit your contents. Obtain it on <a href="https://n1ed.com" target="_blank">https://n1ed.com/</a>';
      }
          
      if($api_key_valid_message != '')
      {
        JFactory::getApplication()->enqueueMessage(JText::_($api_key_valid_message), 'warning');
      }
    }

		return $this->getRenderer($this->layout)->render($layout_data);
	}

	/**
	 * Method to get the data to be passed to the layout for rendering.
	 *
	 * @return  array
	 *
	 * @since 3.7
	 */
	protected function getLayoutData()
	{
		return parent::getLayoutData();
	}
}