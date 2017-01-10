<?php
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
* @ORM\Entity
* @ORM\Table(name="products")
*/
class Product
{
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 */
	private $id;
	/**
     * @ORM\Column
     * @Assert\Length(min=3, max=30)
     * @Assert\NotBlank
     * @Assert\Regex("/[[:alnum:][:space:]]/", message="This value should be based on letters and numbers only.")
	 */
	private $productName;
	/**
	 * @ORM\Column(type="text")
     * @Assert\Length(min=5)
     * @Assert\NotBlank
     * @Assert\Regex("/^\w+/")
	 */
	private $description;

	/**
	 * @ORM\Column(type="integer")
     * @Assert\Range(min=0, max=10000000)
	 */
	private $price;
	/**
	 * @ORM\Column(type="simple_array")
	 */
	private $imageName;
	/**
	 * @ORM\Column(type="simple_array")
	 */
	private $imagePath;
	/**
	 * @ORM\ManytoOne(targetEntity="Category", inversedBy="products")
	 */
	private $category;
    /**
     * @ORM\Column(length=10)
     */
    private $discount;

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
     * Set productName
     *
     * @param string $productName
     *
     * @return Product
     */
    public function setProductName($productName)
    {
        $this->productName = $productName;

        return $this;
    }

    /**
     * Get productName
     *
     * @return string
     */
    public function getProductName()
    {
        return $this->productName;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set imageName
     *
     * @param string $imageName
     *
     * @return Product
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
     * @return Product
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
     * Set category
     *
     * @param string $category
     *
     * @return Product
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set discount
     *
     * @param string $discount
     *
     * @return Product
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return string
     */
    public function getDiscount()
    {
        return $this->discount;
    }
}
