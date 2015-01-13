<?php
namespace Dashboard\CetenBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ClubAdmin extends Admin
{
    public static function getTypeChoices()
    {
        return array(
            0 => 'Événementiel',
            1 => 'Loisir',
            2 => 'Service'
        );
    }

    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'ASC',
        '_sort_by' => 'name'
    );

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $club = $this->getSubject();

        $fileFieldOptions = array('label' => 'Logo', 'required' => false);
        if ($club && ($webPath = $club->getLogo())) {
            $container = $this->getConfigurationPool()->getContainer();
            $fullPath = $container->get('ceten.twig.ceten_extension')->cdn($webPath);
            $fileFieldOptions['help'] = '<img src="'.$fullPath.'" class="admin-preview" />';
        }

        $formMapper
            ->add('name', null, array('label' => 'Name'))
            ->add('type', 'choice', array('label' => 'Type', 'choices' => self::getTypeChoices()))
            ->add('description', 'textarea', array('label' => 'Description'))
            ->add('imageFile', 'file', $fileFieldOptions)
            ->add('website', null, array('label' => 'Website', 'required' => false))
            ->add('referent', 'sonata_type_model_list', array('label' => 'Referent'))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name', null, array('label' => 'Name'))
            ->add('type', 'doctrine_orm_choice', array('label' => 'Type'), 'choice', array('choices' => self::getTypeChoices()))
            ->add('referent', null, array('label' => 'Referent'))
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name', null, array('label' => 'Name'))
            ->add('type', 'choice', array('label' => 'Type', 'choices' => self::getTypeChoices()))
            ->add('referent', 'doctrine_orm_class')
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