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
 * Submission page for the blockchain module.
 *
 * @package   mod_blockchain
 * @copyright 2025 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/mod/blockchain/lib.php');
require_once($CFG->dirroot . '/mod/blockchain/classes/form/submission_form.php');

$assignmentid = required_param('assignmentid', PARAM_INT);
$moduleid = required_param('moduleid', PARAM_INT);

$cm = get_coursemodule_from_id('blockchain', $moduleid, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
$context = context_module::instance($moduleid);

require_login($course, false, $cm);
require_capability('mod/blockchain:submit_assignment', $context);

$PAGE->set_url('/mod/blockchain/submit.php', ['assignmentid' => $assignmentid, 'moduleid' => $moduleid]);
$PAGE->set_title(get_string('submit_assignment', 'mod_blockchain'));
$PAGE->set_heading(get_string('pluginname', 'mod_blockchain'));
$PAGE->set_context($context);

$mform = new mod_blockchain_submission_form(null, ['assignmentid' => $assignmentid, 'moduleid' => $moduleid]);
if ($mform->is_cancelled()) {
    redirect(new moodle_url('/mod/blockchain/view.php', ['id' => $moduleid, 'action' => 'assignments']));
} else if ($fromform = $mform->get_data()) {
    $fs = get_file_storage();
    $files = $mform->get_files();
    foreach ($files['submissionfile'] as $file) {
        $record = new stdClass();
        $record->moduleid = $moduleid;
        $record->userid = $USER->id;
        $record->filename = $file->get_filename();
        $record->file_hash = hash_file('sha256', $file->get_filepath() . $file->get_filename());
        $record->is_assignment = 1;
        $record->timecreated = time();
        $record->timemodified = $record->timecreated;
        $documentid = $DB->insert_record('blockchain_documents', $record);
        $fs->create_file_from_storedfile(['contextid' => $context->id, 'component' => 'mod_blockchain', 'filearea' => 'submission', 'itemid' => $documentid], $file);
    }
    redirect(new moodle_url('/mod/blockchain/view.php', ['id' => $moduleid, 'action' => 'assignments']));
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();