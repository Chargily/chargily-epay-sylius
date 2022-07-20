<?php
/**
 * Created by PhpStorm.
 * User: mohamed
 * Date: 12/4/18
 * Time: 12:32 PM
 */

namespace Kiakaha\ChargilyPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ChargilyGatewayConfigurationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('api_key', TextType::class, [
                'required' => true,
                'label' => 'Api Key',
            ])
            ->add('api_secret', TextType::class, [
                'required'  =>  true,
                'label' => 'Secret Key'
            ])
            ->add('base_url', TextType::class, [
                'required' => true,
                'label' => 'Base Url',
            ])
            ->add('invoice_details', TextType::class, [
                'required' => true,
                'label' => 'Invoice Details',
            ])
            ->add('mode', ChoiceType::class, [
                'choices' => [
                    'EDAHABIA' => 'EDAHABIA',
                    'CIB' => 'CIB',
                ],
                'label' => 'Mode',
            ])
        ;
    }
}
