<?php

/*
	Question2Answer (c) Gideon Greenspan

	http://www.question2answer.org/

	
	File: qa-plugin/ganbox-filter/qa-filter-ganbox.php
	Version: 1.0
	Description: Page module class for Ganbox Filter Plugin


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

    class qa_filter_ganbox {

        var $directory;
        function load_module($directory, $urltoroot)
        {
            $this->directory=$directory;
        }

        function filter_question(&$question, &$errors, $oldquestion) {
            $question['title'] = preg_replace('~(\s|&nbsp;)+~', ' ', $question['title']); // several spaces condensed to one - text: (Big '     ' space) becomes: (Big ' ' space)
            $question['title'] = trim($question['title']);

            if(qa_opt('ganbox_cyr_title_search_on')){
                // if question title do not contain at least one cyrillic letter
                if(!preg_match('|[АБВГДЕЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЬЮЯабвгдежзийклмнопрстуфхцчшщъьюя]|', $question['title'])){
                    if(empty($errors['title'])){
                        $errors['title'] = qa_lang('ganbox_filter/ganbox_err_no_cyr');
                    }else{
                        $errors['title'] .=' '.qa_lang('ganbox_filter/ganbox_err_type_cyr');
                    }
                }
            }

            if(qa_opt('ganbox_lat_title_search_on')){
                // if question title do not contain at least one latin letter
                if(!preg_match('|[A-Za-z]|', $question['title'])){
                    $errors['title'] = (empty($errors['title'])?'':$errors['title'].' ').qa_lang('ganbox_filter/ganbox_err_no_lat');
                }
            }

            if(qa_opt('ganbox_strip_quotes_on')){
                // Delete the different quotation marks at the start and the end of the question - ("Test 'text'") becomes: (Test 'text')
                // replace Microsoft Word version of single  and double quotations marks (“ ” ‘ ’) with  regular quotes (' and ")
                $quotes = array(
                    "\xC2\xAB"     => '"', // « (U+00AB) in UTF-8
                    "\xC2\xBB"     => '"', // » (U+00BB) in UTF-8
                    "\xE2\x80\x98" => "'", // ‘ (U+2018) in UTF-8
                    "\xE2\x80\x99" => "'", // ’ (U+2019) in UTF-8
                    "\xE2\x80\x9A" => "'", // ‚ (U+201A) in UTF-8
                    "\xE2\x80\x9B" => "'", // ‛ (U+201B) in UTF-8
                    "\xE2\x80\x9C" => '"', // “ (U+201C) in UTF-8
                    "\xE2\x80\x9D" => '"', // ” (U+201D) in UTF-8
                    "\xE2\x80\x9E" => '"', // „ (U+201E) in UTF-8
                    "\xE2\x80\x9F" => '"', // ‟ (U+201F) in UTF-8
                    "\xE2\x80\xB9" => "'", // ‹ (U+2039) in UTF-8
                    "\xE2\x80\xBA" => "'", // › (U+203A) in UTF-8
                );
                $question['title'] = strtr($question['title'], $quotes);
                
                $first = mb_substr ($question['title'], 0, 1, 'UTF-8'); // first symbol
                $last = mb_substr ($question['title'], -1, 1, 'UTF-8'); // last symbol

                if($first===$last){
                    if($first === '"') $question['title'] = trim($question['title'],'"');
                    if($first === "'") $question['title'] = trim($question['title'],"'");
                }
                $question['title'] = trim($question['title']); // String: (" text ") become only: (text)
            }

            if(qa_opt('ganbox_check_uppercase_on')){
                // if all letters are capitalized
                if(mb_strtoupper($question['title'], 'UTF-8') === $question['title']){
                    $errors['title'] = (empty($errors['title'])?'':$errors['title'].' ').qa_lang('ganbox_filter/ganbox_not_all_cap'); 
                }else{
                    // if not start with capital letter (or digit)
                    $first = mb_substr ($question['title'], 0, 1, 'UTF-8'); // first symbol (again because striped quotes)
                    if(!is_numeric($first) && mb_strtolower($first, 'UTF-8') == $first){
                        $errors['title'] = (empty($errors['title'])?'':$errors['title'].' ').qa_lang('ganbox_filter/ganbox_first_cap');
                    }
                }
            }

            if(qa_opt('ganbox_question_mark_on')){
                // if not another errors 
                if (empty($errors['title'])){
                    if(mb_substr($question['title'], -1, 1, 'UTF-8') != '?'){ // and question title do not end with question mark
                        $question['title'].='?'; // add a question mark at the end of title (without error)
                    }else{
                        $question['title'] = rtrim($question['title'],'?').'?'; // no more than one question mark at the end
                    }
                }
            }

            if(qa_opt('ganbox_check_question_duplicates_on')){
                // check if question title already exists, prevent duplicate
                if (empty($errors['title'])) { // if not another errors
                    $quTitleExists = qa_db_read_one_value( qa_db_query_sub('SELECT title FROM `^posts` WHERE title = # AND type = "Q" LIMIT 1', $question['title']), true);
                    if( $quTitleExists && is_null($oldquestion) ) {
                        $errors['title'] = (empty($errors['title'])?'':$errors['title'].' ').qa_lang('ganbox_filter/ganbox_duplicate');
                    }
                }
            }
        } 
        //-------------------------------------------

        function filter_answer(&$answer, &$errors, $question, $oldanswer) {
            // replace empty p tags causing unnecessary line breaks, such as <p>&nbsp;</p>
            $answer['content'] = preg_replace('~\s?<p>(\s|&nbsp;)+</p>\s?~', '', $answer['content']);
        }
        //-------------------------------------------

        function filter_comment(&$comment, &$errors, $question, $parent, $oldcomment){}
    }

/*
	Omit PHP closing tag to help avoid accidental output
*/
