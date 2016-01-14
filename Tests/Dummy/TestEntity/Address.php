<?php

namespace Fludio\FactrineBundle\Tests\Dummy\TestEntity;

/**
 * Address
 *
 * @Table()
 * @Entity
 */
class Address
{
    /**
     * @var integer
     *
     * @Column(name="id", type="integer")
     * @Id
     */
    private $id;

    /**
     * @var string
     *
     * @Column(name="street", type="string", length=255)
     */
    private $street;

    /**
     * @var string
     *
     * @Column(name="city", type="string", length=255)
     */
    private $city;

    /**
     * @var string
     *
     * @Column(name="zip", type="string", length=255)
     */
    private $zip;

    /**
     * @var array
     *
     * @Column(name="roomes", type="array")
     */
    private $roomes;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set street
     *
     * @param string $street
     * @return Address
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Address
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set zip
     *
     * @param string $zip
     * @return Address
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip
     *
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @return array
     */
    public function getRoomes()
    {
        return $this->roomes;
    }

    /**
     * @param array $roomes
     * @return $this
     */
    public function setRoomes($roomes)
    {
        $this->roomes = $roomes;

        return $this;
    }
}
