<?php

/**
 * @Entity
 * @Table(name="pastes")
 */
class Paste {
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;
    
    /** @Column(length=40) */
    private $uniqueKey;
    
    /** @ManyToOne(targetEntity="User") */
    private $owner;
    
    /**
     * @ManyToMany(targetEntity="User")
     * @JoinTable(
     *     name="pastes_guests",
     *     joinColumns={@JoinColumn(name="userId", referencedColumnName="id")},
     *     inverseJoinColumns={@JoinColumn(name="pasteId", referencedColumnName="id", unique=true)}
     * )
     * @Column(nullable=true)
     */
    private $guests;
    
    /** @Column(length=255) */
    private $title;
    
    /** @Column(type="datetime") */
    private $postedOn;
    
    /** @Column(type="datetime") */
    private $expiresOn;
    
    /** @Column(length=128) */
    private $language;
    
    /** @Column(type="integer",length=1) */
    private $visibility;
    
    /** @Column(type="text") */
    private $contents;

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
     * Set uniqueKey
     *
     * @param string $uniqueKey
     *
     * @return Paste
     */
    public function setUniqueKey($uniqueKey)
    {
        $this->uniqueKey = $uniqueKey;

        return $this;
    }

    /**
     * Get uniqueKey
     *
     * @return string 
     */
    public function getUniqueKey()
    {
        return $this->uniqueKey;
    }

    /**
     * Set guests
     *
     * @param string $guests
     *
     * @return Paste
     */
    public function setGuests($guests)
    {
        $this->guests = $guests;

        return $this;
    }

    /**
     * Get guests
     *
     * @return string 
     */
    public function getGuests()
    {
        return $this->guests;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Paste
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set postedOn
     *
     * @param \DateTime $postedOn
     *
     * @return Paste
     */
    public function setPostedOn($postedOn)
    {
        $this->postedOn = $postedOn;

        return $this;
    }

    /**
     * Get postedOn
     *
     * @return \DateTime 
     */
    public function getPostedOn()
    {
        return $this->postedOn;
    }

    /**
     * Set expiresOn
     *
     * @param \DateTime $expiresOn
     *
     * @return Paste
     */
    public function setExpiresOn($expiresOn)
    {
        $this->expiresOn = $expiresOn;

        return $this;
    }

    /**
     * Get expiresOn
     *
     * @return \DateTime 
     */
    public function getExpiresOn()
    {
        return $this->expiresOn;
    }

    /**
     * Set language
     *
     * @param string $language
     *
     * @return Paste
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string 
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set visibility
     *
     * @param integer $visibility
     *
     * @return Paste
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;

        return $this;
    }

    /**
     * Get visibility
     *
     * @return integer 
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * Set contents
     *
     * @param string $contents
     *
     * @return Paste
     */
    public function setContents($contents)
    {
        $this->contents = $contents;

        return $this;
    }

    /**
     * Get contents
     *
     * @return string 
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * Set owner
     *
     * @param \User $owner
     *
     * @return Paste
     */
    public function setOwner(\User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \User 
     */
    public function getOwner()
    {
        return $this->owner;
    }
}
