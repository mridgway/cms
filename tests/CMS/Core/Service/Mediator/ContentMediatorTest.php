<?php

namespace Core\Service\Mediator;

use \Mockery as m;

//require_once(__DIR__ . '/../../../../bootstrap.php');

class ContentMediatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Core\Service\Mediator\ContentMediator
     */
    protected $contentMediator = null;

    /**
     * @var Blog\Model\Article
     */
    protected $article = null;

    protected $creationDate;

    protected function setUp()
    {
        $this->creationDate = new \DateTime();
        $this->article = m::mock(new \Blog\Model\Article('Test Article', 'This is a test.'));
        $this->article->shouldReceive('getId')->andReturn(1);
        $form = new \Zend_Form();
        $form->addElement(new \Zend_Form_Element('id'))
             ->addElement(new \Zend_Form_Element('author'))
             ->addElement(new \Zend_Form_Element('authorName'))
             ->addElement(new \Zend_Form_Element('creationDate'))
             ->addElement(new \Zend_Form_Element('modificationDate'))
             ->addElement(new \Zend_Form_Element('tags'));
        $this->contentMediator = new ContentMediator($form, $this->article);
    }

    protected function tearDown()
    {
        m::close();
    }

    public function testPopulation()
    {
        $author = m::mock('User\Model\User');
        $author->shouldReceive('getId')->andReturn(1);
        $this->article->setAuthor($author);
        $this->article->setAuthorName('Test McGee');
        $modDate = clone $this->creationDate;
        $this->article->setModificationDate($modDate->add(new \DateInterval('P10D')));
        $term1 = m::mock('Taxonomy\Model\Term');
        $term1->shouldReceive('getName')->andReturn('test1');
        $term2 = m::mock('Taxonomy\Model\Term');
        $term2->shouldReceive('getName')->andReturn('test2');
        $term3 = m::mock('Taxonomy\Model\Term');
        $term3->shouldReceive('getName')->andReturn('test3');
        $term4 = m::mock('Taxonomy\Model\Term');
        $term4->shouldReceive('getName')->andReturn('test4');
        $this->article->setTags(array(
            $term1, $term2, $term3, $term4
        ));
        
        $this->contentMediator->populate();

        $form = $this->contentMediator->getForm();
        $this->assertEquals(1, $form->getValue('id'));
        $this->assertEquals(1, $form->getValue('author'));
        $this->assertEquals('Test McGee', $form->getValue('authorName'));
        $this->assertEquals($this->creationDate->format('m-d-Y'), $form->getValue('creationDate'));
        $this->assertEquals($this->creationDate->add(new \DateInterval('P10D'))->format('m-d-Y'), $form->getValue('modificationDate'));
        $this->assertEquals(4, count($form->getValue('tags')));
        $this->assertContains('test1', $form->getValue('tags'));
        $this->assertContains('test2', $form->getValue('tags'));
        $this->assertContains('test3', $form->getValue('tags'));
        $this->assertContains('test4', $form->getValue('tags'));
    }

    public function testTransferValues()
    {
        $mockUser = m::mock('User\Model\User');
        $mockUser->shouldReceive('getId')->andReturn(2);
        $mockUserService = m::mock('User\Service\User');
        $mockUserService->shouldReceive('getUser')->with(2)->andReturn($mockUser);
        $this->contentMediator->setUserService($mockUserService);

        $mockTerm1 = m::mock('Taxonomy\Model\Term');
        $mockTerm1->shouldReceive('getName')->andReturn('tag1');
        $mockTerm2 = m::mock('Taxonomy\Model\Term');
        $mockTerm2->shouldReceive('getName')->andReturn('tag3');
        $mockTerm3 = m::mock('Taxonomy\Model\Term');
        $mockTerm3->shouldReceive('getName')->andReturn('tag3');
        $mockTermService = m::mock('Taxonomy\Service\Term');
        $mockTermService->shouldReceive('getOrCreateTerm')->with('tag1', 'contentTags')->andReturn($mockTerm1);
        $mockTermService->shouldReceive('getOrCreateTerm')->with('tag2', 'contentTags')->andReturn($mockTerm2);
        $mockTermService->shouldReceive('getOrCreateTerm')->with('tag3', 'contentTags')->andReturn($mockTerm3);
        $this->contentMediator->setTermService($mockTermService);

        $this->article->shouldReceive('setId')->never();

        $data = array(
            'id' => 5,  // shouldn't change the id
            'author' => 2,
            'authorName' => 'Testerson McTest',
            'creationDate' => '10-21-2010',
            'modificationDate' => '10-28-2010',
            'tags' => array('tag1', 'tag2', 'tag3')
        );
        $this->contentMediator->getForm()->populate($data);
        $this->contentMediator->isValid($data);
        $this->contentMediator->transferValues();
        $this->assertEquals(1, $this->article->getId());
        $this->assertEquals($mockUser, $this->article->getAuthor());
        $this->assertEquals('Testerson McTest', $this->article->getAuthorName());
        $this->assertEquals('10-21-2010', $this->article->getCreationDate()->format('m-d-Y'));
        $this->assertEquals('10-28-2010', $this->article->getModificationDate()->format('m-d-Y'));
        $this->assertEquals(3, count($this->article->getTags()));
        $this->assertContains($mockTerm1, $this->article->getTags());
        $this->assertContains($mockTerm2, $this->article->getTags());
        $this->assertContains($mockTerm3, $this->article->getTags());
    }
}
