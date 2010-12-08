<?php

namespace Integration\Core\Service;

require_once 'PHPUnit/Framework.php';
require_once __DIR__ . '/../../../bootstrap.php';

/**
 * Integration Test for Abstract Content Service.
 */
class AbstractContentTest extends \Integration\IntegrationTestCase
{
    public function testCreate()
    {
        $data = array(
            'tags' => array(
                'tag1',
                'tag2'
            ),
            'author' => array(
                'id' => 1
            ),
            'isFeatured' => true
        );

        $userService = $this->_sc->getService('userService');
        $user = $userService->getUser($data['author']['id']);

        $content = new ConcreteContent();
        $content->setTags($this->_sc->getService('termService')->getOrCreateTerms($data['tags'], 'contentTags'));
        $content->setAuthor($user);
        $content->setAuthorName($user->getFirstName() . ' ' . $user->getLastName());
        $content->setIsFeatured(true);

        $contentService = new ConcreteContentService();
        $contentService->setTermService($this->_sc->getService('termService'));
        $contentService->setUserService($userService);
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
                'id' => 1
            ),
            'authorName' => 'doesnotmatter',
            'isFeatured' => true
        );

        $userService = $this->_sc->getService('userService');
        $user = $userService->getUser($data['author']['id']);

        $content = new ConcreteContent();
        $content->setTags($this->_sc->getService('termService')->getOrCreateTerms($data['tags'], 'contentTags'));
        $content->setAuthor($user);
        $content->setAuthorName($user->getFirstName() . ' ' . $user->getLastName());
        $content->setIsFeatured(true);

        $contentService = new ConcreteContentService();
        $contentService->setTermService($this->_sc->getService('termService'));
        $contentService->setUserService($userService);
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
}

class ConcreteContent extends \Core\Model\Content {}
class ConcreteContentService extends \Core\Service\AbstractContent
{
    public function create($data)
    {
        return $this->_create($data);
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