<?php

namespace Nerdstorm\GoogleBooks\Entity;

use Nerdstorm\GoogleBooks\Annotations\Definition as Annotations;

/**
 * Class Volumes
 *
 * The volumess collection allows you to retrieve volumes from Google Books.
 *
 * @Annotations\Object("books#volumes")
 */
class Volumes implements EntityInterface
{
    /**
     * Number of book volumes found.
     *
     * @var int
     * @Annotations\JsonProperty("totalItems", type="int")
     */
    protected $total_items;

    /**
     * Array of book volume objects.
     *
     * @var Volume[]
     * @Annotations\JsonProperty("items", type="object", className="Nerdstorm\GoogleBooks\Entity\Volume")
     */
    protected $items;

    /**
     * @return int
     */
    public function getTotalItems()
    {
        return $this->total_items;
    }

    /**
     * @param int $total_items
     *
     * @return Volumes
     */
    public function setTotalItems($total_items)
    {
        $this->total_items = $total_items;

        return $this;
    }

    /**
     * @return Volume[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param Volume[] $items
     *
     * @return Volumes
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

}