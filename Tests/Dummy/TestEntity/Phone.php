<?php


namespace Fludio\FactrineBundle\Tests\Dummy\TestEntity;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Hobby
 *
 * @Table()
 * @Entity
 */
class Phone
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
     * @Column(name="number", type="string", length=255)
     */
    private $number;

    /**
     * @ManyToMany(targetEntity="App", inversedBy="phones")
     * @JoinTable(name="apps_phones")
     */
    private $apps;

    public function __construct() {
        $this->apps = new ArrayCollection();
    }

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
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $number
     * @return $this
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Add app
     *
     * @param App $app
     * @return Phone
     */
    public function addApp(App $app)
    {
        $this->apps[] = $app;

        return $this;
    }

    public function setApps($apps)
    {
        $this->apps = $apps;
    }

    /**
     * Remove app
     *
     * @param App $app
     */
    public function removeApp(App $app)
    {
        $this->apps->removeElement($app);
    }

    /**
     * Get apps
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getApps()
    {
        return $this->apps;
    }
}