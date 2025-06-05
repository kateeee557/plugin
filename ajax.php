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
 * AJAX handler for the blockchain module.
 *
 * @package   mod_blockchain
 * @copyright 2025 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('AJAX_SCRIPT', true);
require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/mod/blockchain/lib.php');
require_once($CFG->dirroot . '/mod/blockchain/classes/service/document_service.php');
require_once($CFG->dirroot . '/mod/blockchain/classes/service/token_service.php');

$action = required_param('action', PARAM_TEXT);
$moduleid = required_param('moduleid', PARAM_INT);
$userid = optional_param('userid', $USER->id, PARAM_INT);

require_login();
require_capability('mod/blockchain:view_dashboard', context_module::instance($moduleid));

header('Content-Type: application/json');

$service = null;
switch ($action) {
    case 'get_documents':
        $service = new mod_blockchain_document_service($moduleid, $userid);
        echo json_encode(['documents' => $service->get_documents()]);
        break;

    case 'upload_document':
        $service = new mod_blockchain_document_service($moduleid, $userid);
        $file = new stdClass();
        $file->name = required_param('filename', PARAM_TEXT);
        $file->content = base64_decode(required_param('filecontent', PARAM_BASE64));
        $is_assignment = optional_param('is_assignment', 0, PARAM_INT);
        $result = $service->upload_document($file, $is_assignment);
        echo json_encode(['success' => $result, 'error' => $result ? null : get_string('error', 'mod_blockchain')]);
        break;

    case 'get_token_balance':
        $service = new mod_blockchain_token_service($moduleid, $userid);
        echo json_encode($service->get_token_balance());
        break;

    case 'get_transaction_history':
        $service = new mod_blockchain_token_service($moduleid, $userid);
        echo json_encode($service->get_transaction_history());
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => get_string('invalidaction', 'mod_blockchain')]);
}
exit;