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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Bin Distance
 *
 * This class contains an implementation of an distance interface which is based
 * bin distance
 *
 * @package     mod_groupformation
 * @author      Eduard Gallwas, Johannes Konert, Rene Roepke, Nora Wester, Ahmed Zukic
 * @copyright   2015 MoodlePeers
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
if (!defined('MOODLE_INTERNAL')) {
    die ('Direct access to this script is forbidden.'); // It must be included from a Moodle page.
}

require_once($CFG->dirroot . "/mod/groupformation/lib/classes/evaluators/idistance.php");
require_once($CFG->dirroot . "/mod/groupformation/lib/classes/criteria/criterion.php");
require_once($CFG->dirroot . "/mod/groupformation/lib/classes/criteria/many_of_bin_criterion.php");

/**
 * Class mod_groupformation_many_bin_distance
 *
 * @package     mod_groupformation
 * @author      Eduard Gallwas, Johannes Konert, Rene Roepke, Nora Wester, Ahmed Zukic, Stefan Jung
 * @copyright   2015 MoodlePeers
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_groupformation_many_bin_distance implements mod_groupformation_idistance {

    /**
     * normes distance for each dimension (INTERNAL method)
     * return max value is number of dimensions.
     *
     * @param mod_groupformation_criterion $cr1
     * @param mod_groupformation_criterion $cr2
     * @return float|number
     */
    private function get_distance(mod_groupformation_criterion $cr1, mod_groupformation_criterion $cr2) {
        $index = 0;
        $distance = 0.0;

        foreach ($cr1->get_values() as $p1) {
            if ($p1 != null) {
                // get the value of the second participant
                $p2 = $cr2->get_value($index);

                // save the answer String of both participants as arrays
                $answersP1 = explode(",", $p1);
                $answersP2 = explode(",", $p2);

                // computes the intersection of both answer arrays
                $match = array_intersect($answersP1, $answersP2);

                // if there are equal values
                if (!empty($match)) {
                    // return the distance of 1
                    $distance += 1.0;
                }
            }
            $index++;
        }
        // return the distance divided by all values in this criterion
        return $distance / count($cr1->get_values());
    }


    /**
     * Both given crtieria must be of same type and same number of values.
     *
     * @param mod_groupformation_criterion $c1
     * @param mod_groupformation_criterion $c2
     * @return float 1 or 0
     */
    public function normalized_distance(mod_groupformation_criterion $c1, mod_groupformation_criterion $c2) {
        return $this->get_distance($c1, $c2);
    }

}