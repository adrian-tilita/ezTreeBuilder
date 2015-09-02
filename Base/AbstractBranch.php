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
}
