<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Post
 *
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PostRepository")
 */
class Post
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", columnDefinition="timestamp default current_timestamp on update current_timestamp")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="bucket", type="string", length=255, nullable=true)
     */
    private $imgBucket;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Please, upload a image file.")
     * @Assert\File(mimeTypes={ "image/*" })
     */
    private $imgName;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="text", nullable=true)
     */
    private $imgLink;

    /**
     * @var int
     *
     * @ORM\Column(name="size", type="integer", nullable=true)
     */
    private $imgSize;


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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Post
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Now we tell doctrine that before we persist or update we call the updatedTimestamps() function.
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        if($this->getDate() == null)
        {
            $this->setDate(new \DateTime(date('Y-m-d H:i:s')));
        }
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Post
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
     * Set imgBucket
     *
     * @param string $imgBucket
     *
     * @return Post
     */
    public function setImgBucket($imgBucket)
    {
        $this->imgBucket = $imgBucket;

        return $this;
    }

    /**
     * Get imgBucket
     *
     * @return string
     */
    public function getImgBucket()
    {
        return $this->imgBucket;
    }

    /**
     * Set imgName
     *
     * @param string $imgName
     *
     * @return Post
     */
    public function setImgName($imgName)
    {
        $this->imgName = $imgName;

        return $this;
    }

    /**
     * Get imgName
     *
     * @return string
     */
    public function getImgName()
    {
        return $this->imgName;
    }

    /**
     * Set imgLink
     *
     * @param string $imgLink
     *
     * @return Post
     */
    public function setImgLink($imgLink)
    {
        $this->imgLink = $imgLink;

        return $this;
    }

    /**
     * Get imgLink
     *
     * @return string
     */
    public function getImgLink()
    {
        return $this->imgLink;
    }

    /**
     * Set imgSize
     *
     * @param integer $imgSize
     *
     * @return Post
     */
    public function setImgSize($imgSize)
    {
        $this->imgSize = $imgSize;

        return $this;
    }

    /**
     * Get imgSize
     *
     * @return integer
     */
    public function getImgSize()
    {
        return $this->imgSize;
    }
}
