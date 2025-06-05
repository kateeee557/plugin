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
 * Renderer for the blockchain module.
 *
 * @package   mod_blockchain
 * @copyright 2025 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Renderer class for the blockchain module.
 */
class mod_blockchain_renderer extends plugin_renderer_base {
    /**
     * Render the student dashboard.
     *
     * @param array $data Dashboard data
     * @return string HTML output
     */
    public function render_student_dashboard($data) {
        return $this->render_from_template('mod_blockchain/student_dashboard', $data);
    }

    /**
     * Render the teacher dashboard.
     *
     * @param array $data Dashboard data
     * @return string HTML output
     */
    public function render_teacher_dashboard($data) {
        return $this->render_from_template('mod_blockchain/teacher_dashboard', $data);
    }

    /**
     * Render the assignments list for students.
     *
     * @param array $data Assignments data
     * @return string HTML output
     */
    public function render_assignments($data) {
        return $this->render_from_template('mod_blockchain/assignments', $data);
    }

    /**
     * Render the assignments list for teachers.
     *
     * @param array $data Assignments data
     * @return string HTML output
     */
    public function render_teacher_assignments($data) {
        return $this->render_from_template('mod_blockchain/teacher_assignments', $data);
    }

    /**
     * Render the documents list.
     *
     * @param array $data Documents data
     * @return string HTML output
     */
    public function render_documents($data) {
        return $this->render_from_template('mod_blockchain/documents', $data);
    }

    /**
     * Render the teacher documents list.
     *
     * @param array $data Documents data
     * @return string HTML output
     */
    public function render_teacher_documents($data) {
        return $this->render_from_template('mod_blockchain/teacher_documents', $data);
    }

    /**
     * Render the tokens overview.
     *
     * @param array $data Tokens data
     * @return string HTML output
     */
    public function render_tokens($data) {
        return $this->render_from_template('mod_blockchain/tokens', $data);
    }

    /**
     * Render the student submissions list for teachers.
     *
     * @param array $data Students data
     * @return string HTML output
     */
    public function render_teacher_students($data) {
        return $this->render_from_template('mod_blockchain/teacher_students', $data);
    }

    /**
     * Render the security check results.
     *
     * @param array $data Security check data
     * @return string HTML output
     */
    public function render_security_check($data) {
        return $this->render_from_template('mod_blockchain/security_check', $data);
    }

    /**
     * Render the blockchain sync results.
     *
     * @param array $data Sync results data
     * @return string HTML output
     */
    public function render_sync_results($data) {
        return $this->render_from_template('mod_blockchain/sync_results', $data);
    }

    /**
     * Render the document verification page.
     *
     * @param array $data Verification data
     * @return string HTML output
     */
    public function render_verify_document($data) {
        return $this->render_from_template('mod_blockchain/verify_document', $data);
    }

    /**
     * Render the grade verification page.
     *
     * @param array $data Verification data
     * @return string HTML output
     */
    public function render_verify_grade($data) {
        return $this->render_from_template('mod_blockchain/verify_grade', $data);
    }

    /**
     * Render the submission view page.
     *
     * @param array $data Submission data
     * @return string HTML output
     */
    public function render_view_submission($data) {
        return $this->render_from_template('mod_blockchain/view_submission', $data);
    }

    /**
     * Render the grade form.
     *
     * @param array $data Grade form data
     * @return string HTML output
     */
    public function render_grade_form($data) {
        return $this->render_from_template('mod_blockchain/grade_form', $data);
    }
}