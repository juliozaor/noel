#!/bin/sh

# Install  Node  alongside with the paired NPM release
# NODE_VERSION="18"

# if ! command -v node &> /dev/null || [[ ! "$(node --version)" =~ "v$NODE_VERSION" ]]; then
#     # Remove existing Node.js and npm if not version 18
#     sudo yum remove -y nodejs npm

#     # Clear package manager cache
#     sudo rm -fr /var/cache/yum/*

#     # Clean all
#     sudo yum clean all

#     # Install Node.js 18
#     curl --silent --location https://rpm.nodesource.com/setup_$NODE_VERSION.x | sudo bash -
#     sudo yum install nodejs -y
# fi



# Install Node.js LTS using NVM
NVM_VERSION="0.39.5"
NODE_VERSION="18"  # Change to the desired LTS version

# Install NVM
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v$NVM_VERSION/install.sh | bash
. ~/.nvm/nvm.sh

# Check if Node.js is installed and the correct version
if ! nvm list | grep -q "v$NODE_VERSION"; then
    # Remove existing Node.js if not the correct version
    nvm uninstall $NODE_VERSION

    # Install Node.js LTS
    nvm install $NODE_VERSION || nvm install --lts
fi