<?php
namespace TreeBuilder;

use TreeBuilder\Tree;
use TreeBuilder\Item\TreeItem;
use TreeBuilder\Adapter\ArrayAdapter;

require 'autoload.php';

/**
 * Items
 */
$items = array();
$items[] = array('id' => 502, 'parent_id' => 0, 'data' => array());
$items[] = array('id' => 200, 'parent_id' => 502, 'data' => array());
$items[] = array('id' => 503, 'parent_id' => 0, 'data' => array());
$items[] = array('id' => 504, 'parent_id' => 506, 'data' => array());
$items[] = array('id' => 505, 'parent_id' => 200, 'data' => array());
$items[] = array('id' => 506, 'parent_id' => 507, 'data' => array());
$items[] = array('id' => 507, 'parent_id' => 502, 'data' => array());

// instantiate treebuilder
$treeBuilder = new Tree();
foreach ($items as $item) {
    $data = new TreeItem();
    $data->setId($item['id']);
    $data->setParentId($item['parent_id']);
    $data->setData($item['data']);
    $treeBuilder->addBranch($data);
}
// get the normal type of tree
$treeBuilder->setBuildMode($treeBuilder::BUILD_MODE_COMPLETE);
$objectTree = $treeBuilder->getTree();
echo "<pre>";
var_dump($objectTree);
echo "</pre>";

// add an adapter
$array = $treeBuilder->registerAdapter(new ArrayAdapter())
        ->getTree();
echo "<pre>";
var_dump($array);
echo "</pre>";

$branch = $treeBuilder->getBranchById(507);
echo "<pre>";
var_dump($branch);
echo "</pre>";
