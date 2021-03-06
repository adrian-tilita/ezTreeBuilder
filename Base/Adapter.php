<?php
namespace ezTreeBuilder\Base;

/**
 * Minimum of an adapter public methods
 *
 * @package ezTreeBuilder
 * @author Adrian Tilita <adrian@tilita.ro>
 * @inteface
 * @version 1.0
 */
interface Adapter
{
    /**
     * Sets the raw branches
     * @param array $data
     * @return \TreeBuilder\Base\Adapter
     */
    public function setRawData(array $data);

    /**
     * Sets the tree data
     * @param array $data
     * @return \TreeBuilder\Base\Adapter
     */
    public function setTree(array $data);

    /**
     * The logic of the adapt
     * @return mixed - The actual adapter result
     */
    public function adapt();
}
