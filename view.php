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
 * Main view page for the blockchain module.
 *
 * @package   mod_blockchain
 * @copyright 2025 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/mod/blockchain/lib.php');
require_once($CFG->dirroot . '/mod/blockchain/classes/service/document_service.php');
require_once($CFG->dirroot . '/mod/blockchain/classes/service/token_service.php');

$moduleid = required_param('id', PARAM_INT);
$action = optional_param('action', 'dashboard', PARAM_TEXT);

$cm = get_coursemodule_from_id('blockchain', $moduleid, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
$context = context_module::instance($moduleid);

require_login($course, false, $cm);
require_capability('mod/blockchain:view_dashboard', $context);

$isteacher = has_capability('mod/blockchain:grade', $context);

$PAGE->set_url('/mod/blockchain/view.php', ['id' => $moduleid, 'action' => $action]);
$PAGE->set_title(get_string('pluginname', 'mod_blockchain'));
$PAGE->set_heading(get_string('pluginname', 'mod_blockchain'));
$PAGE->set_context($context);

$renderer = $PAGE->get_renderer('mod_blockchain');
$token_service = new mod_blockchain_token_service($moduleid, $USER->id);
$document_service = new mod_blockchain_document_service($moduleid, $USER->id);

$content = '';
switch ($action) {
    case 'dashboard':
        $data = $isteacher ? [
            'total_assignments' => $DB->count_records('blockchain_assignments', ['moduleid' => $moduleid]),
            'pending_submissions' => $DB->count_records_select('blockchain_documents', 'moduleid = ? AND is_assignment = 1 AND NOT EXISTS (SELECT 1 FROM {blockchain_grades} WHERE submissionid = id)', [$moduleid]),
            'violations_count' => $document_service->count_duplicates(),
            'moduleid' => $moduleid
        ] : [
            'pending_assignments' => $DB->count_records_select('blockchain_assignments', 'moduleid = ? AND deadline > ? AND NOT EXISTS (SELECT 1 FROM {blockchain_documents} WHERE assignmentid = id AND userid = ?)', [$moduleid, time(), $USER->id]),
            'token_balance' => $token_service->get_token_balance()['balance'],
            'total_documents' => $DB->count_records('blockchain_documents', ['moduleid' => $moduleid, 'userid' => $USER->id]),
            'moduleid' => $moduleid
        ];
        $content = $isteacher ? $renderer->render_teacher_dashboard($data) : $renderer->render_student_dashboard($data);
        break;

    case 'assignments':
        $assignments = $DB->get_records('blockchain_assignments', ['moduleid' => $moduleid]);
        foreach ($assignments as $a) {
            $a->deadline = userdate($a->deadline);
            $a->submitted = $DB->record_exists('blockchain_documents', ['moduleid' => $moduleid, 'userid' => $USER->id, 'is_assignment' => 1, 'assignmentid' => $a->id]);
            $a->can_submit = !$a->submitted && $a->deadline > time();
            $a->moduleid = $moduleid;
        }
        $content = $renderer->render_assignments(['assignments' => array_values($assignments), 'moduleid' => $moduleid]);
        break;

    case 'documents':
        $documents = $document_service->get_documents();
        $content = $renderer->render_documents(['documents' => $documents, 'moduleid' => $moduleid, 'userid' => $USER->id, 'form' => $renderer->render_from_template('core_filepicker', ['accepted_types' => ['.pdf', '.doc', '.docx'], 'client_id' => 'documentfile'])]);
        break;

    case 'tokens':
        $content = $renderer->render_tokens(['moduleid' => $moduleid, 'userid' => $USER->id]);
        break;

    case 'students':
        $submissions = $DB->get_records_sql("SELECT d.id, d.filename, g.grade, u.firstname, u.lastname
            FROM {blockchain_documents} d
            JOIN {user} u ON d.userid = u.id
            LEFT JOIN {blockchain_grades} g ON g.submissionid = d.id
            WHERE d.moduleid = ? AND d.is_assignment = 1", [$moduleid]);
        foreach ($submissions as $s) {
            $s->user = $s->firstname . ' ' . $s->lastname;
            $s->can_grade = !$s->grade && has_capability('mod/blockchain:grade', $context);
            $s->moduleid = $moduleid;
        }
        $content = $renderer->render_teacher_students(['submissions' => array_values($submissions), 'moduleid' => $moduleid]);
        break;

    case 'edit_assignment':
        $assignmentid = required_param('assignmentid', PARAM_INT);
        $form_data = $DB->get_record('blockchain_assignments', ['id' => $assignmentid]);
        $form_data->moduleid = $moduleid;
        $content = $renderer->render_from_template('core_form', ['form' => new mod_blockchain_assignment_form(null, ['data' => $form_data])]);
        break;
}

echo $OUTPUT->header();
echo $renderer->render_from_template('mod_blockchain/base', ['title' => get_string('pluginname', 'mod_blockchain'), 'lang' => current_language(), 'moduleid' => $moduleid, 'isteacher' => $isteacher, 'content' => $content, 'head' => '', 'javascript' => '']);
echo $OUTPUT->footer();