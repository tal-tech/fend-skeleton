#发布之前执行一次
cd ..
composer dump-autoload -o -a --no-dev
# 如果使用saltstack 执行 建议cd /home/www/fend/ && HOME="/home/www" /usr/local/php7/bin/php ./Bin/composer dump-autoload -o -a --no-dev