const AcademicToken = artifacts.require("AcademicToken");
const DocumentNFT = artifacts.require("DocumentNFT");
const UserAddressFactory = artifacts.require("UserAddressFactory");
const UserTokenTracker = artifacts.require("UserTokenTracker");

module.exports = function (deployer) {
    deployer.deploy(AcademicToken);
    deployer.deploy(DocumentNFT);
    deployer.deploy(UserAddressFactory);
    deployer.deploy(UserTokenTracker);
};