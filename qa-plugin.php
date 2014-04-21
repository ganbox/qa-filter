<?php

/*
	Plugin Name: Ganbox Filter Plugin
	Plugin URI: 
	Plugin Description: Some extra filters on user inputs in fields of question titile and description
	Plugin Version: 1.0
	Plugin Date: 2014-04-18
	Plugin Author: Ganbox
	Plugin Author URI: http://ganbox.com
	Plugin License: GPLv2
	Plugin Minimum Question2Answer Version: 1.5
	Plugin Update Check URI: 
*/

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../');
	exit;
} 

    qa_register_plugin_module('filter', 'qa-filter-ganbox.php', 'qa_filter_ganbox', 'Ganbox Filter Plugin');
    qa_register_plugin_module('module', 'qa-ganbox-filter-admin-form.php', 'qa_ganbox_filter_admin_form', 'Ganbox Filter Plugin');
    qa_register_plugin_phrases('qa-ganbox-lang-*.php', 'ganbox_filter'); // load language files

/*
	Omit PHP closing tag to help avoid accidental output
*/    
