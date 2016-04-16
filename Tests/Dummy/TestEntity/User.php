<?php

namespace BiteCodes\FactrineBundle\Tests\Dummy\TestEntity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class User
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
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     */
    private $lastName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dob", type="date")
     */
    private $dob;

    /**
     * @var Address
     *
     * @ORM\ManyToOne(targetEntity="Address")
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id")
     **/
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="Hobby", mappedBy="user")
     **/
    private $hobbies;

    /**
     * @ORM\ManyToMany(targetEntity="EmailAddress")
     * @ORM\JoinTable(name="users_email_addresses",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="email_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $emailAddresses;

    /**
     * @ORM\OneToOne(targetEntity="Phone")
     * @ORM\JoinColumn(name="phone_id", referencedColumnName="id")
     */
    private $phone;

    /**
     * @ORM\OneToOne(targetEntity="Job", mappedBy="user")
     */
    private $job;

    /**
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="spouse_id", referencedColumnName="id")
     */
    private $spouse;

    /**
     * @ORM\ManyToMany(targetEntity="Group")
     * @ORM\JoinTable(name="users_groups",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     *      )
     */
    private $groups;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="children")
     */
    private $parents;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="parents")
     * @ORM\JoinTable(name="family",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="child_id", referencedColumnName="id")}
     *      )
     */
    private $children;

    public function __construct()
    {
        $this->hobbies = new ArrayCollection();
        $this->emailAddresses = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->parents = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

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
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set dob
     *
     * @param \DateTime $dob
     * @return User
     */
    public function setDob($dob)
    {
        $this->dob = $dob;

        return $this;
    }

    /**
     * Get dob
     *
     * @return \DateTime
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Add emailAddress
     *
     * @param EmailAddress $emailAddress
     * @return User
     */
    public function addEmailAddress(EmailAddress $emailAddress)
    {
        $this->emailAddresses[] = $emailAddress;

        return $this;
    }

    /**
     * Remove emailAddress
     *
     * @param EmailAddress $emailAddress
     */
    public function removeEmailAddress(EmailAddress $emailAddress)
    {
        $this->emailAddresses->removeElement($emailAddress);
    }

    /**
     * Get email addresses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmailAddresses()
    {
        return $this->emailAddresses;
    }

    /**
     * Add hobby
     *
     * @param Hobby $hobby
     * @return User
     */
    public function addHobby(Hobby $hobby)
    {
        $this->hobbies[] = $hobby;

        return $this;
    }

    /**
     * Remove hobby
     *
     * @param Hobby $hobby
     */
    public function removeHobby(Hobby $hobby)
    {
        $this->hobbies->removeElement($hobby);
    }

    /**
     * Get hobbies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHobbies()
    {
        return $this->hobbies;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * @param mixed $job
     * @return $this
     */
    public function setJob($job)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSpouse()
    {
        return $this->spouse;
    }

    /**
     * @param mixed $spouse
     * @return $this
     */
    public function setSpouse($spouse)
    {
        $this->spouse = $spouse;

        return $this;
    }

    /**
     * Add group
     *
     * @param Group $group
     * @return User
     */
    public function addGroup(Group $group)
    {
        $this->groups[] = $group;

        return $this;
    }

    /**
     * Remove group
     *
     * @param Group $group
     */
    public function removeGroup(Group $group)
    {
        $this->groups->removeElement($group);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Add parent
     *
     * @param User $parent
     * @return User
     */
    public function addParent(User $parent)
    {
        $this->parents[] = $parent;

        return $this;
    }

    /**
     * Remove parent
     *
     * @param User $parent
     */
    public function removeParent(User $parent)
    {
        $this->parents->removeElement($parent);
    }

    /**
     * Get parents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParents()
    {
        return $this->parents;
    }


    /**
     * Add child
     *
     * @param User $child
     * @return User
     */
    public function addChild(User $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param User $child
     */
    public function removeChild(User $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }
}