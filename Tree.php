<?php
namespace ezTreeBuilder;

use ezTreeBuilder\Base\Branch;
use ezTreeBuilder\Base\Adapter;

/**
 * Tree builder class - generates the enire tree based on \TreeBuilder\Branch
 *
 * @package ezTreeBuilder
 * @author Adrian Tilita <adrian@tilita.ro>
 * @version 1.0
 */
class Tree
{
    /**
     * Set the build mode simple and add a depth field for branches
     * @const int
     */
    const BUILD_MODE_DEPTH = 0;

    /**
     * Builds simple mode and add left->right fields
     * @const int
     */
    const BUILD_MODE_LEFT_RIGHT = 1;

    /**
     * Builds the full type off tree, containing all off the above
     * @const int
     */
    const BUILD_MODE_COMPLETE = 2;

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
     * Defines the build mode off the tree
     * @var int
     */
    private $buildMode;

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
     * Set the build mode identified by local constants
     * @param int $value
     * @return \TreeBuilder\Tree
     */
    public function setBuildMode($value)
    {
        $this->buildMode = $value;
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
            if ($this->delegatedAdapter != null) {
                $this->delegatedAdapter->setRawData($this->branches);
                $this->delegatedAdapter->setTree(array($this->branches[$id]));
                $adaptedData = $this->delegatedAdapter->adapt();
                return isset($adaptedData[0]) ? $adaptedData[0] : $adaptedData;
            }
            return $this->branches[$id];
        }
        throw new \Exception("Could not find branch with id {$id}!");
    }

    /**
     * Build the tree
     */
    public function buildTree()
    {
        // reset compiled tree
        $this->compiledTree = array();
        if ($this->isDebugEnabled()) {
            list($start_usec, $start_sec) = explode(" ", microtime());
        }
        // build for simple mode
        foreach ($this->branches as $id => $item) {
            $this->buildBranchReference($id, $item);
        }
        // build according to the required build mode
        if (!empty($this->compiledTree)) {
            switch ($this->buildMode) {
                case (self::BUILD_MODE_DEPTH):
                    $this->addDepth($this->compiledTree);
                    break;
                case (self::BUILD_MODE_LEFT_RIGHT):
                    $this->addLeftRight($this->compiledTree);
                    break;
                case (self::BUILD_MODE_COMPLETE):
                    $this->addDepth($this->compiledTree);
                    $this->addLeftRight($this->compiledTree);
                    break;
            }
        }
        if ($this->isDebugEnabled()) {
            if (count($this->compiledTree)) {
                $this->logDebug("No root branch declared!");
            }
            list($end_usec, $end_sec) = explode(" ", microtime());
            $diff_sec = intval($end_sec) - intval($start_sec);
            $diff_usec = floatval($end_usec) - floatval($start_usec);
            $this->logDebug("Compiled tree in " . floatval($diff_sec) + $diff_usec);
        }
        $this->compiled = true;
    }

    /**
     * Build the tree parent->child references
     * @param int $id
     * @param Branch $item
     */
    private function buildBranchReference($id, Branch $item)
    {
        if ($item->getParentId() != 0) {
            if (isset($this->branches[$item->getParentId()])) {
                $this->branches[$id] = $item->setParent($this->branches[$item->getParentId()]);
                $this->branches[$item->getParentId()]->addChild($item);
            } else {
                if ($this->isDebugEnabled()) {
                    $this->logDebug("Parent of {$item->getId()} with id {$item->getParentId()} is not added."
                    . "Skipping adding the current branch");
                }
            }
        } else {
            $this->compiledTree[] = $item;
        }
    }

    /**
     * Set the depth filed for items
     * @param array $items
     * @param int $depth
     */
    private function addDepth(array $items, $depth = 0)
    {
        foreach ($items as $item) {
            $item->setDepth($depth);
            if ($item->hasChildren()) {
                $this->addDepth($item->getChildren(), $depth + 1);
            }
        }
    }

    /**
     * Add left->right fields to tree
     * @param array $item
     * @param int $left
     * @return int
     */
    private function addLeftRight(array $items, $left = 1)
    {
        $right = $left+1;
        foreach ($items as $item) {
            $item->setLeft($left);
            if ($item->hasChildren()) {
                $right = $this->addLeftRight($item->getChildren(), $left + 1);
                $right++;
            } else {
                $right = $left + 1;
            }
            $item->setRight($right);
            $left = $right + 1;
        }
        return $right;
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
