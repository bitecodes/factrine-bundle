<?php

namespace Fludio\FactrineBundle\Tests\Dummy\TestEntity;

/**
 * House
 *
 * @Table()
 * @Entity
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
