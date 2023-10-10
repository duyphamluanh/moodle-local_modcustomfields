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

namespace local_modcustomfields;

use advanced_testcase;

/**
 * Tests for lib.
 *
 * @package    local_modcustomfields
 * @author     Tomo Tsuyuki <tomotsuyuki@catalyst-au.net>
 * @copyright  2023 Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class lib_test extends advanced_testcase {

    /**
     * Test that course module customfields data is populated correctly.
     * @covers ::local_modcustomfields_coursemodule_standard_elements
     */
    public function test_local_modcustomfields_coursemodule_standard_elements() {
        global $DB, $CFG, $PAGE;

        $this->resetAfterTest();
        $this->setAdminUser();
        $generator = self::getDataGenerator();

        // Setup a customfield for activities.
        $customfieldgenerator = $generator->get_plugin_generator('core_customfield');
        $category = $customfieldgenerator->create_category(['component' => 'local_modcustomfields', 'area' => 'mod']);
        $categoryid = $category->get('id');
        $customfieldgenerator->create_field(['categoryid' => $categoryid, 'name' => 'Custom field text',
            'shortname' => 'cft', 'type' => 'text']);

        // Load assignment form.
        $module = 'assign';
        $modmoodleform = "$CFG->dirroot/mod/{$module}/mod_form.php";
        if (file_exists($modmoodleform)) {
            require_once($modmoodleform);
        } else {
            throw new \moodle_exception('noformdesc');
        }
        $mformclassname = 'mod_' . $module . '_mod_form';

        $course = $generator->create_course();
        $PAGE->set_course($course);

        // Create module, and confirm if the customfield exists.
        $mod = $generator->create_module($module, ['course' => $course->id]);
        [$course, $cm] = get_course_and_cm_from_cmid($mod->cmid);
        list($cm, $context, $mod, $data, $cw) = get_moduleinfo_data($cm, $course);
        $mform = new $mformclassname($data, $cw->section, $cm, $course);
        $form = new \MoodleQuickForm('test', 'post', '');
        local_modcustomfields_coursemodule_standard_elements($mform, $form);
        $this->assertTrue($form->elementExists('customfield_cft'));

        // Set disable module, create module, and confirm if the customfield does not exist.
        set_config('disabledmodules', $module, 'local_modcustomfields');
        $mod = $generator->create_module($module, ['course' => $course->id]);
        [$course, $cm] = get_course_and_cm_from_cmid($mod->cmid);
        list($cm, $context, $mod, $data, $cw) = get_moduleinfo_data($cm, $course);
        $mform = new $mformclassname($data, $cw->section, $cm, $course);
        $form = new \MoodleQuickForm('test', 'post', '');
        local_modcustomfields_coursemodule_standard_elements($mform, $form);
        $this->assertFalse($form->elementExists('customfield_cft'));
    }
}
