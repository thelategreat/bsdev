#!/bin/sh

GIT=git

REV=`$GIT rev-parse --short HEAD`
TODAY=`date +%Y-%m-%d`

echo $TODAY:$REV