<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Country;
use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CityFormType extends AbstractType
{

    // private $cityRepository;
    // private $countryRepository;

    public function __construct(private CityRepository $cityRepository)
    {
        // $this->cityRepository = $cityRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
            $country = $event->getData()['pays'];
            $villes = $country === null ? [] : $this->cityRepository->findByCountry($country, ['name' => 'ASC']);
        
                $event->getForm()->add('city', EntityType::class, [
                    'class' => City::class,
                    'disabled' => !$country,
                    'choice_label' => 'name',
                    'choices' => $villes,
                    'placeholder' => 'choisir une ville'
                ]);
                $event->getForm()->remove('availableAt');
        })
        ->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event){
            // $event->setData(['pays'=> 3]);
            // dd($event->getData());
        }) 
        ->add('name', TextType::class, [
                'constraints' => new NotBlank(['message'=>'ce champ ne peut etre vide'])
            ])
        ->add('age', IntegerType::class)
        ->add('pays', EntityType::class, [
                    'placeholder'=>'choisir un pays',
                    'class' => Country::class,
                    'choice_label' => 'name',
                    'query_builder' => function(CountryRepository $countryRepository){
                        return $countryRepository->findAllOrderedByAscNameQueryBuilder();
                   },
                   'constraints' => new NotBlank(["message" => "choississez un pays"])
                ])
            
        // ->add('message', TextareaType::class,[
        //         'attr' => ['rows' => 7],
        //         'constraints' => [
        //                             new NotBlank(['message' => 'ecrivez votre message']),
        //                             new Length(['min' => 5, 'minMessage'=>'{{ limit }} caracteres attendus'])
        //         ],
        //         'help' => 'ecrivez un message!'
        //         ])
        ->add('availableAt', DateTimeType::class, ['widget' => 'single_text'])
        // ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
        //         $age = $event->getData()['age'] ?? null;
        //         if($age && $age < 18){
        //             $event->getForm()->add('parent', TextType::class);
        //             $event->getForm()->add('city', EntityType::class,['class'=> City::class, 'choice_label'=> 'name']);
        //             $event->getForm()->remove('message');
        //         }
        //     })
            ->getForm()
            ;
    }

}