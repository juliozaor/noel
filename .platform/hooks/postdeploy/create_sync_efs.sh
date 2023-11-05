#!/bin/sh

SYMLINK="/var/app/current/public/temp"
TARGET="/efs/temp"

if [ -L "$SYMLINK" ]; then
    if [ "$(readlink "$SYMLINK")" != "$TARGET" ]; then
        echo "Eliminando el enlace simbólico existente en $SYMLINK"
        sudo rm -R "$SYMLINK"
    else
        echo "El enlace simbólico ya apunta a $TARGET."
        exit 1
    fi
fi

if [ -d "$SYMLINK" ]; then
    echo "Eliminando el directorio existente en $SYMLINK"
    sudo rm -R "$SYMLINK"
fi

echo "Creando nuevo enlace simbólico en $SYMLINK apuntando a $TARGET"
sudo ln -s "$TARGET" /var/app/current/public
   
