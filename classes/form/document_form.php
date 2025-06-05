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
 * Document upload form.
 *
 * @package   mod_blockchain
 * @copyright 2025 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * Document upload form class.
 */
class mod_blockchain_document_form extends moodleform {
    /**
     * Define the form elements.
     */
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('hidden', 'moduleid');
        $mform->setType('moduleid', PARAM_INT);

        $mform->addElement('filepicker', 'documentfile', get_string('uploaddocument', 'mod_blockchain'), null, [
            'maxbytes' => 10485760, // 10MB
            'accepted_types' => ['.pdf', '.doc', '.docx']
        ]);
        $mform->addRule('documentfile', null, 'required', null, 'client');

        $mform->addElement('checkbox', 'is_assignment', get_string('isassignment', 'mod_blockchain'));

        $mform->addElement('submit', 'submitbutton', get_string('submit'));
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

        if (empty($files['documentfile'])) {
            $errors['documentfile'] = get_string('filerequired', 'mod_blockchain');
        }

        return $errors;
    }
}