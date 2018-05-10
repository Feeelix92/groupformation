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
 * Class mod_groupformation_import_export_view_controller
 *
 * @package mod_groupformation
 * @author Rene Roepke
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/groupformation/classes/view_controller/basic_view_controller.php');

class mod_groupformation_import_export_view_controller extends mod_groupformation_basic_view_controller {

    /** @var array Template names */
    protected $templatenames = array('import_export_info');
    /** @var string Title of page */
    protected $title = 'import_export';

    /**
     * mod_groupformation_import_export_view_controller constructor.
     *
     * @param $groupformationid
     * @param $controller
     * @throws coding_exception
     */
    public function __construct($groupformationid, $controller) {
        parent::__construct($groupformationid, $controller);
        $this->title = null;
    }

    /**
     * Renders 'import_export_info' template.
     *
     * @return string
     */
    public function render_import_export_info() {
        $overviewoptions = new mod_groupformation_template_builder();
        $overviewoptions->set_template('import_export_info');
        $overviewoptions->assign_multiple($this->controller->load_info());

        return $overviewoptions->load_template();
    }

    /**
     * Renders 'import_export_statistics' template.
     *
     * @return string
     */
    public function render_import_export_statistics() {
        $overviewoptions = new mod_groupformation_template_builder();
        $overviewoptions->set_template('import_export_statistics');
        $overviewoptions->assign_multiple($this->controller->load_statistics());

        return $overviewoptions->load_template();
    }

    /**
     * Renders 'import_export_topics' template.
     *
     * @return string
     */
    public function render_import_export_topics() {
        $overviewoptions = new mod_groupformation_template_builder();
        $overviewoptions->set_template('import_export_topics');
        $overviewoptions->assign_multiple($this->controller->load_statistics());

        return $overviewoptions->load_template();
    }
}