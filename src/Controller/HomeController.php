<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Country;
use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use DateTimeImmutable;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
        // $form = $this->createFormBuilder(['name'=>'heinzCodeur','message'=>'cool','availableAt'=> new \DateTimeImmutable('- 2 days')])
        $form = $this->createFormBuilder(['age'=>15])
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
            ->add('city', EntityType::class, [ 
                    'disabled'=>true,
                    'placeholder'=>'choisir une ville',
                    'class' => City::class, 
                    'choice_label' => 'name',
                    'query_builder' => function(CityRepository $cityRepository){
                            return $cityRepository->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                    },
                    'constraints' => new NotBlank(["message" => "choississez une ville"])
                ])
            ->add('message', TextareaType::class,[
                'constraints' => [
                                    new NotBlank(['message' => 'ecrivez votre message']),
                                    new Length(['min' => 5, 'minMessage'=>'{{ limit }} caracteres attendus'])
                                ]
                ])
            ->add('availableAt', DateTimeType::class, ['widget' => 'single_text'])
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
                // dd(FormEvents::PRE_SET_DATA);
                // $event->setData(['age'=>16]);
                // $age = $event->getData()?$event->getData()['age']:null;
                $age = $event->getData()['age'] ?? null;
                if($age && $age < 18){
                    $event->getForm()->add('parent', TextType::class);
                    $event->getForm()->remove('message');
                }
            })
            ->getForm()
            ;

                // dd($form->get('citygo'));
            // $form->setData(['availableAt'=> new \DateTimeImmutable('+ 3 days')]);
            $form->setData(['name'=> 'heinzWallace']);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){dd('titi');}
        
        
            return $this->render('home.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
