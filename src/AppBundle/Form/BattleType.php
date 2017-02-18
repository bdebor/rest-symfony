<?php

namespace AppBundle\Form;

use AppBundle\Form\Model\BattleModel;
use AppBundle\Repository\ProgrammerRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BattleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];

        $builder->add('programmerId', EntityType::class, [
            'class' => 'AppBundle\Entity\Programmer',
            'property_path' => 'programmer',
            'query_builder' => function(ProgrammerRepository $repo) use ($user) {
                return $repo->createQueryBuilderForUser($user);
            }
        ])
        ->add('projectId', EntityType::class, [
            'class' => 'AppBundle\Entity\Project',
            'property_path' => 'project'
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

        $resolver->setRequired(['user']);
    }
}
