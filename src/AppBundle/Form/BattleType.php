<?php

namespace AppBundle\Form;

use AppBundle\Form\Model\BattleModel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProgrammerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('programmer', EntityType::class, [
            'class' => 'AppBundle\Entity\Programmer'
        ])
        ->add('project', EntityType::class, [
            'class' => 'AppBundle\Entity\Project'
        ]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BattleModel::class,
            'csrf_protection' => false,
        ]);
    }
}
