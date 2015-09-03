<?php
namespace TreeBuilder\Base;

use TreeBuilder\Base\Branch;

/**
 * Branch asbtract class - Limited concern to \TreeBuilder\Tree
 *
 * @package TreeBuilder
 * @author Adrian Tilita <adrian@tilita.ro>
 * @version 1.0
 * @abstract
 */
abstract class AbstractBranch
{
    /**
     * Container for current branch children
     * @var array
     */
    private $children = array();

    /**
     * Reference to the current branch parent
     * @var \TreeBuilder\Base\Branch
     */
    private $parent;

    /**
     * The depth of the item
     * @var int
     */
    private $depth;

    /**
     * The left number of the branch
     * @var int
     */
    private $left;

    /**
     * The right number of the branch
     * @var int
     */
    private $right;

    /**
     * Set the Parent of the current branch
     * @param \TreeBuilder\Base\Branch $item
     * @return \TreeBuilder\Base\Branch
     */
    public function setParent(Branch $item)
    {
        $this->parent = $item;
        return $this;
    }

    /**
     * Verify if the current branch has a parent
     * @return boolean
     */
    public function hasParent()
    {
        if (!isset($this->parent)) {
            return false;
        }
        return true;
    }

    /**
     * Get the current branch's parent
     * @return \TreeBuilder\Base\Branch
     * @throws \Exception
     */
    public function getParent()
    {
        if ($this->hasParent() === false) {
            throw new \Exception('The current branch has no parent set!');
        }
        return $this->parent;
    }

    /**
     * Adds a new branch child
     * @param \TreeBuilder\Base\Branch $item
     */
    public function addChild(Branch $item)
    {
        $this->children[] = $item;
    }
 
    /**
     * Verify if the current branch has any children
     * @return boolean
     */
    public function hasChildren()
    {
        if (empty($this->children)) {
            return false;
        }
        return true;
    }

    /**
     * Return the current branch children
     * @return array - array(\TreeBuilder\Base\Branch, \TreeBuilder\Base\Branch)
     * @throws \Exception
     */
    public function getChildren()
    {
        if ($this->hasChildren() === false) {
            throw new \Exception('The current branch has no childs!');
        }
        return $this->children;
    }

    /**
     * Set the branch depth
     * @param int $depth
     * @return \TreeBuilder\Base\AbstractBranch
     */
    public function setDepth($depth)
    {
        $this->depth = $depth;
        return $this;
    }

    /**
     * Get the branch depth
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * Set Tree Left value
     * @param int $value
     * @return \TreeBuilder\Base\AbstractBranch
     */
    public function setLeft($value)
    {
        $this->left = $value;
        return $this;
    }

    /**
     * Get Tree Left value
     * @return int
     */
    public function getLeft()
    {
        return $this->left;
    }


    /**
     * Set Tree Right value
     * @param int $value
     * @return \TreeBuilder\Base\AbstractBranch
     */
    public function setRight($value)
    {
        $this->right = $value;
        return $this;
    }

    /**
     * Get Tree Right value
     * @return int
     */
    public function getRight()
    {
        return $this->right;
    }

    /**
     * Get the entire Item as array
     * @return array
     */
    public function getAsArray()
    {
        $returnedArray = array();
        $returnedArray['id'] = $this->getId();
        $returnedArray['parent_id'] = $this->hasParent() ? $this->getParentId() : 0;
        $returnedArray['data'] = $this->getData();
        $returnedArray['depth'] = $this->getDepth();
        $returnedArray['left'] = $this->getLeft();
        $returnedArray['right'] = $this->getRight();
        return $returnedArray;
    }
}
