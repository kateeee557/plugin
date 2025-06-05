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
 * Language strings for the blockchain module.
 *
 * @package   mod_blockchain
 * @copyright 2025 Your Name
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Blockchain';
$string['modulename'] = 'Blockchain';
$string['modulenameplural'] = 'Blockchains';

// Capabilities
$string['blockchain:view_dashboard'] = 'View blockchain dashboard';
$string['blockchain:grade'] = 'Grade submissions';
$string['blockchain:submit_assignment'] = 'Submit assignments';
$string['blockchain:manage_assignments'] = 'Manage assignments';
$string['blockchain:view_tokens'] = 'View token balance and history';

// General
$string['dashboard'] = 'Dashboard';
$string['assignments'] = 'Assignments';
$string['documents'] = 'Documents';
$string['tokens'] = 'Tokens';
$string['students'] = 'Students';
$string['back_to_dashboard'] = 'Back to Dashboard';
$string['back_to_admin'] = 'Back to Admin Panel';
$string['back_to_assignments'] = 'Back to Assignments';
$string['back_to_documents'] = 'Back to Documents';
$string['back_to_students'] = 'Back to Students';
$string['user'] = 'User';
$string['filename'] = 'Filename';
$string['document_id'] = 'Document ID';
$string['submission_id'] = 'Submission ID';
$string['grade'] = 'Grade';
$string['tx_hash'] = 'Transaction Hash';
$string['verified'] = 'Verified';
$string['pending'] = 'Pending';
$string['error'] = 'Error';
$string['ok'] = 'OK';
$string['no_transactions'] = 'No transactions found';
$string['error_fetching_history'] = 'Failed to fetch transaction history';
$string['title'] = 'Title';
$string['description'] = 'Description';
$string['deadline'] = 'Deadline';
$string['invaliddeadline'] = 'Deadline must be in the future';
$string['uploaddocument'] = 'Upload Document';
$string['isassignment'] = 'This is an assignment submission';
$string['uploadsubmission'] = 'Upload Submission';
$string['filerequired'] = 'A file is required';
$string['displaynametoolong'] = 'Display name must be less than 255 characters';
$string['savechanges'] = 'Save Changes';
$string['submit'] = 'Submit';
$string['status'] = 'Status';
$string['actions'] = 'Actions';
$string['submitted'] = 'Submitted';
$string['notsubmitted'] = 'Not Submitted';
$string['noassignments'] = 'No assignments found';
$string['nodocuments'] = 'No documents found';
$string['nosubmissions'] = 'No submissions found';
$string['pending_assignments'] = 'Pending Assignments';
$string['token_balance'] = 'Token Balance';
$string['total_documents'] = 'Total Documents';
$string['submit_assignment'] = 'Submit Assignment';
$string['create_assignment'] = 'Create Assignment';
$string['submissions'] = 'Submissions';
$string['submission_count'] = 'Submission Count';
$string['total_assignments'] = 'Total Assignments';
$string['pending_submissions'] = 'Pending Submissions';
$string['integrity_violations'] = 'Integrity Violations';
$string['violations_detected'] = 'Violations Detected';
$string['violations_count'] = 'Violations Count';
$string['grade_submission'] = 'Grade Submission';
$string['view_submission'] = 'View Submission';
$string['transaction_history'] = 'Transaction History';
$string['amount'] = 'Amount';
$string['reason'] = 'Reason';
$string['date'] = 'Date';
$string['view'] = 'View';
$string['edit'] = 'Edit';
$string['has_grade'] = 'Has Grade';
$string['invalidaction'] = 'Invalid action';

// Profile
$string['profile'] = 'Profile';
$string['profilesuccess'] = 'Profile updated successfully';
$string['nopermissionprofile'] = 'You do not have permission to view this profile';
$string['displayname'] = 'Display Name';
$string['wallet_address'] = 'Wallet Address';

// Security Check
$string['securitycheck'] = 'Security Check';
$string['securitycheck_desc'] = 'Review potential academic integrity violations, such as duplicate submissions.';
$string['duplicate_detected'] = 'Duplicate Submission Detected';
$string['no_duplicates'] = 'No duplicate submissions found';
$string['nopermissiontograde'] = 'You do not have permission to perform security checks';

// Sync Blockchain
$string['syncblockchain'] = 'Synchronize with Blockchain';
$string['syncblockchain_desc'] = 'Synchronize Moodle data with the blockchain for documents and tokens.';
$string['sync_results'] = 'Synchronization Results';
$string['documents_synced'] = 'Documents Synced';
$string['tokens_synced'] = 'Tokens Synced';
$string['sync_success'] = 'Synchronization completed successfully';
$string['syncerror_document'] = 'Failed to sync document ID {$a}';
$string['syncerror_token'] = 'Failed to sync token transaction ID {$a}';
$string['errors'] = 'Errors';

// Verify Document
$string['verifydocument'] = 'Verify Document';
$string['verifydocument_desc'] = 'Check the blockchain verification status of a document.';
$string['document_status'] = 'Document Status';
$string['document_verified'] = 'This document is verified on the blockchain.';
$string['document_pending'] = 'This document is not yet verified on the blockchain.';
$string['nopermissiontoview'] = 'You do not have permission to view this document';
$string['invaliddocument'] = 'Invalid document ID';

// Verify Grade
$string['verifygrade'] = 'Verify Grade';
$string['verifygrade_desc'] = 'Check the blockchain verification status of a grade.';
$string['grade_status'] = 'Grade Status';
$string['grade_verified'] = 'This grade is verified on the blockchain.';
$string['grade_pending'] = 'This grade is not yet verified on the blockchain.';
$string['invalidsubmission'] = 'Invalid submission ID';

// Errors
$string['nowallet'] = 'No wallet found for this user';

// Settings
$string['rpcsettings'] = 'Blockchain RPC Settings';
$string['rpcsettings_desc'] = 'Configure the connection to the blockchain network.';
$string['blockchain_rpc_url'] = 'Blockchain RPC URL';
$string['blockchain_rpc_url_desc'] = 'URL of the blockchain node (e.g., http://localhost:8545 or Infura Sepolia endpoint).';
$string['contractsettings'] = 'Smart Contract Settings';
$string['contractsettings_desc'] = 'Configure addresses for deployed smart contracts.';
$string['document_nft_address'] = 'Document NFT Contract Address';
$string['document_nft_address_desc'] = 'Address of the DocumentNFT smart contract.';
$string['academic_token_address'] = 'Academic Token Contract Address';
$string['academic_token_address_desc'] = 'Address of the AcademicToken smart contract.';
$string['user_address_factory_address'] = 'User Address Factory Contract Address';
$string['user_address_factory_address_desc'] = 'Address of the UserAddressFactory smart contract.';
$string['user_token_tracker_address'] = 'User Token Tracker Contract Address';
$string['user_token_tracker_address_desc'] = 'Address of the UserTokenTracker smart contract.';
$string['devsettings'] = 'Development Settings';
$string['devsettings_desc'] = 'Configure settings for development and testing.';
$string['development_mode'] = 'Development Mode';
$string['development_mode_desc'] = 'Enable mock blockchain interactions for testing.';
$string['offline_fallback'] = 'Offline Fallback';
$string['offline_fallback_desc'] = 'Enable local storage when blockchain is unavailable.';
?>