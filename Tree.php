<?php
namespace TreeBuilder;

use TreeBuilder\Base\Branch;
use TreeBuilder\Base\Adapter;

/**
 * Tree builder class - generates the enire tree based on \TreeBuilder\Branch
 *
 * @package TreeBuilder
 * @author Adrian Tilita <adrian@tilita.ro>
 * @version 1.0
 */
class Tree
{
    /**
     * Wheather to log debug details
     * @var boolean
     */
    private $debug = false;

    /**
     * Collected debug data if enabled
     * @var string
     */
    private $debugData;

    /**
     * "Dirty" flag - weather to build the tree if it wasn't compiled allready
     * @var boolean
     */
    private $compiled = false;

    /**
     * Store all tree items
     * @var array - array([ID] => TreeBuilder\Branch, [ID] => TreeBuilder\Branch)
     */
    private $branches = array();

    /**
     * Stores the compiled tree
     * @var array
     */
    private $compiledTree = array();

    /**
     * A delagated adapter for the output
     * @var Adapter
     */
    private $delegatedAdapter;

    /**
     * Enables debug mode
     */
    public function enableDebug()
    {
        $this->debug = true;
    }

    /**
     * Show debug state
     * @return boolean
     */
    public function isDebugEnabled()
    {
        return $this->debug;
    }

    /**
     * Get debug data
     * @return string
     * @throws \Exception
     */
    public function getDebugData()
    {
        if ($this->isDebugEnabled() === false) {
            throw new \Exception('Debug is not enabled!');
        }
        return $this->debugData;
    }

    /**
     * Delegate an adapter for the wanted output
     * @param Adapter $adapter
     * @return \TreeBuilder\Tree
     */
    public function registerAdapter(Adapter $adapter)
    {
        $this->delegatedAdapter = $adapter;
        return $this;
    }

    /**
     * Add a new TreeItem
     * @param TreeItem $item
     */
    public function addBranch(Branch $item)
    {
        $this->branches[$item->getId()] = $item;
        $this->compiled = false;
    }

    /**
     * Returns the builded tree
     * @return array
     */
    public function getTree()
    {
        if ($this->compiled === false) {
            $this->buildTree();
        }
        if ($this->delegatedAdapter != null) {
            $this->delegatedAdapter->setRawData($this->branches);
            $this->delegatedAdapter->setTree($this->compiledTree);
            return $this->delegatedAdapter->adapt();
        }
        return $this->compiledTree;
    }

    /**
     * Return a branch by it's id
     * @param int $id
     * @return \TreeBuilder\Base\Branch
     * @throws \Exception
     */
    public function getBranchById($id)
    {
        if (isset($this->branches[$id])) {
            return $this->branches[$id];
        }
        throw new \Exception("Could not find branch with id {$id}!");
    }

    /**
     * Build the tree
     */
    public function buildTree()
    {
        $this->compiledTree = array();
        $rootNodes = 0;
        if ($this->isDebugEnabled()) {
            list($start_usec, $start_sec) = explode(" ", microtime());
        }
        foreach ($this->branches as $id => $item) {
            if ($item->getParentId() != 0) {
                if (isset($this->branches[$item->getParentId()])) {
                    $this->branches[$id] = $item->setParent($this->branches[$item->getParentId()]);
                    $this->branches[$item->getParentId()]->addChild($item);
                } else {
                    if ($this->isDebugEnabled()) {
                        $this->logDebug("Parent of {$item->getId()} with id {$item->getParentId()} is not added."
                        . "Skipping adding the current branch");
                    }
                    continue;
                }
            } else {
                $this->compiledTree[] = $item;
                $rootNodes++;
            }
        }
        if ($this->isDebugEnabled()) {
            $this->logDebug("No root branch declared!");
            list($end_usec, $end_sec) = explode(" ", microtime());
            $diff_sec = intval($end_sec) - intval($start_sec);
            $diff_usec = floatval($end_usec) - floatval($start_usec);
            $this->logDebug("Compiled tree in " . floatval($diff_sec) + $diff_usec);
        }
        $this->compiled = true;
    }

    /**
     * Add to debug data
     * @param string $string
     */
    private function logDebug($string)
    {
        $this->debugData .= $string . '<br/>';
    }
}
