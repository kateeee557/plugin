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
 * Library functions for the blockchain module.
 *
 * @package   mod_blockchain
 * @copyright 2025 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Add instance to course.
 *
 * @param stdClass $data
 * @param mod_blockchain_mod_form $mform
 * @return int New instance ID
 */
function blockchain_add_instance(stdClass $data, mod_blockchain_mod_form $mform = null) {
    global $DB;
    $data->timecreated = time();
    $data->timemodified = $data->timecreated;
    return $DB->insert_record('blockchain', $data);
}

/**
 * Update instance.
 *
 * @param stdClass $data
 * @param mod_blockchain_mod_form $mform
 * @return bool Success
 */
function blockchain_update_instance(stdClass $data, mod_blockchain_mod_form $mform = null) {
    global $DB;
    $data->timemodified = time();
    $data->id = $data->instance;
    return $DB->update_record('blockchain', $data);
}

/**
 * Delete instance.
 *
 * @param int $id
 * @return bool Success
 */
function blockchain_delete_instance($id) {
    global $DB;
    $cm = $DB->get_record('course_modules', ['instance' => $id, 'module' => $DB->get_field('modules', 'id', ['name' => 'blockchain'])], '*', MUST_EXIST);
    $DB->delete_records('blockchain', ['id' => $id]);
    return true;
}

/**
 * Get instance data.
 *
 * @param stdClass $cm
 * @return mixed Instance data or false
 */
function blockchain_get_coursemodule_info($cm) {
    global $DB;
    $instance = $DB->get_record('blockchain', ['id' => $cm->instance], '*', MUST_EXIST);
    return (object)['name' => $instance->name];
}