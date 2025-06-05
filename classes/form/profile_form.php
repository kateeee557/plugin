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
 * Profile form for user profile management.
 *
 * @package   mod_blockchain
 * @copyright 2025 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * Profile form class.
 */
class mod_blockchain_profile_form extends moodleform {
    /**
     * Define the form elements.
     */
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('text', 'displayname', get_string('displayname', 'mod_blockchain'), ['size' => '64']);
        $mform->setType('displayname', PARAM_TEXT);
        $mform->addRule('displayname', null, 'required', null, 'client');

        $mform->addElement('static', 'wallet_address', get_string('wallet_address', 'mod_blockchain'), '');
        $mform->setType('wallet_address', PARAM_TEXT);

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

        if (strlen($data['displayname']) > 255) {
            $errors['displayname'] = get_string('displaynametoolong', 'mod_blockchain');
        }

        return $errors;
    }
}