#!/bin/sh

# Install  Node  alongside with the paired NPM release
NODE_VERSION="18"

if [[ ! "$(node --version)" =~ "v$NODE_VERSION" ]]; then
    # Remove existing Node.js and npm if not version 18
    sudo yum remove -y nodejs npm

    # Clear package manager cache
    sudo rm -fr /var/cache/yum/*

    # Clean all
    sudo yum clean all

    # Install Node.js 18
    curl --silent --location https://rpm.nodesource.com/setup_$NODE_VERSION.x | sudo bash -
    sudo yum install nodejs -y
fi