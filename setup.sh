#!/bin/bash

echo "Setting up handback at $PWD"
chmod 750 ./
chmod 640 .htaccess
chmod 640 table.css
cp handback.cfg.default handback.cfg
