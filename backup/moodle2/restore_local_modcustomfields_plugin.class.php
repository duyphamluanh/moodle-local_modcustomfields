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
 * @package local_modcustomfieldsactivitycards
 * @author Andrew Hancox <andrewdchancox@googlemail.com>
 * @author Open Source Learning <enquiries@opensourcelearning.co.uk>
 * @link https://opensourcelearning.co.uk
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright 2023, Andrew Hancox
 */

defined('MOODLE_INTERNAL') || die();


class restore_local_modcustomfields_plugin extends restore_local_plugin {

    public function define_module_plugin_structure() {
        return [new restore_path_element('customfield', '/module/customfields/customfield')];
    }

    /**
     * Process custom fields
     *
     * @param array $data
     */
    public function process_customfield($data) {
        $handler = \local_modcustomfields\customfield\mod_handler::create();
        $handler->restore_instance_data_from_backup($this->task, $data);
    }
}
