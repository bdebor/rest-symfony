<?php

namespace AppBundle\Form;

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
        $builder
            ->add('nickname', 'text', array(
                // readonly if we're in edit mode
                'disabled' => $options['is_edit']
            ))
			->add('avatarNumber')
//			->add('avatarNumber', 'choice', [
//                'choices' => [
//                    // the key is the value that will be set
//                    // the value/label isn't shown in an API, and could
//                    // be set to anything
//                    1 => 'Girl (green)',
//                    2 => 'Boy',
//                    3 => 'Cat',
//                    4 => 'Boy with Hat',
//                    5 => 'Happy Robot',
//                    6 => 'Girl (purple)',
//                ]
//            ])
			->add('tagLine')
//            ->add('tagLine', 'textarea')
            ->add('powerLevel')
//            ->add('user')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Programmer',
            'is_edit' => false,
            // 'csrf_protection' => false // doesn't work ???
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'programmer';
    }
}
