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
 * Moodle blockchain synchronization page.
 *
 * @package   mod_blockchain
 * @copyright 2025 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/mod/blockchain/lib.php');
require_once($CFG->dirroot . '/mod/blockchain/classes/service/blockchain_service.php');

$moduleid = required_param('id', PARAM_INT);

$cm = get_coursemodule_from_id('blockchain', $moduleid, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
$context = context_module::instance($moduleid);

require_login($course, false, $cm);
require_capability('mod/blockchain:grade', $context);

$service = new mod_blockchain_blockchain_service($moduleid);
$results = $service->sync_with_blockchain();

$PAGE->set_url('/mod/blockchain/sync_moodle.php', ['id' => $moduleid]);
$PAGE->set_title(get_string('sync_results', 'mod_blockchain'));
$PAGE->set_heading(get_string('pluginname', 'mod_blockchain'));
$PAGE->set_context($context);

$renderer = $PAGE->get_renderer('mod_blockchain');
echo $OUTPUT->header();
echo $renderer->render_sync_results(['documents_synced' => $results['documents'], 'tokens_synced' => $results['tokens'], 'success' => $results['success'], 'errors' => $results['errors'], 'moduleid' => $moduleid]);
echo $OUTPUT->footer();