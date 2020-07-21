set -e

cd ../../fend/
# clean old
rm -rf app bin logs www fend

# cp new
cp -r ../fend-demo/.gitignore .gitignore
cp -r ../fend-demo/app app
cp -r ../fend-demo/bin bin
mkdir logs
cp -r ../fend-demo/www www

#init fend
mkdir fend
cp -r ../fend-demo/init.php init.php

cp -r ../fend-demo/vendor/php/fend-core/src/* fend/

cp -r ../fend-demo/vendor/php/fend-plugin-cache/src/* fend/

cp -r ../fend-demo/vendor/php/fend-plugin-db/src/* fend/

mkdir fend/Router
cp -r ../fend-demo/vendor/php/fend-plugin-fastrouter/src/* fend/Router/

cp -r ../fend-demo/vendor/php/fend-plugin-kafka/src/* fend/

mkdir fend/Cache
cp -r ../fend-demo/vendor/php/fend-plugin-memcache/src/* fend/Cache/

cp -r ../fend-demo/vendor/php/fend-plugin-mysqli/src/* fend/Db/
cp -r ../fend-demo/vendor/php/fend-plugin-pdo/src/* fend/Db/
cp -r ../fend-demo/vendor/php/fend-plugin-queue/src/* fend/
cp -r ../fend-demo/vendor/php/fend-plugin-rabbitmq/src/* fend/
cp -r ../fend-demo/vendor/php/fend-plugin-redis/src/* fend/
cp -r ../fend-demo/vendor/php/fend-plugin-redismodel/src/* fend/Redis
cp -r ../fend-demo/vendor/php/fend-plugin-router/src/* fend/Router

mkdir fend/Server
cp -r ../fend-demo/vendor/php/fend-plugin-server/src/* fend/Server
cp -r ../fend-demo/vendor/php/fend-plugin-validator/src/* fend/

cd ../fend-demo/bin