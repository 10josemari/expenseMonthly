#!/usr/bin/env bash

# Bash "strict mode", to help catch problems and bugs in the shell script.
set -euo pipefail

# Tell apt-get we're never going to be able to give manual feedback:
export DEBIAN_FRONTEND=noninteractive

# Update the package listing, so we know what package exist:
apt-get update

# Install security updates:
apt-get -y upgrade

apt-get -y install --no-install-recommends \
	vim \
    net-tools
    
# Delete index files we don't need anymore:
rm -rf /var/lib/apt/lists/*