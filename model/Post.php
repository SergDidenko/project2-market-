<?php
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity
 * @ORM\Table(name="posts")
 * @ORM\HasLifeCycleCallbacks
 */
class Post{
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 */
	private $id;
	/**
	 * @ORM\Column
     * @Assert\Length(max=20)
     * @Assert\NotBlank
	 */
	private $title;
	/**
	 * @ORM\Column(type="text")
     * @Assert\Length(max=500)
     * @Assert\NotBlank
	 */
	private $content;
	/**
	 * @ORM\Column(length=50)
	 */
	private $imageName;
	/**
	 * @ORM\Column(length=50)
	 */
	private $imagePath;
	/**
	 * @ORM\Column(type="datetime")
	 */
	private $createAt;
	/**
	 * @ORM\ManytoMany(targetEntity="Tag", inversedBy="posts")
	 * @ORM\JoinTable(name="posts_tags")
	 */
	private $tags;
	/**
	 * @ORM\ManytoOne(targetEntity="User", inversedBy="posts")
	 */
	private $user;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set content
     *
     * @param string $content
     *
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set imageName
     *
     * @param string $imageName
     *
     * @return Post
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get imageName
     *
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * Set imagePath
     *
     * @param string $imagePath
     *
     * @return Post
     */
    public function setImagePath($imagePath)
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    /**
     * Get imagePath
     *
     * @return string
     */
    public function getImagePath()
    {
        return $this->imagePath;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return Post
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;

        return $this;
    }
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setInitialDate(){
        $this->setCreateAt(new DateTime('now'));
    }
    /**
     * Get createAt
     *
     * @return \DateTime
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * Add tag
     *
     * @param \Tag $tag
     *
     * @return Post
     */
    public function addTag(\Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \Tag $tag
     */
    public function removeTag(\Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set user
     *
     * @param \User $user
     *
     * @return Post
     */
    public function setUser(\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \User
     */
    public function getUser()
    {
        return $this->user;
    }
    static public function countPost($a){
        $count=0;
        foreach ($a as $value) {
           $count++;
        }
        return $count;
    }
}
