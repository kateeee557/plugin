// SPDX-License-Identifier: MIT
pragma solidity ^0.8.20;

import "@openzeppelin/contracts/token/ERC20/ERC20.sol";
import "@openzeppelin/contracts/access/Ownable.sol";

contract AcademicToken is ERC20, Ownable {
    address public admin;

    constructor() ERC20("AcademicToken", "ACT") Ownable(msg.sender) {
        admin = msg.sender;
        _mint(admin, 1000000 * 10**18); // Initial supply: 1,000,000 tokens
    }

    function reward(address to, uint256 amount) public onlyOwner {
        _mint(to, amount);
    }
}