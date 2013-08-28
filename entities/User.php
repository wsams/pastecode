<?php

/**
 * @Entity
 * @Table(name="users")
 */
class User {
    
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;
    
    /** @Column(length=64, unique=true) */
    private $username;
    
    /** @Column(length=60) */
    private $password;
    
    /** @Column(length=128, unique=true) */
    private $email;
    
    /** @Column(length=64) */
    private $firstName;
    
    /** @Column(length=64) */
    private $lastName;
    
    /** @Column(type="datetime") */
    private $registeredOn;
    
    /** @Column(length=40) */
    private $activationKey;
    
    /** @Column(type="boolean") */
    private $activated;

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
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
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
     *
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
     * Set registeredOn
     *
     * @param \DateTime $registeredOn
     *
     * @return User
     */
    public function setRegisteredOn($registeredOn)
    {
        $this->registeredOn = $registeredOn;

        return $this;
    }

    /**
     * Get registeredOn
     *
     * @return \DateTime 
     */
    public function getRegisteredOn()
    {
        return $this->registeredOn;
    }

    /**
     * Set activationKey
     *
     * @param string $activationKey
     *
     * @return User
     */
    public function setActivationKey($activationKey)
    {
        $this->activationKey = $activationKey;

        return $this;
    }

    /**
     * Get activationKey
     *
     * @return string 
     */
    public function getActivationKey()
    {
        return $this->activationKey;
    }

    /**
     * Set activated
     *
     * @param boolean $activated
     *
     * @return User
     */
    public function setActivated($activated)
    {
        $this->activated = $activated;

        return $this;
    }

    /**
     * Get activated
     *
     * @return boolean 
     */
    public function getActivated()
    {
        return $this->activated;
    }
}
