// SPDX-License-Identifier: MIT
pragma solidity ^0.8.20;

import "@openzeppelin/contracts/access/Ownable.sol";

contract UserTokenTracker is Ownable {
    mapping(address => mapping(uint256 => string)) public transactions;

    event TransactionRecorded(address indexed user, uint256 amount, string reason, string txHash);

    constructor() Ownable(msg.sender) {}

    function recordTransaction(address user, uint256 amount, string memory reason) public onlyOwner {
        string memory txHash = string(abi.encodePacked(user, block.timestamp)); // Simplified hash
        transactions[user][amount] = txHash;
        emit TransactionRecorded(user, amount, reason, txHash);
    }
}