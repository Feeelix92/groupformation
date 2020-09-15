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
 * Basic algorithm
 *
 * main class to be used for group formations. get an instance of this and run your
 * groupformations using the provided API of this class.
 *
 * @package     mod_groupformation
 * @author      Eduard Gallwas, Johannes Konert, Rene Roepke, Nora Wester, Ahmed Zukic
 * @copyright   2015 MoodlePeers
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . "/mod/groupformation/lib/classes/algorithms/ialgorithm.php");
require_once($CFG->dirroot . "/mod/groupformation/lib/classes/group.php");
require_once($CFG->dirroot . "/mod/groupformation/lib/classes/cohorts/cohort.php");
require_once($CFG->dirroot . "/mod/groupformation/lib/classes/evaluators/ievaluator.php");
require_once($CFG->dirroot . "/mod/groupformation/lib/classes/matchers/imatcher.php");
require_once($CFG->dirroot . "/mod/groupformation/lib/classes/participant.php");
require_once($CFG->dirroot . "/mod/groupformation/lib/classes/statistics.php");

/**
 * Class mod_groupformation_basic_algorithm
 *
 * @package     mod_groupformation
 * @author      Eduard Gallwas, Johannes Konert, Rene Roepke, Nora Wester, Ahmed Zukic
 * @copyright   2015 MoodlePeers
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_groupformation_basic_algorithm implements mod_groupformation_ialgorithm {

    /** @var array This array contains all participants which need to be matched to groups */
    public $participants = array();

    /** @var array This array contains all non-matched participants */
    public $notmatchedparticipants = array();

    /** @var mod_groupformation_cohort This object contains the final cohort with all computed groups and some stats */
    public $cohort;

    /** @var mod_groupformation_evaluator This is the evaluator which is needed to compute performance indices */
    public $evaluator;

    /** @var mod_groupformation_imatcher This is the matcher which is used to match participants to groups */
    public $matcher;

    /** @var mod_groupformation_optimizer This is the optimizer which is used to optimize the computed groups */
    public $optimizer;

    /** @var int This is the number of participants which need to be matched */
    public $numberofparticipants = 0;

    /** @var int This is the maximum group size */
    public $groupsize = 0;

    /** @var int This is the number of groups */
    public $numberofgroups = 0;

    /**
     * mod_groupformation_basic_algorithm constructor.
     *
     * @param array $participants
     * @param mod_groupformation_imatcher $matcher
     * @param int $groupsize
     */
    public function __construct($participants, mod_groupformation_imatcher $matcher, $groupsize) {
        foreach ($participants as $p) {
            $this->participants[] = clone($p);
        }

        $this->matcher = $matcher;
        $this->evaluator = new mod_groupformation_evaluator();
        $this->groupsize = $groupsize;
        $this->init();
    }

    /**
     * Init of algorithm class
     */
    private function init() {
        $this->numberofparticipants = count($this->participants);
        mod_groupformation_group::set_group_members_max_size($this->groupsize);
        mod_groupformation_group::$evaluator = $this->evaluator;
        mod_groupformation_cohort::$evaluator = $this->evaluator;
        // Set cohort: generate empty groups in cohort to fill with participants.
        $this->cohort = new mod_groupformation_cohort(ceil($this->numberofparticipants / $this->groupsize));
        // Set the list of not yet matched participants; the array is automatically copied in PHP.
        $this->notmatchedparticipants = $this->participants;
        $this->numberofgroups = 0;
    }

    /**
     * Adds a participant to the participants which need to be matched
     *
     * @param mod_groupformation_participant $participant
     * @return bool
     */
    public function add_new_participant(mod_groupformation_participant $participant) {
        if ($this->participants == null || in_array($participant, $this->participants, true)) {
            return false;
        }
        // Increase count of participants.
        $this->numberofparticipants++;
        $tmp = ceil($this->numberofparticipants / $this->groupsize);
        // If count of groups changed, then new empty Group.
        if ($tmp != $this->numberofgroups) {
            $this->numberofgroups = $tmp;
            $this->cohort->add_empty_group();
        }
        // Add the new participant to entries.
        $this->participants[] = $participant;
        // Add new participant to the set of not yet matched entries.
        $this->notmatchedparticipants[] = $participant;
        return true;
    }
    /**
     * This method is used to calculate the frequency of each bin
     *
     * @param $participants
     * @return array
     */
    public function calc_manyOfBin_stats($participants){
        $merge_array = array();
        foreach ($participants as $participant){
            foreach ($participant->get_criteria() as $criterion){
                if (strcmp(get_class($criterion), "mod_groupformation_many_of_bin_criterion") == 0){
                    // saves the many-of bin answers in the $answers array
                    $answers = explode(",",$criterion->get_value(0));
                    // saves all answers of all participants in one array
                    $merge_array = array_merge($merge_array, $answers);
                }
            }
        }return array_count_values($merge_array);
    }

    /**
     * This method returns the index of the most common bin in the statistics array
     *
     * @param array $statistic
     * @return int
     */
    public function index_most_common_bin($statistic){
        return array_search(max($statistic), $statistic);
    }

    /**
     * This method returns the index of the least common bin in the statistics array
     *
     * @param array $statistic
     * @return int
     */
    public function index_least_common_bin($statistic){
        return array_search(min($statistic), $statistic);
    }

    /**
     * This method returns an array of three arrays with the participants with common selected bins,
     * the remaining participants and the statistics of the actual participants
     *
     * @param array $participants
     * @param String $common_bin_method
     * @return array
     */
    public function sort_by_bin_answer($participants, $common_bin_method){
        $common_bin_participants = array();
        $remaining_participants = array();
        $statistic = $this->calc_manyOfBin_stats($participants);
        if($common_bin_method == 'most'){
            $common_bin_index = $this->index_most_common_bin($statistic);
        }else{
            $common_bin_index = $this->index_least_common_bin($statistic);
        }
        for($i = 0; $i < count($participants); $i++){
            foreach ($participants[$i]->get_criteria() as $criterion){
                if (strcmp(get_class($criterion), "mod_groupformation_many_of_bin_criterion") == 0){
                    // saves the many-of bin answers in the $answers array
                    $answers = explode(",",$criterion->get_value(0));
                    if(in_array($common_bin_index, $answers)){
                        $common_bin_participants[] = $participants[$i];
                    }else{
                        $remaining_participants[] = $participants[$i];
                    }
                }
            }
        }
        return array('common_bin' => $common_bin_participants, 'remaining'=> $remaining_participants, 'statistic'=>$statistic);
    }

    /**
     *  The main method to call for getting a formation "run" (this takes a while)
     *  Uses the global set matcher to assign every not yet matched participant to a group
     *
     * @return mod_groupformation_cohort
     * @throws Exception
     */
    public function do_one_formation() {
        // checks if the first participant has a many-of bin criterion
        if(mod_groupformation_criterion::has_many_of_bin_criterion()){
            $participants = $this->notmatchedparticipants;
            $statistic = $this->calc_manyOfBin_stats($participants);
            $countofgroups = count($this->cohort->groups);
            $slice_start = 0;

            for ($i = 0; $i < count($statistic); $i++) {
                $sorted_participants = $this->sort_by_bin_answer($participants, 'least');
                $required_groups = ceil(count($sorted_participants['common_bin']) / $this->groupsize);
                $countofgroups -= $required_groups;

                if($countofgroups < 0){
                    for ($j = 0; $j < abs($countofgroups); $j++){
                        $this->cohort->add_empty_group();
                    }
                    $countofgroups = 0;
                }
                $slice_groups = array_slice($this->cohort->groups, $slice_start, $required_groups);
                $slice_start += $required_groups;
                $this->matcher->match_to_groups($sorted_participants['common_bin'], $slice_groups);
                $participants = $sorted_participants['remaining'];
            }
        }else {
            $this->matcher->match_to_groups($this->notmatchedparticipants, $this->cohort->groups);
        }
        $this->cohort->countofgroups = count($this->cohort->groups);
        $this->cohort->whichmatcherused = get_class($this);
        $this->cohort->calculate_cpi();
        return $this->cohort;
    }
}