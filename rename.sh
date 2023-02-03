#!/bin/sh
echo "choose wisely ! you won't be able to rename it using this command again afterwards"
read -p "How would you like to name your project ? (keep it cool, letters, numbers and underscores only) " projectName

sed -i ".bak" "s/BOILERPLATE/$projectName/g" *
find . -name "*.bak" -type f -delete
echo "I'm sure $projectName is going to be LEGEND ...wait for it..."
sleep 1
echo "DARY!"