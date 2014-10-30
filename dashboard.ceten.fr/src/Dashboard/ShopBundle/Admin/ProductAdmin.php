<?php
namespace Dashboard\ShopBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

use Sonata\AdminBundle\Admin\AdminInterface;
use Knp\Menu\ItemInterface as MenuItemInterface;

class ProductAdmin extends Admin
{

    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'ASC',
        '_sort_by' => 'name'
    );

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {

        $product = $this->getSubject();

        $fileFieldOptions = array('label' => 'Image', 'required' => false);
        if ($product && ($webPath = $product->getImage())) {
            $container = $this->getConfigurationPool()->getContainer();
            $fullPath = $container->get('ceten.twig.ceten_extension')->cdn($webPath);
            $fileFieldOptions['help'] = '<img src="'.$fullPath.'" class="admin-preview" />';
        }

        $formMapper
            ->add('name', null, array('label' => 'Name'))
            ->add('priceCeten', null, array('label' => 'Price for CETEN'))
            ->add('price', null, array('label' => 'Price for non-CETEN'))
            ->add('description', null, array('label' => 'Description'))
            ->add('imageFile', 'file', $fileFieldOptions)
            ->add('tags', 'sonata_type_model', array('label' => 'Tags', 'multiple' => true, 'by_reference' => false, 'required' => false))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name', null, array('label' => 'Name'))
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name', null, array('label' => 'Name'))
            ->add('slug', null, array('label' => 'Slug'))
            ->add('priceCeten', 'currency', array('label' => 'Price for CETEN', 'currency' => 'EUR'))
            ->add('price', 'currency', array('label' => 'Price for non-CETEN', 'currency' => 'EUR'))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array()
                )
            ))
        ;
    }

    public function prePersist($product) {
        $product->refreshUpdated();
    }

    public function preUpdate($product) {
        $product->refreshUpdated();
    }
}