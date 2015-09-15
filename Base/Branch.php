<?php
namespace ezTreeBuilder\Base;

/**
 * Base interface for a branch item
 *
 * @package ezTreeBuilder
 * @author Adrian Tilita <adrian@tilita.ro>
 * @version 1.0
 * @interace
 */
interface Branch
{
    /**
     * Set the tree item id
     * @param int $value
     * @return \TreeBuilder\Base\Branch
     */
    public function setId($value);

    /**
     * Get the tree item id
     * @return int
     */
    public function getId();

    /**
     * Set the parent's id
     * @param int $value
     * @return \TreeBuilder\Base\Branch
     */
    public function setParentId($value);

    /**
     * Get the parent's id
     * @return int
     */
    public function getParentId();

    /**
     * Store data to be passed for the tree item
     * @param mixed $value
     * @return \TreeBuilder\Base\Branch
     */
    public function setData($value);

    /**
     * Retrieve the tree item passed data
     * @return mixed
     */
    public function getData();

    /**
     * Set the Parent of the current branch
     * @param \TreeBuilder\Base\Branch $item
     * @return \TreeBuilder\Base\Branch
     */
    public function setParent(Branch $item);

    /**
     * Verify if the current branch has a parent
     * @return boolean
     */
    public function hasParent();

    /**
     * Get the current branch's parent
     * @return \TreeBuilder\Base\Branch
     * @throws \Exception
     */
    public function getParent();

    /**
     * Adds a new branch child
     * @param \TreeBuilder\Base\Branch $item
     */
    public function addChild(Branch $item);
 
    /**
     * Verify if the current branch has any children
     * @return boolean
     */
    public function hasChildren();

    /**
     * Return the current branch children
     * @return array - array(\TreeBuilder\Base\Branch, \TreeBuilder\Base\Branch)
     * @throws \Exception
     */
    public function getChildren();
}
