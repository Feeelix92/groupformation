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

		
		$answer = 0;
		if($hasAnswer && $q[3]!= -1){
			//$answer ist die position im optionArray bzw. der eingstellte Range von der Antwort
			$answer = $q[3];
            echo '<tr>';
            echo '<th scope="row">' . $this->question . '</th>';
		}else{
            echo '<tr class="noAnswer">';
            echo '<th scope="row">' . $this->question . '</th>';
        }
	
		echo '<td data-title="0 = ' . $optArray[1] . ', 100 = ' . $optArray[0] . '" class="range">
					<span class="">0</span>
					<input type="range" name="'. $this->category . $this->qnumber .'" class="gf_range_inputs" min="0" max="100" value="'. $answer .'" />
					<span class="">100</span>
					<input type="text" name="'. $this->category . $this->qnumber .'_valid" value="0" />
					</td>';
		echo '</tr>';
		
		
	}
}


?>
