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
 * Document service class for the blockchain module.
 *
 * @package   mod_blockchain
 * @copyright 2025 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_blockchain\service;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/blockchain/vendor/autoload.php');
use Web3\Web3;
use Web3\Contract;

class document_service {
    private $moduleid;
    private $userid;
    private $web3;
    private $contract;

    /**
     * Constructor.
     *
     * @param int $moduleid The module instance ID.
     * @param int $userid The user ID.
     */
    public function __construct($moduleid, $userid = 0) {
        global $CFG;
        $this->moduleid = $moduleid;
        $this->userid = $userid ?: $USER->id;
        $rpc_url = get_config('mod_blockchain', 'blockchain_rpc_url');
        $this->web3 = new Web3($rpc_url);
        $contract_address = get_config('mod_blockchain', 'document_nft_address');
        $abi_file = $CFG->dirroot . '/mod/blockchain/abi/document_nft.json';
        $abi = file_exists($abi_file) ? json_decode(file_get_contents($abi_file), true) : [];
        $this->contract = new Contract($this->web3->provider, $abi);
        $this->contract->at($contract_address);
    }

    /**
     * Get documents for the user.
     *
     * @return array List of documents.
     */
    public function get_documents() {
        global $DB;
        return $DB->get_records('blockchain_documents', ['moduleid' => $this->moduleid, 'userid' => $this->userid]);
    }

    /**
     * Upload a document.
     *
     * @param stdClass $file File object with name and content.
     * @param bool $is_assignment Whether it’s an assignment submission.
     * @return bool Success.
     */
    public function upload_document($file, $is_assignment = 0) {
        global $DB, $USER;
        $record = new stdClass();
        $record->moduleid = $this->moduleid;
        $record->userid = $USER->id;
        $record->filename = $file->name;
        $record->file_hash = hash('sha256', $file->content);
        $record->is_assignment = $is_assignment;
        $record->timecreated = time();
        $record->timemodified = $record->timecreated;
        if ($this->check_duplicate_submissions($record->file_hash)) {
            return false;
        }
        $documentid = $DB->insert_record('blockchain_documents', $record);
        $fs = get_file_storage();
        $fs->create_file_from_string(['contextid' => context_module::instance($this->moduleid)->id, 'component' => 'mod_blockchain', 'filearea' => 'document', 'itemid' => $documentid], $file->content);
        try {
            $this->contract->send('mint', $USER->id, $record->file_hash, ['from' => $USER->id]);
            $token_id = $this->contract->call('tokenOfOwnerByIndex', $USER->id, 0);
            $DB->set_field('blockchain_documents', 'token_id', $token_id, ['id' => $documentid]);
        } catch (Exception $e) {
            // Fallback to offline mode if configured.
            if (get_config('mod_blockchain', 'offline_fallback')) {
                return true;
            }
            return false;
        }
        return true;
    }

    /**
     * Check for duplicate submissions.
     *
     * @param string $hash The file hash to check.
     * @return bool True if duplicate detected.
     */
    public function check_duplicate_submissions($hash) {
        global $DB;
        return $DB->record_exists_select('blockchain_documents', 'moduleid = ? AND file_hash = ?', [$this->moduleid, $hash]);
    }

    /**
     * Count duplicate submissions.
     *
     * @return int Number of duplicates.
     */
    public function count_duplicates() {
        global $DB;
        $sql = "SELECT COUNT(DISTINCT d1.id) FROM {blockchain_documents} d1
                JOIN {blockchain_documents} d2 ON d1.file_hash = d2.file_hash AND d1.id < d2.id
                WHERE d1.moduleid = ? AND d2.moduleid = ?";
        return $DB->count_records_sql($sql, [$this->moduleid, $this->moduleid]);
    }

    /**
     * Retrieve duplicate submissions with user information.
     *
     * @return array List of duplicate documents with user names.
     */
    public function get_duplicates() {
        global $DB;
        $sql = "SELECT d1.id, d1.filename, u.firstname, u.lastname
                FROM {blockchain_documents} d1
                JOIN {blockchain_documents} d2 ON d1.file_hash = d2.file_hash AND d1.id < d2.id
                JOIN {user} u ON d1.userid = u.id
                WHERE d1.moduleid = ? AND d2.moduleid = ?";
        $records = $DB->get_records_sql($sql, [$this->moduleid, $this->moduleid]);
        $duplicates = [];
        foreach ($records as $r) {
            $duplicates[] = [
                'id' => $r->id,
                'filename' => $r->filename,
                'user' => $r->firstname . ' ' . $r->lastname,
            ];
        }
        return $duplicates;
    }

    /**
     * Get a specific document.
     *
     * @param int $documentid The document ID.
     * @return stdClass Document record.
     */
    public function get_document($documentid) {
        global $DB;
        return $DB->get_record('blockchain_documents', ['id' => $documentid], '*', MUST_EXIST);
    }
}