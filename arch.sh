#!/bin/dash
dat=$(date +%Y%m%d)  
for dir in `ls -F1 /home/ald/screensaver/shots | grep -e ./ | tr -d \/`
do tar -cvzf "/media/1093ec24-8589-4a06-9787-545849954e5a/shared/screen_saver_arch/$dat.$dir.tar.gz" "/home/ald/screensaver/shots/$dir/" &&
touch /home/ald/screensaver/shots/index.php &&
find /home/ald/screensaver/shots/ -type f -mtime +10 -exec rm -f {} \; 
find /media/1093ec24-8589-4a06-9787-545849954e5a/shared/screen_saver_arch/ -type f -mtime +60 -exec rm -f {} \;
#do echo 
#do rar a -u "/mnt/win_e/backup/$dir.rar" "/mnt/win_d/Pictures/$dir"
done
