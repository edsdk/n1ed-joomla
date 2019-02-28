<?php
/**
* N1ED module for Joomla 3
* Developer: EdSDK
* Website: https://n1ed.com/
* License: GNU General Public License Version 2 or later
**/
defined('_JEXEC') or die('Restricted access');

class JFormRuleApiKey extends JFormRule
{
  
  public function test(SimpleXMLElement $element, $value, $group = null, JRegistry $input = null, JForm $form = null)
  {
    $api_key_valid_message = '';

    $api_key = $value;

    if((isset($api_key)) && ($api_key != '')) {
  
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
    }

    if($api_key_valid_message != '')
    {
      $element->addAttribute('message', JText::_($api_key_valid_message));
      return false;
    }

    return true;
  }
}