<?php


namespace Fludio\FactrineBundle\Tests\Factory\Util;


use Fludio\FactrineBundle\Factory\DataProvider\FakerDataProvider;
use Fludio\FactrineBundle\Factory\Util\DataGuesser;

class DataGuesserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DataGuesser
     */
    protected $guesser;

    public function setUp()
    {
        $dataProvider = new FakerDataProvider();
        $this->guesser = new DataGuesser($dataProvider);
    }

    /** @test */
    public function it_guesses_city_data()
    {
        $mapping = ['fieldName' => 'city'];

        $result = $this->guesser->guess($mapping);

        $this->assertEquals('faker.city', $result);
    }

    /** @test */
    public function it_guesses_street_data()
    {
        $mapping = ['fieldName' => 'street'];

        $result = $this->guesser->guess($mapping);

        $this->assertContains('faker.street', $result);
    }

    /** @test */
    public function it_guesses_first_name_data()
    {
        $mapping = ['fieldName' => 'firstName'];

        $result = $this->guesser->guess($mapping);

        $this->assertEquals('faker.firstName', $result);
    }

    /** @test */
    public function it_guesses_name_data()
    {
        $mapping = ['fieldName' => 'name'];

        $result = $this->guesser->guess($mapping);

        $this->assertEquals('faker.name', $result);
    }

    /** @test */
    public function it_uses_default_integer_data()
    {
        $mapping1 = ['fieldName' => 'amount', 'type' => 'integer'];
        $mapping2 = ['fieldName' => 'amount', 'type' => 'smallint'];
        $mapping3 = ['fieldName' => 'amount', 'type' => 'bigint'];
        $mapping4 = ['fieldName' => 'amount', 'type' => 'decimal'];
        $mapping5 = ['fieldName' => 'amount', 'type' => 'float'];

        $result1 = $this->guesser->guess($mapping1);
        $result2 = $this->guesser->guess($mapping2);
        $result3 = $this->guesser->guess($mapping3);
        $result4 = $this->guesser->guess($mapping4);
        $result5 = $this->guesser->guess($mapping5);

        $this->assertEquals('faker.randomNumber', $result1);
        $this->assertEquals('faker.randomNumber', $result2);
        $this->assertEquals('faker.randomNumber', $result3);
        $this->assertEquals('faker.randomNumber', $result4);
        $this->assertEquals('faker.randomNumber', $result5);
    }

    /** @test */
    public function it_uses_default_string_data()
    {
        $mapping1 = ['fieldName' => 'song', 'type' => 'string'];
        $mapping2 = ['fieldName' => 'song', 'type' => 'text'];

        $result1 = $this->guesser->guess($mapping1);
        $result2 = $this->guesser->guess($mapping2);

        $this->assertEquals('faker.sentence', $result1);
        $this->assertEquals('faker.sentence', $result2);
    }

    /** @test */
    public function it_uses_default_date_data()
    {
        $mapping1 = ['fieldName' => 'dob', 'type' => 'date'];
        $mapping2 = ['fieldName' => 'dob', 'type' => 'datetime'];

        $result1 = $this->guesser->guess($mapping1);
        $result2 = $this->guesser->guess($mapping2);

        $this->assertEquals('faker.dateTimeBetween', $result1);
        $this->assertEquals('faker.dateTimeBetween', $result2);
    }

    /** @test */
    public function it_uses_default_boolean_data()
    {
        $mapping = ['fieldName' => 'isChecked', 'type' => 'boolean'];

        $result = $this->guesser->guess($mapping);

        $this->assertEquals('faker.boolean', $result);
    }

    /** @test */
    public function it_adds_a_question_mark_for_unknown_types()
    {
        $mapping = ['fieldName' => 'isChecked', 'type' => 'strange'];

        $result = $this->guesser->guess($mapping);

        $this->assertEquals('faker.?', $result);
    }
}