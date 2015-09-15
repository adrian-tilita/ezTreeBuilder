<?php
namespace ezTreeBuilder\Adapter;

use ezTreeBuilder\Base\AbstractAdapter;
use ezTreeBuilder\Base\Adapter;
use ezTreeBuilder\Item\TreeItem;

class LinearArrayAdapter extends AbstractAdapter implements Adapter
{
    /**
     * Container for the array branches
     * @var array
     */
    private $lines = array();

    /**
     * Adapt a tree to Array
     * @return array
     */
    public function adapt()
    {
        $treeData = $this->getTree();
        $this->getArrayItems($treeData);
        return $this->lines;
    }

    /**
     * Loop throw the array and recursive call itself on children adding items to lines
     * @param array $items
     */
    private function getArrayItems($items)
    {
        foreach ($items as $item) {
            $this->lines[] = $item->getAsArray();
            if ($item->hasChildren() === true) {
                $this->getArrayItems($item->getChildren());
            }
        }
    }
}
