chmod +X /build/php5-0LI9sl
pecl install channel://pecl.php.net/pthreads-3.1.5
pecl install channel://pecl.php.net/weakref-0.3.1
pecl install channel://pecl.php.net/yaml-2.0.0RC7
tar zxf /etc/php5-pm/PHP_5.6.10_x86-64_Linux.tar.gz
if [ ! -d "plugins" ]; then  
mkdir "plugins"  
fi
if [ ! -d "releases" ]; then  
mkdir "releases"  
fi
cp /etc/php5-pm/DevTools_v1.10.0.phar ./plugins/DevTools.phar
chmod +x ./JenkinsBuild.php
./JenkinsBuild.php ${BUILD_NUMBER}
