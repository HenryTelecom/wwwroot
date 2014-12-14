<?php
namespace Dashboard\CetenBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

use Sonata\AdminBundle\Admin\AdminInterface;
use Knp\Menu\ItemInterface as MenuItemInterface;

class NewsAdmin extends Admin
{

    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'ASC',
        '_sort_by' => 'title'
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
            ->add('title', null, array('label' => 'Title'))
            ->add('overview', 'textarea', array('label' => 'Overview'))
            ->add('imageFile', 'file', $fileFieldOptions)
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title', null, array('label' => 'Title'))
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('title', null, array('label' => 'Title'))
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