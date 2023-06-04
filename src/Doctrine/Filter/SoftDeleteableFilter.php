<?php

namespace App\Doctrine\Filter;

use App\Entity\Trait\SoftDeleteable;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class SoftDeleteableFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        // Check if the entity uses the SoftDeleteable trait
        if (!in_array(SoftDeleteable::class, $targetEntity->getReflectionClass()->getTraitNames())) {
            return '';
        }

        return sprintf('%s.deleted_at is NULL', $targetTableAlias);
    }
}
