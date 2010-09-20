git submodule init
git submodule update

# doctrine
cd vendor/Doctrine2
git submodule init
git submodule update
rm -Rf ../../upload/library/Doctrine
mkdir ../../upload/library/Doctrine
cp -R lib/Doctrine/ORM ../../upload/library/Doctrine/
cp -R lib/vendor/doctrine-common/lib/Doctrine/Common ../../upload/library/Doctrine/
cp -R lib/vendor/doctrine-dbal/lib/Doctrine/DBAL ../../upload/library/Doctrine/
cd ../..

# zend framework
rm -Rf upload/library/Zend
cp -R vendor/ZendFramework/library/Zend upload/library/

mkdir upload/library/ZendX
# zendx_application53
rm -Rf upload/library/ZendX/Application53
cp -R vendor/ZendX_Application53/lib/ZendX/Application53 upload/library/ZendX/

# zendx_doctrine2
rm -Rf upload/library/ZendX/Doctrine2
cp -R vendor/ZendX_Doctrine2/lib/ZendX/Doctrine2 upload/library/ZendX/

# jquery
#rm upload/CMS/Core/Resource/js/jquery.min.js
#cd vendor/jquery
#make
#cp dist/jquery.min.js ../../upload/CMS/Core/Resource/js/
#cd ../..

# jquery-ui
#rm -Rf upload/CMS/Core/Resource/js/jquery-ui
#cd vendor/jquery-ui/build
#ant
#cp -R dist/jquery-ui-1.8.4 ../../../upload/CMS/Core/Resource/js/
#mv ../../../upload/CMS/Core/Resource/js/jquery-ui-1.8.4 ../../../upload/CMS/Core/Resource/js/jquery-ui
#cd ../../..

# aloha
#rm -Rf upload/CMS/Core/Resource/js/aloha
#cd vendor/AlohaEditor/build
#ant
#cp -R out/aloha-nightly/aloha ../../../upload/CMS/Core/Resource/js
#cd ../../..

# set up data directory
mkdir upload/data
mkdir upload/data/proxies
chmod -R 777 upload/data
