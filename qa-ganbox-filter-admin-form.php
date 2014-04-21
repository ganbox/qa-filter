<?php

/*
	Question2Answer (c) Gideon Greenspan

	http://www.question2answer.org/

	
	File: qa-plugin/ganbox-filter/qa-ganbox-filter-admin-form.php
	Version: 1.0
	Description: Generic module class for Ganbox Filter Plugin to provide admin form and default option


	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	More about this license: http://www.question2answer.org/license.php
*/

	class qa_ganbox_filter_admin_form {
		
		function option_default($option)
		{
			if ($option=='ganbox_check_question_duplicates_on') return true;
			if ($option=='ganbox_question_mark_on') return true;
			if ($option=='ganbox_check_uppercase_on') return true;
			if ($option=='ganbox_strip_quotes_on') return true;
			if ($option=='ganbox_lat_title_search_on') return false;
			if ($option=='ganbox_cyr_title_search_on') return false;
		}
	
	
		function admin_form(&$qa_content)
		{
			$saved=false;
			
			if (qa_clicked('ganbox_save_button')) {
				qa_opt('ganbox_lat_title_search_on', (int)qa_post_text('ganbox_lat_search_on_field'));
				qa_opt('ganbox_cyr_title_search_on', (int)qa_post_text('ganbox_cyr_search_on_field'));
				qa_opt('ganbox_strip_quotes_on', (int)qa_post_text('ganbox_strip_quotes_on_field'));
				qa_opt('ganbox_check_uppercase_on', (int)qa_post_text('ganbox_check_uppercase_on_field'));
				qa_opt('ganbox_question_mark_on', (int)qa_post_text('ganbox_question_mark_on_field'));
				qa_opt('ganbox_check_question_duplicates_on', (int)qa_post_text('ganbox_check_question_duplicates_on_field'));
				$saved=true;
			}
			

			
			return array(
				'ok' => $saved ? 'Ganbox filter settings saved' : null,
				
				'fields' => array(
					array(
						'label' => 'Check for question title duplicates.',
						'type' => 'checkbox',
						'value' => qa_opt('ganbox_check_question_duplicates_on'),
						'tags' => 'name="ganbox_check_question_duplicates_on_field" id="ganbox_check_question_duplicates_on_field"',
					),
					array(
						'label' => 'Capitalize only the first character of the question title.',
						'type' => 'checkbox',
						'value' => qa_opt('ganbox_check_uppercase_on'),
						'tags' => 'name="ganbox_check_uppercase_on_field" id="ganbox_check_uppercase_on_field"',
					),
					array(
						'label' => 'Add a question mark at the end of the question title if not present.',
						'type' => 'checkbox',
						'value' => qa_opt('ganbox_question_mark_on'),
						'tags' => 'name="ganbox_question_mark_on_field" id="ganbox_question_mark_on_field"',
					),
					array(
						'label' => 'Delete the quotation marks at the start and the end of the sentence.',
						'type' => 'checkbox',
						'value' => qa_opt('ganbox_strip_quotes_on'),
						'tags' => 'name="ganbox_strip_quotes_on_field" id="ganbox_strip_quotes_on_field"',
					),
					array(
						'label' => 'Search for at least one latin letter (A-z) in the question title.',
						'type' => 'checkbox',
						'value' => qa_opt('ganbox_lat_title_search_on'),
						'tags' => 'name="ganbox_lat_search_on_field" id="ganbox_lat_search_on_field"',
					),
					array(
						'label' => 'Search for at least one cyrillic symbol in the question title.',
						'type' => 'checkbox',
						'value' => qa_opt('ganbox_cyr_title_search_on'),
						'tags' => 'name="ganbox_cyr_search_on_field" id="ganbox_cyr_search_on_field"',
					),
					

				),
				
				'buttons' => array(
					array(
						'label' => 'Save Changes',
						'tags' => 'name="ganbox_save_button"',
					),
				),
			);
		}
		
	}
	

/*
	Omit PHP closing tag to help avoid accidental output
*/
