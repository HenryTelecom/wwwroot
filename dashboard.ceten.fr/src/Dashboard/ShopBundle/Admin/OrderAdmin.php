<?php
namespace Dashboard\ShopBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Admin\AdminInterface;

use Knp\Menu\ItemInterface as MenuItemInterface;

class OrderAdmin extends Admin
{

    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'DESC',
        '_sort_by' => 'created'
    );


    public static function getStateChoices()
    {
        return array(
            0 => 'Order waiting for treatment',
            1 => 'Withdraw possible',
            2 => 'Withdrawn'
        );
    }

    public static function getPaymentChoices()
    {
        return array(
            0 => 'Not paid yet',
            1 => 'Credit card',
            2 => 'Check',
            3 => 'Cash',
            4 => 'Wire transfer'
        );
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('payment', 'choice', array('label' => 'Payment', 'choices' => self::getPaymentChoices()))
            ->add('state', 'choice', array('label' => 'State', 'choices' => self::getStateChoices()))
            ->add('orders', null, array('label' => 'Products'))
            ->add('total', null, array('label' => 'Total'))
        ;

    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('payment', 'choice', array('label' => 'Payment', 'choices' => self::getPaymentChoices()))
            ->add('state', 'choice', array('label' => 'State', 'choices' => self::getStateChoices()))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('user', null, array('label' => 'User'))
            ->add('payment', 'doctrine_orm_choice', array('label' => 'Payment'), 'choice', array('choices' => self::getPaymentChoices()))
            ->add('state', 'doctrine_orm_choice', array('label' => 'State'), 'choice', array('choices' => self::getStateChoices()))
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('created', null, array('label' => 'Name'))
            ->add('payment', 'choice', array('label' => 'Payment', 'choices' => self::getPaymentChoices()))
            ->add('state', 'choice', array('label' => 'State', 'choices' => self::getStateChoices()))
            ->add('total', null, array('label' => 'Total'))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array()
                )
            ))
        ;
    }

    public function preRemove($order)
    {
        $container = $this->getConfigurationPool()->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        $em->getRepository('CetenCetenBundle:Product')->updateStock($order, true);
    }
}