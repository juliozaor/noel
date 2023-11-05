#!/bin/sh

# Make Folders Writable

# After the deployment finished, give the full 0777 permissions
# to some folders that should be writable, such as the storage/
# or bootstrap/cache/, for example.

sudo chmod -R 777 var/app/current/storage/
sudo chmod -R 777 var/app/current/bootstrap/cache/
sudo chmod -R 777 var/app/current/public/
sudo chmod -R 777 /efs

# Storage Symlink Creation
php artisan storage:link