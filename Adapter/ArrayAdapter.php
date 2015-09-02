<?php
namespace TreeBuilder\Adapter;

use TreeBuilder\Base\AbstractAdapter;
use TreeBuilder\Base\Adapter;
use TreeBuilder\Item\TreeItem;

class ArrayAdapter extends AbstractAdapter implements Adapter
{
    /**
     * Adapt a tree to Array
     * @return array
     */
    public function adapt()
    {
        $treeData = $this->getTree();
        return $this->getArrayItems($treeData);
    }

    /**
     * Loop throw the array and recursive call itself on children
     * @param array $items
     * @return array
     */
    private function getArrayItems($items)
    {
        $returnedArray = array();
        $iteration = 0;
        foreach ($items as $item) {
            $returnedArray[$iteration] = $this->branchToArray($item);
            if ($item->hasChildren() === true) {
                $returnedArray[$iteration]['children'] = $this->getArrayItems($item->getChildren());
            } else {
                $returnedArray[$iteration]['children'] = array();
            }
            $iteration++;
        }
        return $returnedArray;
    }

    /**
     * Converts a tree branch to an array
     * @param TreeItem $item
     * @return array
     */
    private function branchToArray(TreeItem $item)
    {
        $returnedArray = array();
        $returnedArray['id'] = $item->getId();
        $returnedArray['parent_id'] = $item->hasParent() ? $item->getParentId() : 0;
        $returnedArray['data'] = $item->getData();
        return $returnedArray;
    }
}
