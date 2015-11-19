<?php

namespace Fludio\DoctrineEntityFactoryBundle\Tests\Dummy\TestEntity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 *
 * @Table()
 * @Entity
 */
class User
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
     * @Column(name="firstName", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @Column(name="lastName", type="string", length=255)
     */
    private $lastName;

    /**
     * @var \DateTime
     *
     * @Column(name="dob", type="date")
     */
    private $dob;

    /**
     * @var Address
     *
     * @ManyToOne(targetEntity="Address", cascade={"persist"})
     * @JoinColumn(name="address_id", referencedColumnName="id")
     **/
    private $address;

    /**
     * @OneToMany(targetEntity="Hobby", mappedBy="user", cascade={"persist"})
     **/
    private $hobbies;

    /**
     * @ManyToMany(targetEntity="EmailAddress")
     * @JoinTable(name="users_email_addresses",
     *      joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="email_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $emailAddresses;

    /**
     * @OneToOne(targetEntity="Phone")
     * @JoinColumn(name="phone_id", referencedColumnName="id")
     */
    private $phone;

    /**
     * @OneToOne(targetEntity="Job", mappedBy="user")
     */
    private $job;

    /**
     * @OneToOne(targetEntity="User")
     * @JoinColumn(name="spouse_id", referencedColumnName="id")
     */
    private $spouse;

    /**
     * @ManyToMany(targetEntity="Group")
     * @JoinTable(name="users_groups",
     *      joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="group_id", referencedColumnName="id")}
     *      )
     */
    private $groups;

    /**
     * @ManyToMany(targetEntity="User", mappedBy="children")
     */
    private $parents;

    /**
     * @ManyToMany(targetEntity="User", inversedBy="parents")
     * @JoinTable(name="family",
     *      joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="child_id", referencedColumnName="id")}
     *      )
     */
    private $children;

    public function __construct() {
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