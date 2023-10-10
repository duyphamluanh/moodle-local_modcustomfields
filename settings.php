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
 * This adds the custom fields management page.
 *
 * @package     local_modcustomfields
 * @copyright   2020 Daniel Neis Araujo <daniel@adapta.online>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) { // Needs this condition or there is error on login page.

    $ADMIN->add('localplugins', new admin_category('local_modcustomfields',
        get_string('pluginname', 'local_modcustomfields')));

    $ADMIN->add('local_modcustomfields',
        new admin_externalpage('local_modcustomfields_customfield', new lang_string('customfields', 'local_modcustomfields'),
            $CFG->wwwroot . '/local/modcustomfields/customfield.php',
            array('moodle/course:configurecustomfields')
        )
    );
    $setingpage = new admin_settingpage('local_modcustomfields_settingpage', get_string('settings'));

    if ($ADMIN->fulltree) {
        $mods = core_component::get_plugin_list('mod');

        $modarray = [];
        $defaultsettings = [];
        foreach ($mods as $mod => $path) {
            $modarray[$mod] = get_string('modulename', $mod);
        }
        asort($modarray);
        $modulesetting = new admin_setting_configmulticheckbox('local_modcustomfields/disabledmodules',
            get_string('settings:disabledmodules', 'local_modcustomfields'),
            get_string('settings:disabledmodules_desc', 'local_modcustomfields'),
            $defaultsettings,
            $modarray);
        $settings = $modulesetting->get_setting();
        $setingpage->add($modulesetting);
    }
    $ADMIN->add('local_modcustomfields', $setingpage);
}
