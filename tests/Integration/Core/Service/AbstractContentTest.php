<?php

namespace Integration\Core\Service;

require_once 'PHPUnit/Framework.php';
require_once __DIR__ . '/../../../bootstrap.php';

/**
 * Integration Test for Abstract Content Service.
 */
class AbstractContentTest extends \Integration\IntegrationTestCase
{
    protected $_user;

    public function setUp()
    {
        parent::setUp();
        
        $data = array(
            'group' => 'Admin',
            'email' => 'test@test.com',
            'firstName' => 'test',
            'lastName' => 'test'
        );

        $userService = $this->_sc->getService('userService');
        $user = $userService->createUser($data);

        $this->_user = $user;
    }

    public function testCreate()
    {
        $data = array(
            'tags' => array(
                'tag1',
                'tag2'
            ),
            'author' => array(
                'id' => $this->_user->getId()
            ),
            'isFeatured' => true
        );

        $content = new ConcreteContent();
        $content->setTags($this->_sc->getService('termService')->getOrCreateTerms($data['tags'], 'contentTags'));
        $content->setAuthor($this->_user);
        $content->setAuthorName($this->_user->getFirstName() . ' ' . $this->_user->getLastName());
        $content->setIsFeatured(true);

        $contentService = new ConcreteContentService();
        $contentService->setTermService($this->_sc->getService('termService'));
        $contentService->setUserService($this->_sc->getService('userService'));
        $contentService->setValidationClassName('Integration\Core\Service\ConcreteContentValidation');
        $contentService->setClassName('Integration\Core\Service\ConcreteContent');

        $newContent = $contentService->create($data);

        $this->assertEquals(\Doctrine\Common\Util\Debug::export($content, 1), \Doctrine\Common\Util\Debug::export($newContent, 1));
    }

    public function testCreateIfAuthorIsGivenThenAuthorNameIsOverWritten()
    {
        $data = array(
            'tags' => array(
                'tag1',
                'tag2'
            ),
            'author' => array(
                'id' => $this->_user->getId()
            ),
            'authorName' => 'doesnotmatter',
            'isFeatured' => true
        );

        $content = new ConcreteContent();
        $content->setTags($this->_sc->getService('termService')->getOrCreateTerms($data['tags'], 'contentTags'));
        $content->setAuthor($this->_user);
        $content->setAuthorName($this->_user->getFirstName() . ' ' . $this->_user->getLastName());
        $content->setIsFeatured(true);

        $contentService = new ConcreteContentService();
        $contentService->setTermService($this->_sc->getService('termService'));
        $contentService->setUserService($this->_sc->getService('userService'));
        $contentService->setValidationClassName('Integration\Core\Service\ConcreteContentValidation');
        $contentService->setClassName('Integration\Core\Service\ConcreteContent');

        $newContent = $contentService->create($data);

        $this->assertEquals(\Doctrine\Common\Util\Debug::export($content, 1), \Doctrine\Common\Util\Debug::export($newContent, 1));
    }

    public function testOnlySetAuthorNameIfAuthorIdIsNotGiven()
    {
        $data = array(
            'tags' => array(
                'tag1',
                'tag2'
            ),
            'authorName' => 'doesnotmatter',
            'isFeatured' => true
        );

        $content = new ConcreteContent();
        $content->setTags($this->_sc->getService('termService')->getOrCreateTerms($data['tags'], 'contentTags'));
        $content->setAuthorName($data['authorName']);
        $content->setIsFeatured(true);

        $contentService = new ConcreteContentService();
        $contentService->setTermService($this->_sc->getService('termService'));
        $contentService->setValidationClassName('Integration\Core\Service\ConcreteContentValidation');
        $contentService->setClassName('Integration\Core\Service\ConcreteContent');

        $newContent = $contentService->create($data);

        $this->assertEquals(\Doctrine\Common\Util\Debug::export($content, 1), \Doctrine\Common\Util\Debug::export($newContent, 1));
    }

    public function testShouldThrowErrorIfAuthorIsNotSet()
    {
        $data = array(
            'tags' => array(
                'tag1',
                'tag2'
            ),
            'isFeatured' => true
        );

        $contentService = new ConcreteContentService();
        $contentService->setTermService($this->_sc->getService('termService'));
        $contentService->setValidationClassName('Integration\Core\Service\ConcreteContentValidation');
        $contentService->setClassName('Integration\Core\Service\ConcreteContent');

        $this->setExpectedException('Core\Exception\ValidationException');
        $newContent = $contentService->create($data);
    }

    public function shouldNotThrowErrorIfAuthorIsNotSet()
    {
        $data = array(
            'isFeatured' => false
        );

        $content = new ConcreteContent();
        $content->setTags($this->_sc->getService('termService')->getOrCreateTerms(array('tag1', 'tag2'), 'contentTags'));
        $content->setAuthorName('authorName');
        $content->setIsFeatured(true);

        $contentService = new ConcreteContentService();
        $contentService->setTermService($this->_sc->getService('termService'));
        $contentService->setValidationClassName('Integration\Core\Service\ConcreteContentValidation');
        $contentService->setClassName('Integration\Core\Service\ConcreteContent');

        $newContent = $contentService->setContentObjects($content, $data, false);

        $this->assertEquals(false, $content->getIsFeatured());
    }

    public function testUpdateTags()
    {
        $data = array(
            'tags' => array(
                'tag1',
                'tag2'
            ),
            'author' => array(
                'id' => $this->_user->getId()
            ),
            'isFeatured' => true
        );

        $contentService = new ConcreteContentService();
        $contentService->setTermService($this->_sc->getService('termService'));
        $contentService->setUserService($this->_sc->getService('userService'));
        $contentService->setValidationClassName('Integration\Core\Service\ConcreteContentValidation');
        $contentService->setClassName('Integration\Core\Service\ConcreteContent');

        $newContent = $contentService->create($data);

        $data = array(
            'tags' => '',
            'author' => array(
                'id' => $this->_user->getId()
            ),
            'isFeatured' => true
        );

        $contentService->setContentObjects($newContent, $data, false);


        $this->assertEquals(array(), \Doctrine\Common\Util\Debug::export($newContent->getTags(), 1));
    }
}

class ConcreteContent extends \Core\Model\Content {}
class ConcreteContentService extends \Core\Service\AbstractContent
{
    public function create($data)
    {
        return $this->_create($data);
    }

    public function update($data)
    {
        return $this->_update($data);
    }

    public function setContentObjects($content, $data, $throwErrors)
    {
        return $this->_setContentObjects($content, $data, $throwErrors);
    }
}

class ConcreteContentValidation extends \Zend_Form
{
    public function init()
    {
        $this->addElements(array(
            \Core\Form\Factory\ContentElementFactory::getIdElement(),
            \Core\Form\Factory\ContentElementFactory::getIsFeaturedElement(),
            \Core\Form\Factory\ContentElementFactory::getAuthorNameElement()
        ));
    }
}