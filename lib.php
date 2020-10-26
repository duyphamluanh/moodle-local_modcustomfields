<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin lib.
 *
 * @package     local_modcustomfields
 * @copyright   2020 Daniel Neis Araujo <daniel@adapta.online>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


/**
 * Inject the custom fields elements into all moodle module settings forms.
 *
 * @param moodleform $formwrapper The moodle quickforms wrapper object.
 * @param MoodleQuickForm $mform The actual form object (required to modify the form).
 */
function local_modcustomfields_coursemodule_standard_elements($formwrapper, $mform) {
    // Add custom fields to the form.
    $handler = local_modcustomfields\customfield\mod_handler::create();
    $handler->set_parent_context($formwrapper->get_context()); // For course handler only.
    $cm = $formwrapper->get_coursemodule();
    $handler->instance_form_definition($mform, empty($cm->id) ? 0 : $cm->id);
    // Prepare custom fields data.
    $data = $formwrapper->get_current();
    $oldid = $data->id;
    $data->id = $cm->id;
    $handler->instance_form_before_set_data($data);
    $data->id = $oldid;
}

/**
 * Validates the custom fields elements of all moodle module settings forms.
 *
 * @param moodleform $formwrapper The moodle quickforms wrapper object.
 * @param \stdClass $data The form data.
 */
function local_modcustomfields_coursemodule_validation($formwrapper, $data) {
    // Add the custom fields validation.
    $handler = local_modcustomfields\customfield\mod_handler::create();
    return $handler->instance_form_validation($data, []);
}

/**
 * Saves the data of custom fields elements of all moodle module settings forms.
 *
 * @param object $moduleinfo the module info
 * @param object $course the course of the module
 */
function local_modcustomfields_coursemodule_edit_post_actions($moduleinfo, $course) {
    // Save custom fields if there are any of them in the form.
    $handler = local_modcustomfields\customfield\mod_handler::create();
    // Make sure to set the handler's parent context first.
    $context = context_module::instance($moduleinfo->coursemodule);
    $handler->set_parent_context($context);
    // Save the custom field data.
    $moduleinfo->id = $moduleinfo->coursemodule;
    $handler->instance_form_save($moduleinfo, true);
}