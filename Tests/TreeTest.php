<?php
namespace TreeBuilder\Tests;

use TreeBuilder\Tree;
use TreeBuilder\Item\TreeItem;

class TreeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Simple unit test for parent
     */
    public function testSimpleParent()
    {
        $items = $this->getTestData();
        $treeBuilder = $this->buildTree($items);

        $item = $treeBuilder->getBranchById(2);
        $this->assertTrue($item->hasParent());
        $this->assertEquals($item->getParent()->getId(), 1);
    }

    /**
     * Simple unit test for child
     */
    public function testSimpleChild()
    {
        $items = $this->getTestData();
        $treeBuilder = $this->buildTree($items);

        $parent = $treeBuilder->getBranchById(1);
        $child = $treeBuilder->getBranchById(2);
        $this->assertTrue($parent->hasChildren());
        $this->assertContains($child, $parent->getChildren());
    }

    /**
     * Simple unit test for incorrect data
     */
    public function testIncorrectData()
    {
        $items = array();
        $items[] = array('id' => 1, 'parent_id' => 1);
        $items[] = array('id' => 2, 'parent_id' => 3);
        $treeBuilder = $this->buildTree($items);
        // No root branch defined
        $this->assertEmpty($treeBuilder->getTree());

        // add a root parent
        $items[] = array('id' => 3, 'parent_id' => 0);
        $treeBuilder = $this->buildTree($items);

        // Root branch defined
        $this->assertNotEmpty($treeBuilder->getTree());
    }

    /**
     * Test parents in complex form
     * NOT Recomanded type of test - too much nested logic for a test
     */
    public function testComplexParents()
    {
        // get data
        $items = $this->getComplexTestData();
        $treeBuilder = $this->buildTree($items);
        foreach ($items as $item) {
            $branch = $treeBuilder->getBranchById($item['id']);
            if ($item['parent_id'] == 0) {
                $this->assertFalse($branch->hasParent());
            } else {
                $this->assertTrue($branch->hasParent());
                $this->assertEquals($branch->getParent()->getId(), $item['parent_id']);
            }
        }
    }

    /**
     * Test Children in complex form
     * NOT Recomanded type of test - too much nested logic for a test
     */
    public function testComplexChildren()
    {
        // get data
        $items = $this->getComplexTestData();
        $treeBuilder = $this->buildTree($items);
        // associate items with childs
        $parents = array();
        $non_parents = array();
        $parentsToChildren = array();
        foreach ($items as $item) {
            $parents[] = $item['parent_id'];
        }
        foreach ($items as $item) {
            if (!in_array($item['id'], $parents)) {
                $non_parents[] = $item['id'];
            }
            if (!isset($parentsToChildren[$item['parent_id']])) {
                $parentsToChildren[$item['parent_id']] = array();
            }
            $parentsToChildren[$item['parent_id']][] = $item['id'];
        }
        // actual test
        foreach ($items as $item) {
            $branch = $treeBuilder->getBranchById($item['id']);
            if (in_array($item['id'], $non_parents)) {
                $this->assertFalse($branch->hasChildren());
            } else {
                $this->assertTrue($branch->hasChildren());
                $children = $branch->getChildren();
                foreach ($children as $child) {
                    $this->assertContains($child->getId(), $parentsToChildren[$item['id']]);
                }
            }
        }
    }

    /**
     * Get simple set of data for simple unit tests
     * @return array
     */
    private function getTestData()
    {
        $items = array();
        $items[] = array('id' => 1, 'parent_id' => 0);
        $items[] = array('id' => 2, 'parent_id' => 1);
        return $items;
    }

    /**
     * Builds a set off tree items
     * @return array
     */
    private function getComplexTestData()
    {
        $items = array();
        $items[] = array('id' => 1, 'parent_id' => 0, 'data' => array());
        $items[] = array('id' => 2, 'parent_id' => 1, 'data' => array());
        $items[] = array('id' => 3, 'parent_id' => 2, 'data' => array());
        $items[] = array('id' => 4, 'parent_id' => 0, 'data' => array());
        $items[] = array('id' => 5, 'parent_id' => 3, 'data' => array());
        $items[] = array('id' => 6, 'parent_id' => 3, 'data' => array());
        $items[] = array('id' => 7, 'parent_id' => 6, 'data' => array());
        $items[] = array('id' => 8, 'parent_id' => 6, 'data' => array());
        $items[] = array('id' => 9, 'parent_id' => 8, 'data' => array());
        $items[] = array('id' => 10, 'parent_id' => 7, 'data' => array());
        return $items;
    }

    /**
     * Build a tree
     * @param array $items
     * @return Tree
     */
    private function buildTree($items, $debug = false)
    {
        // build tree
        $treeBuilder = new Tree();
        if ($debug === true) {
            $treeBuilder->enableDebug();
        }
        foreach ($items as $item) {
            $branch = new TreeItem();
            $branch->setId($item['id']);
            $branch->setParentId($item['parent_id']);
            if (isset($item['data'])) {
                $branch->setData($item['data']);
            }
            $treeBuilder->addBranch($branch);
        }
        $treeBuilder->buildTree();
        return $treeBuilder;
    }
}
