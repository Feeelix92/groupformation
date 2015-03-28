<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.
/**
 * interface betweeen DB and Plugin
 *
 * @package mod_groupformation
 * @copyright 2015 Nora Wester
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

//defined('MOODLE_INTERNAL') || die();  -> template
//namespace mod_groupformation\classes\lecturer_settings;

if (!defined('MOODLE_INTERNAL')) {
	die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}
	
	//require_once 'storage_manager.php';
	require_once(dirname(__FILE__).'/storage_manager.php');
	require_once(dirname(__FILE__).'/xml_loader.php');

	
	class mod_groupformation_setting_manager {

		private $groupformationid;
		private $szenario;
		private $topicValues;
		private $knowledgeValues;
		private $store;
		
		private $xmlLoader;
		
		private $number = 0;

		/**
		 * 
		 * @param unknown $groupformationid
		 * @param unknown $szenario
		 * @param  $topicValues
		 * @param array $knowledgeValues
		 */
		public function __construct($groupformationid, $szenario, array $topicValues, array $knowledgeValues){
			$this->groupformationid = $groupformationid;
			$this->szenario = $szenario;
			$this->knowledgeValues = $knowledgeValues;
			$this->topicValues = $topicValues;
			$this->store = new mod_groupformation_storage_manager($groupformationid);
			$this->xmlLoader = new mod_groupformation_xml_loader();
			$this->xmlLoader->setStore($this->store);
		}
		
		/**
		 * 
		 * @param $german indicates whether the question should be in german
		 */
		public function create_Questions($german){
			//'Sprache f�r die Gruppenarbeit / Language for Team Work'
			
			if($german){
				$languageQ = "Bitte waehlen Sie, in welcher Sprache es Ihnen moeglich ist mit ihrer Gruppe zu kommunizieren";
				$options = array ("deutsch", "deutsch/englisch", "englisch");
			}
			else {
				$languageQ = "Please select in which languages you can possibly communicate with your team.";
				$options = array ("german", "german/english", "english");
			}
			
			$question = array('type' => 'dropdown',
					'question' => $languageQ,
					'options' => $options
			);
			
			
			$this->store->add_Question($question);
			$this->number++;
			
			
			if($german){
				$options = array ("sehr gut","","","","nicht vorhanden");
			} else {
				$options = array ("excellent", "", "", "","none");
			}
			
			
			if($this->szenario != 'seminar'){
				foreach($this->knowledgeValues as $knowledge){
					if(strlen($knowledge) != 0){
						if($german)
							$knowledgename = "Wie schaetzen Sie ihr persoenliches Vorwissen in $knowledge ein?";
						else $knowledgename = "";
				
						$question = array('type' => 'dropdown',
								'question' => $knowledgename,
								'options' => $options
						);
					
						$this->store->add_question($question);
						$this->number++;
					}
			
				}
			}
			//Bitte sortieren Sie die zur Wahl stehenden Themen entsprechend Ihrer Pr�ferenz, beginnend mit Ihrem bevorzugten Thema.
			//Please sort topics available according to your preference, starting with your prefered topic.
			foreach($this->topicValues as $topic){
				
				if(strlen($topic) != 0){
					if($german)
						$topicname = "Wie gross ist Ihr Interesse an $topic";
					else $topicname = "";
				
					$question = array('type' => 'dragdrop',
							'question' => $topicname,
							'options' => $options
					);
				
					$this->store->add_question($question);
					$this->number++;
				}
			}
			
			$empty = $this->store->catalogTableNotSet();
			var_dump($empty);
			if($empty){
				$this->xmlLoader->saveData('team');
			}
			
// 			$temp = $this->xmlLoader->saveData('character', $german, $this->number);
// 			$this->number = $this->number + $temp;
			
// 			//je nach szenario andere Werte und Fragen
// 			if($this->szenario == 'project'){
// 				$temp = $this->xmlLoader->saveData('motivation', $german, $this->number);
// 				$this->number = $this->number + $temp;
// 			} 
			
// 			if($this->szenario == 'homework'){
// 				$temp = $this->xmlLoader->saveData('learning', $german, $this->number);
// 				$this->number = $this->number + $temp;
// 			} 
		}
		
		public function save_settings(){
			
			 $this->store->add_settings($this->knowledgeValues, $this->szenario, $this->topicValues, $this->number);
		}
	}