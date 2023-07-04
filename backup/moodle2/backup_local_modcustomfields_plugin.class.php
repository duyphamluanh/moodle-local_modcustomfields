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
 * @package local_modcustomfields
 * @author Andrew Hancox <andrewdchancox@googlemail.com>
 * @author Open Source Learning <enquiries@opensourcelearning.co.uk>
 * @link https://opensourcelearning.co.uk
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright 2023, Andrew Hancox
 */

defined('MOODLE_INTERNAL') || die();

class backup_local_modcustomfields_plugin extends backup_local_plugin {

    /**
     * Returns the format information to attach to course element
     */
    protected function define_module_plugin_structure() {
        // Define the virtual plugin element with the condition to fulfill.
        $plugin = $this->get_plugin_element();

        $customfields = new backup_nested_element('customfields');
        $customfield = new backup_nested_element('customfield', array('id'), array(
            'shortname', 'type', 'value', 'valueformat'
        ));

        $plugin->add_child($customfields);
        $customfields->add_child($customfield);

        $handler = \local_modcustomfields\customfield\mod_handler::create();
        $fieldsforbackup = $handler->get_instance_data_for_backup($this->task->get_moduleid());
        $customfield->set_source_array($fieldsforbackup);

        return $plugin;
    }
}
