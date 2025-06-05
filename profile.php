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
 * Profile management page for the blockchain module.
 *
 * @package   mod_blockchain
 * @copyright 2025 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/mod/blockchain/lib.php');
require_once($CFG->dirroot . '/mod/blockchain/classes/form/profile_form.php');

$moduleid = required_param('id', PARAM_INT);

$cm = get_coursemodule_from_id('blockchain', $moduleid, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
$context = context_module::instance($moduleid);

require_login($course, false, $cm);

$PAGE->set_url('/mod/blockchain/profile.php', ['id' => $moduleid]);
$PAGE->set_title(get_string('profile', 'mod_blockchain'));
$PAGE->set_heading(get_string('pluginname', 'mod_blockchain'));
$PAGE->set_context($context);

$wallet = $DB->get_record('blockchain_wallets', ['userid' => $USER->id]);
$data = new stdClass();
$data->id = $USER->id;
$data->displayname = $USER->firstname . ' ' . $USER->lastname;
$data->wallet_address = $wallet ? $wallet->address : get_string('nowallet', 'mod_blockchain');

$mform = new mod_blockchain_profile_form(null, ['data' => $data]);
if ($mform->is_cancelled()) {
    redirect(new moodle_url('/mod/blockchain/view.php', ['id' => $moduleid]));
} else if ($fromform = $mform->get_data()) {
    $user = $DB->get_record('user', ['id' => $USER->id]);
    $user->firstname = substr($fromform->displayname, 0, strpos($fromform->displayname, ' '));
    $user->lastname = substr($fromform->displayname, strpos($fromform->displayname, ' ') + 1);
    $DB->update_record('user', $user);
    redirect(new moodle_url('/mod/blockchain/view.php', ['id' => $moduleid]), get_string('profilesuccess', 'mod_blockchain'));
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();