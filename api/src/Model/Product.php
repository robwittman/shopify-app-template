<?php

namespace App\Model;

use App\Model;
use App\ORM\TenantAwareInterface;
use Doctrine\ORM\Mapping;

/**
 * @Entity(repositoryClass="\App\Repository\ProductRepository")
 * @Table(name="products")
 **/
class Product extends Model implements TenantAwareInterface
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue **/
    protected $id;

    /**
     * @Column(type="text", nullable=true)
     **/
    protected $body_html;

    /**
     * @Column(type="datetime")
     */
    protected $created_at;

    /**
     * @Column(type="string", length=145, nullable=true)
     */
    protected $handle;

    /**
     * @Column(type="text", nullable=true)
     */
    protected $options;

    /**
     * @Column(type="string", length=45, nullable=true)
     */
    protected $product_type;

    /**
     * @Column(type="datetime", nullable=true)
     */
    protected $published_at;

    /**
     * @Column(type="string", length=45, nullable=true)
     */
    protected $published_scope;

    /**
     * @Column(type="string", length=145, nullable=true)
     */
    protected $tags;

    /**
     * @Column(type="string", length=45, nullable=true)
     */
    protected $template_suffix;

    /**
     * @Column(type="string", length=145, nullable=true)
     */
    protected $title;

    /**
     * @Column(type="string", length=45, nullable=true)
     */
    protected $metafields_global_title_tag;

    /**
     * @Column(type="string", length=45, nullable=true)
     */
    protected $metafields_global_description_tag;

    /**
     * @Column(type="datetime")
     */
    protected $updated_at;

    /**
     * @Column(type="string", length=45, nullable=true)
     */
    protected $vendor;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Product
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBodyHtml()
    {
        return $this->body_html;
    }

    /**
     * @param mixed $body_html
     * @return Product
     */
    public function setBodyHtml($body_html)
    {
        $this->body_html = $body_html;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     * @return Product
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @param mixed $handle
     * @return Product
     */
    public function setHandle($handle)
    {
        $this->handle = $handle;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param mixed $options
     * @return Product
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductType()
    {
        return $this->product_type;
    }

    /**
     * @param mixed $product_type
     * @return Product
     */
    public function setProductType($product_type)
    {
        $this->product_type = $product_type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPublishedAt()
    {
        return $this->published_at;
    }

    /**
     * @param mixed $published_at
     * @return Product
     */
    public function setPublishedAt($published_at)
    {
        $this->published_at = $published_at;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPublishedScope()
    {
        return $this->published_scope;
    }

    /**
     * @param mixed $published_scope
     * @return Product
     */
    public function setPublishedScope($published_scope)
    {
        $this->published_scope = $published_scope;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     * @return Product
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTemplateSuffix()
    {
        return $this->template_suffix;
    }

    /**
     * @param mixed $template_suffix
     * @return Product
     */
    public function setTemplateSuffix($template_suffix)
    {
        $this->template_suffix = $template_suffix;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return Product
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetafieldsGlobalTitleTag()
    {
        return $this->metafields_global_title_tag;
    }

    /**
     * @param mixed $metafields_global_title_tag
     * @return Product
     */
    public function setMetafieldsGlobalTitleTag($metafields_global_title_tag)
    {
        $this->metafields_global_title_tag = $metafields_global_title_tag;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetafieldsGlobalDescriptionTag()
    {
        return $this->metafields_global_description_tag;
    }

    /**
     * @param mixed $metafields_global_description_tag
     * @return Product
     */
    public function setMetafieldsGlobalDescriptionTag($metafields_global_description_tag)
    {
        $this->metafields_global_description_tag = $metafields_global_description_tag;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     * @return Product
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * @param mixed $vendor
     * @return Product
     */
    public function setVendor($vendor)
    {
        $this->vendor = $vendor;
        return $this;
    }

}
