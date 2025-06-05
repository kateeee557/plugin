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
 * Grade service class for the blockchain module.
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

class grade_service {
    private $moduleid;
    private $web3;
    private $contract;

    /**
     * Constructor.
     *
     * @param int $moduleid The module instance ID.
     */
    public function __construct($moduleid) {
        global $CFG;
        $this->moduleid = $moduleid;
        $rpc_url = get_config('mod_blockchain', 'blockchain_rpc_url');
        $this->web3 = new Web3($rpc_url);
        $contract_address = get_config('mod_blockchain', 'user_token_tracker_address');
        $abi_file = $CFG->dirroot . '/mod/blockchain/abi/user_token_tracker.json';
        $abi = file_exists($abi_file) ? json_decode(file_get_contents($abi_file), true) : [];
        $this->contract = new Contract($this->web3->provider, $abi);
        $this->contract->at($contract_address);
    }

    /**
     * Record a grade on the blockchain.
     *
     * @param int $submissionid The submission ID.
     * @param float $grade The grade value.
     * @return bool Success.
     */
    public function record_grade($submissionid, $grade) {
        global $DB, $USER;
        $grade_hash = hash('sha256', $grade . $submissionid . time());
        $record = new stdClass();
        $record->submissionid = $submissionid;
        $record->grade = $grade;
        $record->grade_hash = $grade_hash;
        $record->timecreated = time();
        $record->timemodified = $record->timecreated;
        $gradeid = $DB->insert_record('blockchain_grades', $record);
        try {
            $tx_hash = $this->contract->send('recordTransaction', $USER->id, 0, 'Grade Recorded', ['from' => $USER->id]);
            $DB->set_field('blockchain_grades', 'tx_hash', $tx_hash, ['id' => $gradeid]);
        } catch (Exception $e) {
            if (get_config('mod_blockchain', 'offline_fallback')) {
                return true;
            }
            $DB->delete_records('blockchain_grades', ['id' => $gradeid]);
            return false;
        }
        return true;
    }

    /**
     * Get a specific grade.
     *
     * @param int $gradeid The grade ID.
     * @return stdClass Grade record.
     */
    public function get_grade($gradeid) {
        global $DB;
        return $DB->get_record('blockchain_grades', ['id' => $gradeid], '*', MUST_EXIST);
    }
}