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

    protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        /*$admin = $this->isChild() ? $this->getParent() : $this;
        $id = $admin->getRequest()->get('id');

        $menu->addChild('Products',
            array('uri' => $admin->generateUrl('list', array('id' => $id)))
        );*/
    }

    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'ASC',
        '_sort_by' => 'name'
    );

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', null, array('label' => 'Name'))
            ->add('priceCeten', null, array('label' => 'Price for CETEN'))
            ->add('price', null, array('label' => 'Price for non-CETEN'))
            ->add('description', null, array('label' => 'Description'))
            ->add('tags', 'sonata_type_model', array('label' => 'Tags', 'multiple' => true, 'by_reference' => false))
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
            ->addIdentifier('name', null, array('label' => 'Name'))
            ->addIdentifier('slug', null, array('label' => 'Slug'))
            ->addIdentifier('priceCeten', 'currency', array('label' => 'Price for CETEN', 'currency' => 'EUR'))
            ->addIdentifier('price', 'currency', array('label' => 'Price for non-CETEN', 'currency' => 'EUR'))
        ;
    }
}