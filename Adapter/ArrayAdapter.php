<?php
namespace ezTreeBuilder\Adapter;

use ezTreeBuilder\Base\AbstractAdapter;
use ezTreeBuilder\Base\Adapter;

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
            $returnedArray[$iteration] = $item->getAsArray();
            if ($item->hasChildren() === true) {
                $returnedArray[$iteration]['children'] = $this->getArrayItems($item->getChildren());
            } else {
                $returnedArray[$iteration]['children'] = array();
            }
            $iteration++;
        }
        return $returnedArray;
    }
}
