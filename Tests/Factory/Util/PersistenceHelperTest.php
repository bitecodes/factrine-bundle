<?php


namespace Fludio\FactrineBundle\Tests\Factory\Util;

use Fludio\FactrineBundle\Factory\Util\PersistenceHelper;
use Fludio\FactrineBundle\Tests\Dummy\TestCase;
use Fludio\FactrineBundle\Tests\Dummy\TestEntity\App;
use Fludio\FactrineBundle\Tests\Dummy\TestEntity\Phone;

/**
 * Class PersistanceHelperTest
 * @package Fludio\FactrineBundle\Tests\Factory\Util
 */
class PersistenceHelperTest extends TestCase
{
    /**
     * @var PersistenceHelper
     */
    protected $persistanceHelper;

    public function setUp()
    {
        parent::setUp();

        $this->persistanceHelper = new PersistenceHelper($this->em);
    }

    /** @test */
    public function it_persists_associated_entities_without_cascade_persist()
    {
        $app = new App();
        $app->setTitle('FlappyBird');

        $phone = new Phone();
        $phone->setNumber('+49123456789');
        $phone->addApp($app);

        $this->persistanceHelper->persist($phone);

        $this->assertEquals('+49123456789', $phone->getNumber());
        $this->assertEquals(1, $phone->getApps()->count());

    }

    /** @test */
    public function it_handles_non_existing_associations()
    {
        $phone = new Phone();
        $phone->setNumber('+49123456789');

        $this->persistanceHelper->persist($phone);

        $this->assertEquals('+49123456789', $phone->getNumber());
        $this->assertEmpty($phone->getApps());
    }
}
