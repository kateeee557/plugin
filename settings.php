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
 * Settings page for the blockchain module.
 *
 * @package   mod_blockchain
 * @copyright 2025 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    require_once($CFG->dirroot . '/mod/blockchain/lib.php');

    // RPC Settings
    $settings->add(new admin_setting_heading('blockchain_rpcsettings', get_string('rpcsettings', 'mod_blockchain'),
        get_string('rpcsettings_desc', 'mod_blockchain')));
    $settings->add(new admin_setting_configtext('mod_blockchain/blockchain_rpc_url',
        get_string('blockchain_rpc_url', 'mod_blockchain'),
        get_string('blockchain_rpc_url_desc', 'mod_blockchain'), 'http://localhost:8545', PARAM_URL));

    // Contract Settings
    $settings->add(new admin_setting_heading('blockchain_contractsettings', get_string('contractsettings', 'mod_blockchain'),
        get_string('contractsettings_desc', 'mod_blockchain')));
    $settings->add(new admin_setting_configtext('mod_blockchain/document_nft_address',
        get_string('document_nft_address', 'mod_blockchain'),
        get_string('document_nft_address_desc', 'mod_blockchain'), '', PARAM_TEXT));
    $settings->add(new admin_setting_configtext('mod_blockchain/academic_token_address',
        get_string('academic_token_address', 'mod_blockchain'),
        get_string('academic_token_address_desc', 'mod_blockchain'), '', PARAM_TEXT));
    $settings->add(new admin_setting_configtext('mod_blockchain/user_address_factory_address',
        get_string('user_address_factory_address', 'mod_blockchain'),
        get_string('user_address_factory_address_desc', 'mod_blockchain'), '', PARAM_TEXT));
    $settings->add(new admin_setting_configtext('mod_blockchain/user_token_tracker_address',
        get_string('user_token_tracker_address', 'mod_blockchain'),
        get_string('user_token_tracker_address_desc', 'mod_blockchain'), '', PARAM_TEXT));

    // Development Settings
    $settings->add(new admin_setting_heading('blockchain_devsettings', get_string('devsettings', 'mod_blockchain'),
        get_string('devsettings_desc', 'mod_blockchain')));
    $settings->add(new admin_setting_configcheckbox('mod_blockchain/development_mode',
        get_string('development_mode', 'mod_blockchain'),
        get_string('development_mode_desc', 'mod_blockchain'), 1));
    $settings->add(new admin_setting_configcheckbox('mod_blockchain/offline_fallback',
        get_string('offline_fallback', 'mod_blockchain'),
        get_string('offline_fallback_desc', 'mod_blockchain'), 1));
}