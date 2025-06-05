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
 * Upgrade script for the blockchain module.
 *
 * @package   mod_blockchain
 * @copyright 2025 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Upgrade function for the blockchain module.
 *
 * @param int $oldversion The version we are upgrading from
 * @return bool True if upgrade successful
 */
function xmldb_blockchain_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2025060400) {
        // Define the blockchain_assignments table (added in initial version).
        $table = new xmldb_table('blockchain_assignments');
        $field = new xmldb_field('description', XMLDB_TYPE_TEXT, null, null, null, null, null, 'title');

        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Update the version.
        upgrade_mod_savepoint(true, 2025060400, 'blockchain');
    }

    return true;
}