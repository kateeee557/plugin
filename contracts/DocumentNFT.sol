// SPDX-License-Identifier: MIT
pragma solidity ^0.8.20;

import "@openzeppelin/contracts/token/ERC721/ERC721.sol";
import "@openzeppelin/contracts/access/Ownable.sol";

contract DocumentNFT is ERC721, Ownable {
    uint256 private _tokenIdCounter;

    constructor() ERC721("DocumentNFT", "DOC") Ownable(msg.sender) {
        _tokenIdCounter = 0;
    }

    function mint(address to, string memory hash) public onlyOwner {
        uint256 tokenId = _tokenIdCounter;
        _tokenIdCounter += 1; // Increment counter manually
        _safeMint(to, tokenId);
        // Store hash (simplified, in practice use IPFS or similar).
    }

    function tokenOfOwnerByIndex(address owner, uint256 index) public view returns (uint256) {
        require(index < balanceOf(owner), "Index out of bounds");
        return uint256(uint160(owner) + index); // Simplified; use proper enumeration.
    }
}