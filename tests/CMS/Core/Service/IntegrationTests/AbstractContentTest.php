<?php
namespace Core\Service\IntegrationTests;

require_once 'PHPUnit/Framework.php';
require_once __DIR__ . '/../../../../bootstrap.php';

/**
 * Integration Test for Page Route Service.
 */
class AbstractContentTest extends \CMS\CMSAbstractIntegrationTestCase
{

    public function setUp()
    {
        parent::setUp();

        $this->_sc->getService('doctrine')->beginTransaction();
    }

    protected function tearDown()
    {
        $this->_sc->getService('doctrine')->rollback();
        \Core\Module\Registry::destroy();
    }

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
        $contentService->setValidationClassName('Core\Service\IntegrationTests\ConcreteContentValidation');
        $contentService->setClassName('Core\Service\IntegrationTests\ConcreteContent');

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
        $contentService->setValidationClassName('Core\Service\IntegrationTests\ConcreteContentValidation');
        $contentService->setClassName('Core\Service\IntegrationTests\ConcreteContent');

        $newContent = $contentService->create($data);

        $this->assertEquals(\Doctrine\Common\Util\Debug::export($content, 1), \Doctrine\Common\Util\Debug::export($newContent, 1));
    }
}

class ConcreteContent extends \Core\Model\Content {}
class ConcreteContentService extends \Core\Service\AbstractContent
{
    public function create($data)
    {
        return $this->_create($data);
    }
}

class ConcreteContentValidation extends \Zend_Form
{
    public function init()
    {
        $this->addElements(array(
            \Core\Form\Factory\ContentElementFactory::getIsFeaturedElement(),
            \Core\Form\Factory\ContentElementFactory::getAuthorNameElement()
        ));
    }
}