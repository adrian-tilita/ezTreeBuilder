<?php
namespace ezTreeBuilder\Base;

/**
 * The base of an Adapter
 *
 * @package ezTreeBuilder
 * @author Adrian Tilita <adrian@tilita.ro>
 * @abstract
 * @version 1.0
 */
abstract class AbstractAdapter implements Adapter
{
    /**
     * Raw branches if needed
     * @var array
     */
    private $rawData;

    /**
     * Builded tree
     * @var array
     */
    private $tree;

    /**
     * Sets the raw branches
     * @param array $data
     * @return Adapter
     */
    public function setRawData(array $data)
    {
        $this->rawData = $data;
        return $this;
    }

    /**
     * Returnes the raw data
     * @return array
     */
    protected function getRawData()
    {
        return $this->rawData;
    }

    /**
     * Sets the tree data
     * @param array $data
     * @return Adapter
     */
    public function setTree(array $data)
    {
        $this->tree = $data;
        return $this;
    }

    /**
     * Get the tree array
     * @return array
     */
    protected function getTree()
    {
        return $this->tree;
    }
}
