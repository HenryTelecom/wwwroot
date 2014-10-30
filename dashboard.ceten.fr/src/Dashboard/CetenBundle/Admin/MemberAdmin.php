<?php
namespace Dashboard\CetenBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class MemberAdmin extends Admin
{
    public static function getYearChoices()
    {
        return array(
            1 => '1A',
            2 => '2A',
            3 => '3A',
            4 => 'Autres'
        );
    }

    public static function getPaymentChoices()
    {
        return array(
            0 => 'Credit card',
            1 => 'Check',
            2 => 'Cash',
            3 => 'SoGé',
            4 => 'Sogé (Deposit OK)',
            5 => 'Wire transfer',
            6 => 'Not paid yet'
        );
    }

    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'ASC',
        '_sort_by' => 'lastname'
    );

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('firstname', null, array('label' => 'First name'))
            ->add('lastname', null, array('label' => 'Last name'))
            ->add('email', null, array('label' => 'E-mail address'))
            ->add('year', 'choice', array('label' => 'Year', 'choices' => self::getYearChoices()))
            ->add('payment', 'choice', array('label' => 'Payment type', 'choices' => self::getPaymentChoices()))
            ->add('deposit', null, array('label' => 'Deposit', 'required' => false))
            ->add('depositName', null, array('label' => 'Deposit check name (if different from member name)', 'required' => false))
            ->add('welcomePack', null, array('label' => 'Welcome pack', 'required' => false))
            ->add('mailing', null, array('label' => 'Mailing list', 'required' => false))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('firstname', null, array('label' => 'First name'))
            ->add('lastname', null, array('label' => 'Last name'))
            ->add('year', 'doctrine_orm_choice', array('label' => 'Year'), 'choice', array('choices' => self::getYearChoices()))
        ;

        if ($this->isGranted('EDIT')) {
            $datagridMapper
                ->add('payment', 'doctrine_orm_choice', array('label' => 'Payment type'), 'choice', array('choices' => self::getPaymentChoices()))
            ;
        }

        $datagridMapper
            ->add('deposit', null, array('label' => 'Deposit'))
        ;

        if ($this->isGranted('EDIT')) {
            $datagridMapper
                ->add('mailing', null, array('label' => 'Mailing list'))
            ;
        }
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('firstname', null, array('label' => 'First name'))
            ->add('lastname', null, array('label' => 'Last name'))
            ->add('year', 'choice', array('label' => 'Year', 'choices' => self::getYearChoices()))
        ;

        if ($this->isGranted('EDIT')) {
            $listMapper
                ->add('payment', 'choice', array('label' => 'Payment type', 'choices' => self::getPaymentChoices()))
            ;
        }

        $listMapper
            ->add('deposit', null, array('label' => 'Deposit'))
        ;

        if ($this->isGranted('EDIT')) {
            $listMapper
                ->add('mailing', 'boolean', array('label' => 'Mailing list', 'editable' => true))
                ->add('_action', 'actions', array(
                    'actions' => array(
                        'edit' => array(),
                        'delete' => array()
                    )
                ))
            ;
        } 
    }
}