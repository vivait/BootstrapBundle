<?php

namespace Vivait\BootstrapBundle\Hydrator;

use Doctrine\ORM\Internal\Hydration\AbstractHydrator;
use PDO;

/**
 * Compatible with Doctrine ORM 2.5
 */
class ListHydrator25 extends AbstractHydrator
{
    protected function hydrateAllData()
    {
        $result = $cache = array();

        foreach ($this->_stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $this->hydrateRowData($row, $result);
        }

        return $result;
    }

    protected function hydrateRowData(array $data, array &$result)
    {
        if (count($data) == 0) {
            return false;
        }

        $keys = array_keys($data);

        // Assume first column is id field
        $id = $data[$keys[0]];

        if (count($data) == 2) {
            // If only one more field assume that this is the value field
            $value = $data[$keys[1]];
        } else {
            // Remove ID field and add remaining fields as value array
            array_shift($data);
            $value = $data;
        }

        $result[$id] = $value;
    }
}