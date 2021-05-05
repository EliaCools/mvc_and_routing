<?php

namespace App\Controller;

use App\Form\UserNameType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
class LearningController extends AbstractController
{

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        $this->session->start();
    }


    #[Route('/about-me', name: 'aboutMe')]
    public function aboutMe(): Response{

        $response = $this->forward('App\Controller\LearningController::showMyName', []);

        if(!$this->session->get('name')){
            return $response;
        }

        return $this->render('learning/aboutMe.html.twig', [

            "name" => $this->session->get('name') ?? 'unknown',

        ]);
    }

    #[Route('/', name: 'showMyName')]
    public function showMyName(Request $request): Response{

        $form = $this->createForm(UserNameType::class);

        $form->handleRequest($request);

          if($form->isSubmitted()){

              return $this->redirectToRoute('changeMyName', ['request' => $request], 307);
          }
        return $this->render('learning/showMyName.html.twig', [

            "name" => $this->session->get('name') ?? 'unknown',
            "form" => $form->createView(),


        ]);
    }

    #[Route('/changeMyName', name: 'changeMyName',methods: ['POST'])]
    public function changeMyName(Request $request): Response{


        $this->session->set('name',$request->get('user_name')["name"]);

        return $this->redirectToRoute('showMyName');


    }


}
