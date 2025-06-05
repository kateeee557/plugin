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
 * Assignment form for creating and editing assignments.
 *
 * @package   mod_blockchain
 * @copyright 2025 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * Assignment form class.
 */
class mod_blockchain_assignment_form extends moodleform {
    /**
     * Define the form elements.
     */
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'moduleid');
        $mform->setType('moduleid', PARAM_INT);

        $mform->addElement('text', 'title', get_string('title', 'mod_blockchain'), ['size' => '64']);
        $mform->setType('title', PARAM_TEXT);
        $mform->addRule('title', null, 'required', null, 'client');

        $mform->addElement('textarea', 'description', get_string('description', 'mod_blockchain'), ['rows' => 5, 'cols' => 60]);
        $mform->setType('description', PARAM_TEXT);

        $mform->addElement('date_time_selector', 'deadline', get_string('deadline', 'mod_blockchain'));
        $mform->setDefault('deadline', time() + WEEKSECS);

        $mform->addElement('submit', 'submitbutton', get_string('savechanges'));
    }

    /**
     * Validate the form data.
     *
     * @param array $data Form data
     * @param array $files Uploaded files
     * @return array List of errors
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        if ($data['deadline'] <= time()) {
            $errors['deadline'] = get_string('invaliddeadline', 'mod_blockchain');
        }

        return $errors;
    }
}