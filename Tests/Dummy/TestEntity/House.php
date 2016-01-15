<?php

namespace Fludio\FactrineBundle\Tests\Dummy\TestEntity;

use Doctrine\ORM\Mapping as ORM;

/**
 * House
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *     "house" = "House",
 *     "treehouse" = "TreeHouse"
 * })
 */
class House
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
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
