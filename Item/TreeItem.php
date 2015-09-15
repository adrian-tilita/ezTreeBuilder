<?php
namespace ezTreeBuilder\Item;

use ezTreeBuilder\Base\Branch;
use ezTreeBuilder\Base\AbstractBranch;

/**
 * TreeItem class - Limited concern to caller
 *
 * Used to build \TreeBuilder\Base\Branch to be used by \TreeBuilder\Tree
 *
 * @package TreeBuilder
 * @author Adrian Tilita <adrian@tilita.ro>
 * @version 1.0
 */
class TreeItem extends AbstractBranch implements Branch
{
    /**
     * An identifier for the tree item
     * @var int
     */
    protected $id;

    /**
     * The identifier of the parent item - default to 0 for root node
     * @var int
     */
    protected $parentId = 0;

    /**
     * Passed data for the tree item
     * @var mixed
     */
    protected $data;

    /**
     * Set the tree item id
     * @param int $value
     * @return \TreeBuilder\Base\Branch
     */
    public function setId($value)
    {
        $this->id = $value;
        return $this;
    }

    /**
     * Get the tree item id
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the parent's id
     * @param int $value
     * @return \TreeBuilder\Base\Branch
     */
    public function setParentId($value)
    {
        $this->parentId = $value;
        return $this;
    }

    /**
     * Get the parent's id
     * @return int
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Store data to be passed for the tree item
     * @param mixed $value
     * @return \TreeBuilder\Base\Branch
     */
    public function setData($value)
    {
        $this->data = $value;
        return $this;
    }

    /**
     * Retrieve the tree item passed data
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}
