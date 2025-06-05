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
 * Post-install script for the blockchain module.
 *
 * @package   mod_blockchain
 * @copyright 2025 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Post-install function.
 */
function xmldb_blockchain_install() {
    global $DB;

    // Create default settings if not already set.
    set_config('blockchain_rpc_url', 'http://localhost:8545', 'mod_blockchain');
    set_config('development_mode', 1, 'mod_blockchain');
    set_config('offline_fallback', 1, 'mod_blockchain');

    // Create a default wallet for admin user (user ID 2 in Moodle default setup).
    $admin_wallet = [
        'userid' => 2,
        'address' => '0xAdminWalletAddressPlaceholder',
        'timecreated' => time(),
        'timemodified' => time(),
    ];
    $DB->insert_record('blockchain_wallets', $admin_wallet);

    return true;
}