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

    private $cityRepository;
    private $countryRepository;

    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
            $country = $event->getData()['country'];
            $villes = $country === null ? [] : $this->cityRepository->findByCountry($country, ['name' => 'ASC']);
            // $villes = $cityRepository->createQueryBuilder('c')
            //     ->andWhere('c.country = :country')
            //     ->setParameter('country', $country)
            //     ->orderBy('c.name', 'ASC')
            //     ->getQuery()
            //     ->getResult();
        
                $event->getForm()->add('city', EntityType::class, [
                    'class' => City::class,
                    'disabled' => !$country,
                    'choice_label' => 'name',
                    'choices' => $villes,
                    'placeholder' => 'choisir une ville'
                ]);
                // $event->getForm()->add('quartier', ChoiceType::class, [
                //     'choices' => ['marcory' => 1, 'bietry' => 2 ]
                // ]);
        })    
            ->add('name', TextType::class, [
                'constraints' => new NotBlank(['message'=>'ce champ ne peut etre vide'])
            ])
            ->add('age', IntegerType::class)
            ->add('country', EntityType::class, [
                    'placeholder'=>'choisir un pays',
                    'class' => Country::class,
                //    'choice_label' => fn(Country $country) => $country->getId().'-'.$country->getName()
                   'choice_label' => 'name',
                   'query_builder' => function(CountryRepository $countryRepository){
                        return $countryRepository->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                   },
                   'constraints' => new NotBlank(["message" => "choississez un pays"])
                ])
            // ->add('city', EntityType::class, [ 
            //         'disabled'=>false,
            //         'placeholder'=>'choisir une ville',
            //         'class' => City::class, 
            //         'choice_label' => 'name',
            //         'query_builder' => function(CityRepository $cityRepository){
            //                 return $cityRepository->createQueryBuilder('c')->orderBy('c.name', 'ASC');
            //         },
            //         'constraints' => new NotBlank(["message" => "choississez une ville"])
            //     ])
            ->add('message', TextareaType::class,[
                'constraints' => [
                                    new NotBlank(['message' => 'ecrivez votre message']),
                                    new Length(['min' => 5, 'minMessage'=>'{{ limit }} caracteres attendus'])
                ],
                'help' => 'ecrivez un message!'
                ])
            ->add('availableAt', DateTimeType::class, ['widget' => 'single_text'])
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
                //  dd($this->cityRepository->findBy(
                //     ['country' => $event->getData()['country']])
                // );
                //  $event->setData(['age'=>19]);
                // $age = $event->getData()?$event->getData()['age']:null;
                $age = $event->getData()['age'] ?? null;
                if($age && $age < 18){
                    $event->getForm()->add('parent', TextType::class);
                    $event->getForm()->add('city', EntityType::class,['class'=> City::class, 'choice_label'=> 'name']);
                    $event->getForm()->remove('message');
                }
            })
            ->getForm()
            ;
    }

}