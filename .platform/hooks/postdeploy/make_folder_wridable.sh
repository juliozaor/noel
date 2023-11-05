#!/bin/sh

# Make Folders Writable

# After the deployment finished, give the full 0777 permissions
# to some folders that should be writable, such as the storage/
# or bootstrap/cache/, for example.

php artisan storage:link
sudo chmod -R 777 storage/
sudo chmod -R 777 bootstrap/cache/
sudo chmod -R 777 public
sudo chmod -R 775 /efs

# Storage Symlink Creation
php artisan storage:link