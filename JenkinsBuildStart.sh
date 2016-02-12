wget https://bintray.com/artifact/download/pocketmine/PocketMine/PHP_7.0.3_x86-64_Linux.tar.gz
tar zxf PHP_7.0.3_x86-64_Linux.tar.gz
# wget https://github.com/ecoron/ImagicalMine-php7-linux/releases/download/0.1/ImagicalMine-php7-linux.tar.gz
# tar zxf ImagicalMine-php7-linux.tar.gz

if [ ! -d "plugins" ]; then  
mkdir "plugins"  
fi
if [ ! -d "releases" ]; then  
mkdir "releases"  
fi
PHP_BINARY="./bin/php7/bin/php"
wget -O plugins/DevTools.phar https://github.com/PocketMine/DevTools/releases/download/v1.9.0/DevTools_v1.9.0.phar
chmod +x JenkinsBuild.php
./bin/php7/bin/php JenkinsBuild.php
