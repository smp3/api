<?php

namespace SMP3Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tasks")
 */
class Task
{

    const
        STATUS_PENDING = 'pending',
        STATUS_RUNNING = 'running',
        STATUS_FINISHED = 'finished'

    ;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="SMP3Bundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\Column(type="string")
     */
    protected $type;

    /**
     * @ORM\Column(type="string")
     */
    protected $status;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdDateTime;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $statusChangeDateTime;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $additionalData;

    public function __construct($type)
    {
        $this->setType($type);
        $this->createdDateTime = new \DateTime('now');
        $this->setStatus(Task::STATUS_PENDING);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {

        $this->status = $status;
        $this->statusChangeDateTime = new \DateTime('now');

        return $this;
    }

    public function getCreatedDateTime()
    {
        return $this->createdDateTime;
    }

    public function getStatusChangeDateTime()
    {
        return $this->statusChangeDateTime;
    }
    
    public function getAdditionalData()
    {
        return $this->additionalData;
    }
    
    public function setAdditionalData($additionalData)
    {
        $this->additionalData = $additionalData;
        
        return $this;
    }
}
