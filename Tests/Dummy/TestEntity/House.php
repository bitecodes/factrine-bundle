<?php

namespace Fludio\FactrineBundle\Tests\Dummy\TestEntity;

/**
 * House
 *
 * @Table()
 * @Entity
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="type", type="string")
 * @DiscriminatorMap({
 *     "house" = "House",
 *     "treehouse" = "TreeHouse"
 * })
 */
class House
{
    /**
     * @var integer
     *
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
