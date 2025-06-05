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
 * Token service class for the blockchain module.
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

class token_service {
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
        $contract_address = get_config('mod_blockchain', 'academic_token_address');
        $abi_file = $CFG->dirroot . '/mod/blockchain/abi/academic_token.json';
        $abi = file_exists($abi_file) ? json_decode(file_get_contents($abi_file), true) : [];
        $this->contract = new Contract($this->web3->provider, $abi);
        $this->contract->at($contract_address);
    }

    /**
     * Get token balance for the user.
     *
     * @return array Balance data.
     */
    public function get_token_balance() {
        try {
            $balance = $this->contract->call('balanceOf', $this->userid);
            return ['balance' => $balance, 'success' => true];
        } catch (Exception $e) {
            if (get_config('mod_blockchain', 'offline_fallback')) {
                global $DB;
                $total = $DB->sum_records('blockchain_tokens', 'amount', ['moduleid' => $this->moduleid, 'userid' => $this->userid]);
                return ['balance' => $total ?: 0, 'success' => false];
            }
            return ['balance' => 0, 'success' => false];
        }
    }

    /**
     * Get transaction history for the user.
     *
     * @return array Transaction history.
     */
    public function get_transaction_history() {
        global $DB;
        $transactions = $DB->get_records('blockchain_tokens', ['moduleid' => $this->moduleid, 'userid' => $this->userid]);
        $history = [];
        foreach ($transactions as $tx) {
            $history[] = [
                'amount' => $tx->amount,
                'reason' => $tx->reason,
                'tx_hash' => $tx->tx_hash,
                'date' => userdate($tx->timecreated)
            ];
        }
        return $history;
    }

    /**
     * Reward user with tokens for timely submission.
     *
     * @param int $userid The user ID.
     * @return bool Success.
     */
    public function reward_tokens($userid) {
        global $DB;
        try {
            $this->contract->send('reward', $userid, ['from' => $userid]);
            $record = new stdClass();
            $record->moduleid = $this->moduleid;
            $record->userid = $userid;
            $record->amount = 10; // Standard reward.
            $record->reason = 'Timely Submission';
            $record->timecreated = time();
            $record->timemodified = $record->timecreated;
            $DB->insert_record('blockchain_tokens', $record);
            return true;
        } catch (Exception $e) {
            if (get_config('mod_blockchain', 'offline_fallback')) {
                $record = new stdClass();
                $record->moduleid = $this->moduleid;
                $record->userid = $userid;
                $record->amount = 10;
                $record->reason = 'Timely Submission (Offline)';
                $record->timecreated = time();
                $record->timemodified = $record->timecreated;
                $DB->insert_record('blockchain_tokens', $record);
                return true;
            }
            return false;
        }
    }
}