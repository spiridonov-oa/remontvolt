<?php 
/*
# ------------------------------------------------------------------------
# Extensions for Joomla 2.5.x - Joomla 3.x
# ------------------------------------------------------------------------
# Copyright (C) 2011-2014 Ext-Joom.com. All Rights Reserved.
# @license - PHP files are GNU/GPL V2.
# Author: Ext-Joom.com
# Websites:  http://www.ext-joom.com 
# Date modified: 01/09/2014 - 13:00
# ------------------------------------------------------------------------
*/

// no direct access
defined('_JEXEC') or die;
$document 					= JFactory::getDocument();
$document->addStyleSheet(JURI::base() . 'modules/mod_ext_callback/assets/css/default.css');
//Params
$ext_id						= $params->get('ext_id', '1');
$moduleclass_sfx			= $params->get('moduleclass_sfx');
$ext_my_email				= $params->get('ext_my_email');
$ext_subject_label			= $params->get('ext_subject_label', 'Subject:');
$ext_subject				= $params->get('ext_subject', 'Callback from your site');
$ext_send_label				= $params->get('ext_send_label', 'Send');

$ext_name_label				= $params->get('ext_name_label', 'Name:');
$ext_attribute_name 		= $params->get('ext_attribute_name', 'Your Name');
$ext_phone_label			= $params->get('ext_phone_label', 'Phone:');
$ext_attribute_phone		= $params->get('ext_attribute_phone', '147258369');
$ext_message_label			= $params->get('ext_message_label', 'Message:');
$ext_attribute_message		= $params->get('ext_attribute_message', 'Note... ');
$ext_show_message			= (int)$params->get('ext_show_message', 0);

$ext_send_message			= $params->get('ext_send_message');
$ext_error_field			= $params->get('ext_error_field');
$ext_error_phone_field		= $params->get('ext_error_phone_field', 'The phone must be in the format 123456789');

$errMsg  = '';
$name    = '';
$phone	 = '';
$message = '';

require JModuleHelper::getLayoutPath('mod_ext_callback', $params->get('layout', 'default'));
?>