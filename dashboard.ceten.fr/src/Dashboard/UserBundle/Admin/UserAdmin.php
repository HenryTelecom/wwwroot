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

    public static function getRoles()
    {
        return array(
            'ROLE_USER'             => 'User',
            'ROLE_READ_MEMBER'      => 'Read Ceten members',
            'ROLE_EDIT_MEMBER'      => 'Edit Ceten members',
            'ROLE_ADMIN_SHOP'       => 'Manage shop',
            'ROLE_SUPER_ADMIN'      => 'Super admin'
        );
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('firstname', null, array('label' => 'First name'))
            ->add('lastname', null, array('label' => 'Last name'))
            ->add('ceten', null, array('label' => 'Ceten'))
            ->add('roles', 'choice', array('label' => 'Roles', 'choices' => self::getRoles(), 'multiple' => true))
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
            ->add('firstname', null, array('label' => 'First name'))
            ->add('lastname', null, array('label' => 'Last name'))
            ->add('username', null, array('label' => 'E-mail'))
            ->add('enabled', null, array('label' => 'Enabled'))
            ->add('oauth', null, array('label' => 'Oauth'))
            ->add('ceten', null, array('label' => 'Ceten', 'editable' => true))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                )
            ))
        ;
    }
}