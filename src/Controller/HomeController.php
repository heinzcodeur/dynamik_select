<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Country;
use App\Form\CityFormType;
use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use DateTimeImmutable;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
    private $cityRepository;
    private $countryRepository;

    public function __construct(CityRepository $cityRepository, CountryRepository $countryRepository)
    {
        $this->cityRepository = $cityRepository;
        $this->countryRepository = $countryRepository;
    }


    #[Route('/', name: 'app_home')]
    public function index(Request $request, CityRepository $cityRepository): Response
    {
        // $form = $this->createFormBuilder(['name'=>'heinzCodeur','message'=>'cool','availableAt'=> new \DateTimeImmutable('- 2 days')])
        $form = $this->createForm(CityFormType::class, ['pays' => $this->countryRepository->find(2), 'city' => $cityRepository->find(4)]);
        

            // $form->setData(['availableAt'=> new \DateTimeImmutable('+ 3 days')]);
            $form->setData(['name'=> 'heinzWallace']);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){dd('titi');}
        
        
            return $this->render('home.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
