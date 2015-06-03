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
 * Prints a particular instance of groupformation
 *
 * @package mod_groupformation
 * @author  
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class RangeInput{
	
	private $category;
	private $qnumber;
	private $question;	
	
	public function __construct(){
		

	}
	
	
	
	public function __printHTML($q, $cat, $qnumb, $hasAnswer){
		$this->question = $q[1];
		$optArray = $q[2];
		$this->category = $cat;
		$this->qnumber = $qnumb;
		
		echo '<tr>';
		echo '<th scope="row">' . $this->question . '</th>';
		
		//TODO @ALL Wer hat hier label eingebaut und wozu? :) Die Zeile dr�ber war schon richtig! by EG
// 		echo '<td> <label for="' . $this->category . $this->qnumber . '">' .
// 				$this->question . '</label> </td>';
		
		$answer = 0;
		if($hasAnswer){
			//$answer ist die position im optionArray bzw. der eingstellte Range von der Antwort
			$answer = $q[3];
		}
	
		echo '<td data-title="0 = ' . $optArray[1] . ', 100 = ' . $optArray[0] . '" class="range">
					<span class="">0</span>
					<input type="range" name="'. $this->category . $this->qnumber .'" min="0" max="100" value="'. $answer .'" />
					<span class="">100</span>
					</td>';
		echo '</tr>';
		
		
	}
}


?>
