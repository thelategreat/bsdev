#!/bin/sh
#
# this can run on the client when pulling using .git/hooks/post-merge
# i.e.
# --[snip]--
# if [ -f ./git-rev.sh ] 
# then
#   ./git-rev.sh > build.txt
# fi
# --[snip]--
#

GIT=git

REV=`$GIT rev-parse --short HEAD`
TODAY=`date +%Y-%m-%d`

echo $TODAY:$REV