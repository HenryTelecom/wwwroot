<?php
namespace Dashboard\UserBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class UserAdmin extends Admin
{
    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'ASC',
        '_sort_by' => 'username'
    );

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('firstname', null, array('label' => 'First name'))
            ->add('lastname', null, array('label' => 'Last name'))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('firstname', null, array('label' => 'First name'))
            ->add('lastname', null, array('label' => 'Last name'))
            ->add('oauth', null, array('label' => 'Oauth'))
            ->add('enabled', null, array('label' => 'Enabled'))
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('firstname', null, array('label' => 'First name'))
            ->addIdentifier('lastname', null, array('label' => 'Last name'))
            ->addIdentifier('username', null, array('label' => 'E-mail'))
        ;

        if ($this->isGranted('EDIT')) {
            $listMapper
                ->addIdentifier('enabled', null, array('label' => 'Enabled', 'roles' => array('ROLE_ADMIN_USER')))
                ->addIdentifier('oauth', null, array('label' => 'Oauth'))
            ;
        }
    }
}