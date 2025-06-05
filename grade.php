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
 * Grade submission page for the blockchain module.
 *
 * @package   mod_blockchain
 * @copyright 2025 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/mod/blockchain/lib.php');
require_once($CFG->dirroot . '/mod/blockchain/classes/service/grade_service.php');

$submissionid = required_param('id', PARAM_INT);
$moduleid = required_param('moduleid', PARAM_INT);

$cm = get_coursemodule_from_id('blockchain', $moduleid, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
$context = context_module::instance($moduleid);

require_login($course, false, $cm);
require_capability('mod/blockchain:grade', $context);

$grade_service = new mod_blockchain_grade_service($moduleid);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $grade = required_param('grade', PARAM_FLOAT);
    $grade_service->record_grade($submissionid, $grade);
    redirect(new moodle_url('/mod/blockchain/view.php', ['id' => $moduleid, 'action' => 'students']));
}

$PAGE->set_url('/mod/blockchain/grade.php', ['id' => $submissionid, 'moduleid' => $moduleid]);
$PAGE->set_title(get_string('grade_submission', 'mod_blockchain'));
$PAGE->set_heading(get_string('pluginname', 'mod_blockchain'));
$PAGE->set_context($context);

$renderer = $PAGE->get_renderer('mod_blockchain');
$form_data = new stdClass();
$form_data->submissionid = $submissionid;
$form_data->moduleid = $moduleid;
echo $OUTPUT->header();
echo $renderer->render_grade_form($form_data);
echo $OUTPUT->footer();