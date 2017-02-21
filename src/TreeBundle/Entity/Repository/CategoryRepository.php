<?php

namespace TreeBundle\Entity\Repository;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class CategoryRepository extends NestedTreeRepository {

    public function getChildrenQueryBuilder($node = null, $direct = false, $sortByField = null, $direction = 'ASC', $includeNode = false) {
        
    }

    public function getChildrenArrayResult($id) { //Using
        $em = $this->getEntityManager();

        $query = $em->createQuery("
        SELECT c
        FROM TreeBundle:Category c
        WHERE c.parent = $id
        ORDER BY c.title ASC
    ");

        return $query->getArrayResult();
    }

}
