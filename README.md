# TreeBuilder
Build a tree from a set of given Items based on parent

# Example usage:
```php
require 'vendors/autoload.php';

$items = array();
$items[] = array('id' => 1, 'parent_id' => 0, 'data' => 'Laptops and Accessories');
$items[] = array('id' => 2, 'parent_id' => 0, 'data' => 'TVs and Accesories');
$items[] = array('id' => 3, 'parent_id' => 1, 'data' => 'Laptops');
$items[] = array('id' => 4, 'parent_id' => 1, 'data' => 'Accesories');
$items[] = array('id' => 5, 'parent_id' => 4, 'data' => 'RAM');
$items[] = array('id' => 6, 'parent_id' => 4, 'data' => 'Hard drives');
$items[] = array('id' => 7, 'parent_id' => 4, 'data' => 'Laptop Bags');
$items[] = array('id' => 8, 'parent_id' => 6, 'data' => 'SSD');
$items[] = array('id' => 9, 'parent_id' => 6, 'data' => 'SSHD');


// instantiate treebuilder
$treeBuilder = new ezTreeBuilder\Tree();
foreach ($items as $item) {
    $data = new ezTreeBuilder\Item\TreeItem();
    $data->setId($item['id']);
    $data->setParentId($item['parent_id']);
    $data->setData($item['data']);
    $treeBuilder->addBranch($data);
}

// get the normal type of tree
$treeBuilder->setBuildMode($treeBuilder::BUILD_MODE_COMPLETE);
$objectTree = $treeBuilder->getTree();
echo "Main Categories:\n";
foreach ($objectTree as $tree) {
    echo "\t" . $tree->getData() . "\n";
}

// add an adapter
$array = $treeBuilder->registerAdapter(new ezTreeBuilder\Adapter\ArrayAdapter())
        ->getTree();
print_r($array);

$branch = $treeBuilder->getBranchById(6);
var_dump($branch);

// add a second adapter
$array = $treeBuilder->registerAdapter(new ezTreeBuilder\Adapter\LinearArrayAdapter())
        ->getTree();
var_dump($array);
```