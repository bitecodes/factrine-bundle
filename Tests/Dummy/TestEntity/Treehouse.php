<?php

namespace BiteCodes\FactrineBundle\Tests\Dummy\TestEntity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Treehouse extends House
{
    /**
     * @var string
     *
     * @ORM\Column(name="tree_type", type="string")
     */
    protected $treeType;

    /**
     * @return string
     */
    public function getTreeType()
    {
        return $this->treeType;
    }

    /**
     * @param string $treeType
     * @return $this
     */
    public function setTreeType($treeType)
    {
        $this->treeType = $treeType;

        return $this;
    }
}