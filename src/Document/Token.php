<?php

namespace App\Document;

use App\Repository\TokenRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Field;

/**
 * @MongoDB\Document(repositoryClass=TokenRepository::class)
 */
class Token
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @Field(type="string")
     */
    protected $user;

    /**
     * @Field(type="string")
     */
    protected $hash;

    /**
     * @Field(type="date_immutable", nullable=true)
     */
    protected $created_at;

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function setHash($hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($createdAt): self
    {
        $this->created_at = $createdAt;

        return $this;
    }
}