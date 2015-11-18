<?php


namespace Fludio\DoctrineEntityFactoryBundle\Tests\Dummy\TestEntity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * App
 *
 * @Table()
 * @Entity
 */
class App
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
     * @var string
     *
     * @Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @ManyToMany(targetEntity="Phone", mappedBy="apps")
     */
    private $phones;

    public function __construct() {
        $this->phones = new ArrayCollection();
    }

    /**
     * Add phone
     *
     * @param Phone $phone
     * @return App
     */
    public function addPhone(Phone $phone)
    {
        $this->phones[] = $phone;

        return $this;
    }

    /**
     * Remove phone
     *
     * @param Phone $phone
     */
    public function removePhone(Phone $phone)
    {
        $this->phones->removeElement($phone);
    }

    /**
     * Get phones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhones()
    {
        return $this->phones;
    }
}