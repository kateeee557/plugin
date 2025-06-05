Moodle Blockchain Plugin (mod_blockchain)
Overview
The mod_blockchain plugin integrates blockchain technology into Moodle to enhance transparency and integrity in academic processes. It leverages blockchain to secure documents, verify submissions, track student performance, and reward students with tokens. Built as a Moodle activity module, it supports both student and teacher roles with features like document verification via NFTs, immutable grade recording, and a token-based reward system.
Key Features

User Management:
Role-based access for students and teachers.
Automatic blockchain wallet generation during registration.
Profile management with blockchain address display.


Student Features:
Dashboard with assignments, grades, and token balance.
Assignment submission with deadline tracking.
Document upload and verification as NFTs (ERC-721).
Token system to view balance, transaction history, and benefits.


Teacher Features:
Dashboard with assignment overview and integrity checks.
Assignment creation, editing, and submission tracking.
Grading with blockchain verification.
Document sharing with blockchain verification.
Academic integrity violation detection (< 1% false positive rate).


Blockchain Integration:
Smart Contracts:
Academic Token (ERC-20) for rewards.
Document NFT (ERC-721) for verification.
User Wallet Factory for wallet management.
User Token Tracker for transaction history.


Document verification with hash-based NFT minting.
Immutable grade recording on the blockchain.
Token rewards (10 tokens for timely submissions).


Technical Requirements:
Backend: PHP with Web3.php for blockchain interactions.
Frontend: Responsive Bootstrap UI with blockchain status indicators.
Security: Password hashing, CSRF protection, integrity checks.
Storage: Secure file storage with blockchain verification.
Blockchain Config: Supports development/production modes with offline fallback.


Additional Features:
Performance dashboards and token statistics.
Smart deadlines with token-based extensions.
Blockchain-verified academic credentials.
Advanced integrity with duplicate submission detection.



Installation
Prerequisites

Moodle 3.11 or later.
PHP 7.4 or later with required extensions (e.g., curl for Web3.php).
Composer for PHP dependency management.
Access to an Ethereum-compatible blockchain node (e.g., localhost, Infura Sepolia).
Deployed smart contracts (AcademicToken, DocumentNFT, UserAddressFactory, UserTokenTracker).

Steps

Clone the Repository:
git clone https://github.com/yourusername/mod_blockchain.git mod_blockchain

Place the mod_blockchain folder in moodle/mod/.

Install Dependencies:Navigate to moodle/mod/blockchain/ and run:
composer install

This installs Web3.php and other dependencies.

Install the Plugin:

Log in to your Moodle instance as an administrator.
Go to Site administration > Notifications.
Follow the prompts to install the plugin.


Configure Blockchain Settings:

Go to Site administration > Plugins > Activity modules > Blockchain.
Set the following:
Blockchain RPC URL: URL of your blockchain node (e.g., http://localhost:8545 or Infura endpoint).
Contract Addresses: Addresses of deployed smart contracts (AcademicToken, DocumentNFT, UserAddressFactory, UserTokenTracker).
Development Mode: Enable for testing with mock blockchain interactions.
Offline Fallback: Enable to allow operations when the blockchain is unavailable.




Deploy Smart Contracts (if not already deployed):

Use Truffle or Hardhat to compile and deploy the smart contracts located in mod_blockchain/contracts/.
Copy the generated ABI files to mod_blockchain/abi/.
Update contract addresses in the plugin settings.


Add the Activity to a Course:

In a course, click Turn editing on.
Add an activity and select Blockchain.
Configure the activity settings (e.g., name, intro).



Usage
For Students

Access the Dashboard:
Navigate to the Blockchain activity in your course.
View pending assignments, token balance, and total documents.


Submit Assignments:
Go to the Assignments tab.
Click Submit on an assignment and upload your file.
On-time submissions automatically reward 10 tokens.


Manage Documents:
Upload documents in the Documents tab.
Documents are verified as NFTs on the blockchain within 5 minutes.


Track Tokens:
View your token balance and transaction history in the Tokens tab.



For Teachers

Access the Dashboard:
View total assignments, pending submissions, and integrity violations.


Manage Assignments:
Create or edit assignments in the Assignments tab.
Track student submissions.


Grade Submissions:
Go to the Students tab.
View submissions, grade them, and record on the blockchain.


Check Academic Integrity:
Use the Security Check tab to review duplicate submissions.


Synchronize with Blockchain:
Use the Synchronize tab to sync Moodle data with the blockchain.



Development Notes
Directory Structure
mod_blockchain/
├── abi/                    # Smart contract ABIs
├── classes/
│   ├── form/               # Moodle forms (e.g., submission_form.php)
│   ├── service/            # Service classes (e.g., document_service.php)
│   └── renderer.php        # Template renderer
├── contracts/              # Smart contracts (Solidity files)
├── lang/
│   └── en/                 # Language strings (blockchain.php)
├── templates/              # Mustache templates
├── vendor/                 # Composer dependencies (Web3.php)
├── ajax.php                # AJAX handler
├── grade.php               # Grade submission page
├── lib.php                 # Core plugin functions
├── mod_form.php            # Module settings form
├── profile.php             # Profile management page
├── security.php            # Academic integrity checks
├── settings.php            # Plugin settings
├── styles.css              # Custom styles
├── submit.php              # Assignment submission page
├── sync_moodle.php         # Blockchain synchronization page
├── verify_document.php     # Document verification page
├── verify_grade.php        # Grade verification page
├── version.php             # Plugin version
├── view.php                # Main view page
└── README.md               # Documentation

Database Schema
Defined in install.xml:

blockchain_documents: Stores document metadata (ID, user ID, filename, hash, token ID).
blockchain_grades: Stores grade records (submission ID, grade, hash, transaction hash).
blockchain_tokens: Stores token transactions (user ID, amount, reason, transaction hash).
blockchain_wallets: Stores user wallet addresses.

Smart Contracts

AcademicToken (ERC-20): Manages token rewards.
DocumentNFT (ERC-721): Mints NFTs for document verification.
UserAddressFactory: Generates user wallets.
UserTokenTracker: Tracks token transactions.

Testing

Enable development_mode in settings to mock blockchain interactions.
Use a local Ethereum node (e.g., Ganache) for testing.
Test document uploads, grade recording, and token rewards.

License
This plugin is licensed under the GNU General Public License v3 (GPLv3). See the LICENSE file for details.
Contributing
Contributions are welcome! Please fork the repository, create a feature branch, and submit a pull request.
Support
For issues, please open a ticket on the GitHub repository or contact the maintainer at your-email@example.com.

Last updated: June 04, 2025
