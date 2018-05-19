<?php

namespace App\Model;

use App\Model;
use App\ORM\TenantAwareInterface;

/**
 * @Entity(repositoryClass="\App\Repository\VariantRepository")
 * @Table(name="variants")
 **/
class Variant extends Model implements TenantAwareInterface
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue **/
    protected $id;

    /**
     * @Column(type="string", length=45)
     */
    protected $name;

    /**
     * @Column(type="string", length=45)
     */
    protected $option1;

    /**
     * @Column(type="string", length=45)
     */
    protected $option2;
}
