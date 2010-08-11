<?php
namespace Modo\Service\Query;

use \Mock\Doctrine,
    \Mock\Modo;

require_once 'PHPUnit/Framework.php';

require_once \LIBRARY_PATH . '/Modo/Service/Query/ConstraintBuilder.php';

/**
 * Test class for ConstraintBuilder.
 * Manually created
 *
 * @version $id$
 */
class ConstraintBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ConstraintBuilder
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new ConstraintBuilder;
    }

    public function testGetHydrationMode()
    {
        $expected = $this->object->getDefaultHydrationMode();
        $actual   = $this->object->getHydrationMode();

        $this->assertSame($expected, $actual);
    }
    
    public function testGetAndSetLimit()
    {
        // large integer to ensure no conflicts with any default values
        $expected = 9999999;

        $this->object->setLimit($expected);
        $actual = $this->object->getLimit();

        $this->assertSame($expected, $actual);
    }

    public function testGetAndSetOffset()
    {
        // large integer to ensure no conflicts with any default values
        $expected = 9999999;

        $this->object->setOffset($expected);
        $actual = $this->object->getOffset();

        $this->assertSame($expected, $actual);
    }

    public function testGetAndSetOrder()
    {
        $builder = $this->object;

        $expected = array('sort' => 'u.id', 'order' => 'DESC');

        $builder->setOrderBy($expected['sort'], $expected['order']);
        $actual = $builder->getOrderBy();

        $this->assertSame($expected, $actual);
    }

    public function testGetAndSetReturnCollection()
    {
        $expected = true;

        $this->object->setReturnCollection($expected);
        $actual = $this->object->getReturnCollection();

        $this->assertSame($expected, $actual);
    }

    public function testSetHydrationMode()
    {
        $expected = $this->object->getDefaultHydrationMode() + 1;

        $this->object->setHydrationMode($expected);
        $actual = $this->object->getHydrationMode();

        $this->assertSame($expected, $actual);
    }

    public function testApplyLimitToQueryBuilderTest()
    {
        $em = new Modo\Orm\VersionedEntityManagerMock();
        $qb = new Doctrine\ORM\QueryBuilderMock($em);

        $expected = 11;

        $builder = $this->object;
        $builder->setLimit($expected)
                ->applyToQueryBuilder($qb);

        $actual = $qb->getMaxResults();

        $this->assertSame($expected, $actual);
    }

    public function testApplyOffsetToQueryBuilderTest()
    {
        $em = new Modo\Orm\VersionedEntityManagerMock();
        $qb = new Doctrine\ORM\QueryBuilderMock($em);

        $expected = 3;

        $builder = $this->object;
        $builder->setOffset($expected)
                ->applyToQueryBuilder($qb);

        $actual = $qb->getFirstResult();

        $this->assertSame($expected, $actual);
    }

    public function testApplyOrderToQueryBuilderTest()
    {
        $em = new Modo\Orm\VersionedEntityManagerMock();
        $qb = new Doctrine\ORM\QueryBuilderMock($em);

        $expectedSort  = 'blah';
        $expectedOrder = 'DESC';

        $builder = $this->object;
        $builder->setOrderBy($expectedSort, $expectedOrder)
                ->applyToQueryBuilder($qb);
        
        $actualSort  = $qb->testGetSort();
        $actualOrder = $qb->testGetOrder();

        $sortMessage  = "'$actualSort' sort does not match expected '$expectedSort'";
        $orderMessage = "'$actualSort' order does not match expected '$expectedOrder'";

        $this->assertSame($expectedSort, $actualSort, $sortMessage);
        $this->assertSame($expectedOrder, $actualOrder, $orderMessage);
    }
}