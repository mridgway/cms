git submodule update --init

# doctrine
cd vendor/Doctrine2
git submodule update --init
cp build.properties.dev build.properties
phing
rm build.properties
rm -Rf ../../upload/library/Doctrine
mv build/orm/Doctrine ../../upload/library
cd ../..

# zend framework
cd vendor/ZendFramework
git submodule update --init
cd ../..
rm -Rf upload/library/Zend
cp -R vendor/ZendFramework/library/Zend upload/library/

# zendx
rm -Rf upload/library/ZendX
cp -R /Users/mike/Sites/ModoCMS/vendor/ZendFramework/extras/library/ZendX upload/library

# zendx_application53
cd vendor/ZendX_Application53
git submodule update --init
cd ../..
rm -Rf upload/library/ZendX/Application53
cp -R vendor/ZendX_Application53/lib/ZendX/Application53 upload/library/ZendX/

# zendx_doctrine2
cd vendor/ZendX_Doctrine2
git submodule update --init
cd ../..
rm -Rf upload/library/ZendX/Doctrine2
cp -R vendor/ZendX_Doctrine2/lib/ZendX/Doctrine2 upload/library/ZendX/

# jquery
cd vendor/jquery
git submodule update --init
make
rm ../../upload/public/resources/vendor/js/jquery.min.js
cp dist/jquery.min.js ../../upload/public/resources/vendor/js/
cd ../..

# jquery-ui
cd vendor/jquery-ui/build
git submodule update --init
ant
rm -Rf ../../../upload/public/resources/vendor/js/jquery-ui
cp -R dist/jquery-ui-1.8.4 ../../../upload/public/resources/vendor/js/
mv ../../../upload/public/resources/vendor/js/jquery-ui-1.8.4 ../../../upload/public/resources/vendor/js/jquery-ui
cd ../../..

# aloha
#cd vendor/AlohaEditor/build
#git submodule update --init
#ant
#rm -Rf ../../../upload/public/resources/vendor/js/aloha
#cp -R out/aloha-nightly/aloha ../../../upload/public/resources/vendor/js
#cd ../../..
