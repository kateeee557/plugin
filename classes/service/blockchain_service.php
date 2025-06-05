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
 * Blockchain service class for the blockchain module.
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

class blockchain_service {
    private $moduleid;
    private $web3;
    private $contract_addresses;

    /**
     * Constructor.
     *
     * @param int $moduleid The module instance ID.
     */
    public function __construct($moduleid) {
        global $CFG;
        $this->moduleid = $moduleid;
        $this->contract_addresses = [
            'academic_token' => get_config('mod_blockchain', 'academic_token_address'),
            'document_nft' => get_config('mod_blockchain', 'document_nft_address'),
            'user_address_factory' => get_config('mod_blockchain', 'user_address_factory_address'),
            'user_token_tracker' => get_config('mod_blockchain', 'user_token_tracker_address')
        ];
        $rpc_url = get_config('mod_blockchain', 'blockchain_rpc_url');
        $this->web3 = new Web3($rpc_url);
        $this->load_contract_abis();
    }

    /**
     * Load contract ABIs from files.
     */
    private function load_contract_abis() {
        $abi_files = [
            'academic_token' => $CFG->dirroot . '/mod/blockchain/abi/academic_token.json',
            'document_nft' => $CFG->dirroot . '/mod/blockchain/abi/document_nft.json',
            'user_address_factory' => $CFG->dirroot . '/mod/blockchain/abi/user_address_factory.json',
            'user_token_tracker' => $CFG->dirroot . '/mod/blockchain/abi/user_token_tracker.json'
        ];
        foreach ($abi_files as $key => $file) {
            if (file_exists($file)) {
                $this->contract_abis[$key] = json_decode(file_get_contents($file), true);
            }
        }
    }

    /**
     * Synchronize Moodle data with blockchain.
     *
     * @return array Sync results.
     */
    public function sync_with_blockchain() {
        global $DB;
        $results = ['documents' => 0, 'tokens' => 0, 'success' => true, 'errors' => []];

        // Sync documents (mint NFTs if not minted).
        $documents = $DB->get_records('blockchain_documents', ['moduleid' => $this->moduleid, 'token_id' => null]);
        $contract = new Contract($this->web3->provider, $this->contract_abis['document_nft']);
        foreach ($documents as $doc) {
            try {
                $hash = $doc->file_hash;
                $contract->at($this->contract_addresses['document_nft'])->send('mint', $doc->userid, $hash, ['from' => $doc->userid]);
                $token_id = $contract->at($this->contract_addresses['document_nft'])->call('tokenOfOwnerByIndex', $doc->userid, 0);
                $DB->set_field('blockchain_documents', 'token_id', $token_id, ['id' => $doc->id]);
                $results['documents']++;
            } catch (Exception $e) {
                $results['errors'][] = get_string('syncerror_document', 'mod_blockchain', $doc->id);
                $results['success'] = false;
            }
        }

        // Sync tokens (record transactions if not recorded).
        $tokens = $DB->get_records('blockchain_tokens', ['moduleid' => $this->moduleid, 'tx_hash' => null]);
        $contract = new Contract($this->web3->provider, $this->contract_abis['user_token_tracker']);
        foreach ($tokens as $token) {
            try {
                $tx_hash = $contract->at($this->contract_addresses['user_token_tracker'])->send('recordTransaction', $token->userid, $token->amount, $token->reason, ['from' => $token->userid]);
                $DB->set_field('blockchain_tokens', 'tx_hash', $tx_hash, ['id' => $token->id]);
                $results['tokens']++;
            } catch (Exception $e) {
                $results['errors'][] = get_string('syncerror_token', 'mod_blockchain', $token->id);
                $results['success'] = false;
            }
        }

        return $results;
    }
}