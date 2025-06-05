// SPDX-License-Identifier: MIT
pragma solidity ^0.8.20;

import "@openzeppelin/contracts/access/Ownable.sol";

contract UserAddressFactory is Ownable {
    mapping(address => address) public wallets;

    event WalletCreated(address indexed user, address wallet);

    constructor() Ownable(msg.sender) {}

    function createWallet(address user) public onlyOwner {
        require(wallets[user] == address(0), "Wallet already exists");
        address wallet = address(new UserWallet(user));
        wallets[user] = wallet;
        emit WalletCreated(user, wallet);
    }
}

contract UserWallet {
    address public owner;

    constructor(address _owner) {
        owner = _owner;
    }
}