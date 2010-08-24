git submodule init
git submodule update

# doctrine
cd vendor/Doctrine2
git submodule init
git submodule update
cp build.properties.dev build.properties
phing
rm build.properties
rm -Rf ../../upload/library/Doctrine
mv build/orm/Doctrine ../../upload/library
cd ../..

# zend framework
rm -Rf upload/library/Zend
cp -R vendor/ZendFramework/library/Zend upload/library

# zendx_application53
rm -Rf upload/library/ZendX/Application53
cp -R vendor/ZendX_Application53/lib/ZendX/Application53 upload/library/ZendX

# zendx_doctrine2
rm -Rf upload/library/ZendX/Doctrine2
cp -R vendor/ZendX_Doctrine2/lib/ZendX/Doctrine2 upload/library/ZendX

# jquery
rm upload/CMS/Core/Resource/js/jquery.min.js
cd vendor/jquery
make
cp dist/jquery.min.js ../../upload/CMS/Core/Resource/js/
cd ../..

# jquery-ui
rm -Rf upload/CMS/Core/Resource/js/jquery-ui
cd vendor/jquery-ui/build
ant
cp -R dist/jquery-ui-1.8.4 ../../../upload/CMS/Core/Resource/js/
mv ../../../upload/CMS/Core/Resource/js/jquery-ui-1.8.4 ../../../upload/CMS/Core/Resource/js/jquery-ui
cd ../../..