<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Form\SearchType;
use App\Form\VoitureType;
use App\Model\SearchData;
use App\Repository\VoitureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class VoitureController extends AbstractController
{
    #[Route('/', name: 'app_voiture_index', methods: ['GET'])]
    public function index(VoitureRepository $voitureRepository ,Request $request, PaginatorInterface $paginator): Response
    {
        
if ($this->getUser()){
            if (!$this->getUser()->isVerified()){
                $this->addFlash('info', 'Your email address is not verified.');
            }  
        }
        $pagination = $paginator->paginate(
            $voitureRepository->paginationQuery(),
            $request->query->get('page', 1),
            3  
        );
$search = false ;

    $searchData = new SearchData();
    $form = $this->createForm(SearchType::class,$searchData);
    $form->handleRequest($request);

    
    if($form->isSubmitted() && $form->isValid()) {
       $searchData->page = $request->query->getInt('page',1);
    //    $voitures = $voitureRepository->findBySearch($searchData);
    //  dd($voitures);
    $pagination = $paginator->paginate(
        $voitureRepository->findbySearch($searchData),
        $request->query->get('page', 1),
        3  
    );
    
    $search = true;

    return $this->render('voiture/index.html.twig', [
        'form' => $form,
        'pagination' => $pagination,
        'search' => $search,
        'searchData' => $searchData->q,
        'voitures' => $voitureRepository->findBySearch($searchData)
    ]);
}

return $this->render('voiture/index.html.twig', [
    'form' => $form->createView(),
    'pagination' => $pagination,
    'search' => $search,
    // 'voitures' => $voitureRepository->findAll()
]);
}
    #[Route('/voiture/creer', name: 'app_voiture_creer', methods: ['GET', 'POST'])]
    public function creer(Request $request, VoitureRepository $voitureRepository): Response
    {
        if ($this->getUser()){
            if ($this->getUser()->isVerified() == false) {
                $this->addFlash('error', 'You must confirm your email to create a new Car !');
                return $this->redirectToRoute('app_home');
            } 
        }else{
            $this->addFlash('error', 'You must login to create a new Car !');
            return $this->redirectToRoute('app_login');
        }
        $voiture = new Voiture();
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $voiture->setUser($this->getUser());
            $voitureRepository->save($voiture, true);
            $this->addFlash('success', 'Vous avez créé la voiture avec succès !');
            return $this->redirectToRoute('app_voiture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voiture/creer.html.twig', [
            'voiture' => $voiture,
            'form' => $form,
        ]);
    }

    #[Route('/voiture/{id}', name: 'app_voiture_details', methods: ['GET'])]
    public function details(Voiture $voiture): Response
    {
        return $this->render('voiture/details.html.twig', [
            'voiture' => $voiture,
        ]);
    }

    #[Route('/voiture/{id}/editer', name: 'app_voiture_editer', methods: ['GET', 'POST'])]
    public function editer(Request $request, Voiture $voiture, VoitureRepository $voitureRepository): Response
    { if ($this->getUser()) {
        if ($this->getUser()->isVerified() == false) {
            $this->addFlash('error', 'You must confirm your email to edit this Car !');
            return $this->redirectToRoute('app_home');
        } else if ($voiture->getUser()->getEmail() !== $this->getUser()->getEmail()) {
            $this->addFlash('error', 'You must to be the user ' . $voiture->getUser()->getFirstname() . ' to edit this Car !');
            return $this->redirectToRoute('app_voiture_index');
        }
    } else {
        $this->addFlash('error', 'You must login to edit this Car !');
        return $this->redirectToRoute('app_login');
    }
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $voitureRepository->save($voiture, true);
            $this->addFlash('success', 'Vous avez modifié la voiture avec succès!');
            return $this->redirectToRoute('app_voiture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voiture/editer.html.twig', [
            'voiture' => $voiture,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_voiture_supprimer', methods: ['POST'])]
    public function supprimer(Request $request, Voiture $voiture, VoitureRepository $voitureRepository): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->isVerified() == false) {
                $this->addFlash('error', 'You must confirm your email to delete this Car !');
                return $this->redirectToRoute('app_home');
            } else if ($voiture->getUser()->getEmail() !== $this->getUser()->getEmail()) {
                $this->addFlash('error', 'You must to be the user ' . $voiture->getUser()->getFirstname() . ' to delete this Car !');
                return $this->redirectToRoute('app_voiture_index');
            }
        } else {
            $this->addFlash('error', 'You must login to delete this  Car!');
            return $this->redirectToRoute('app_login');
        }
        if ($this->isCsrfTokenValid('delete'.$voiture->getId(), $request->request->get('_token'))) {
            $voitureRepository->remove($voiture, true);
        }
       
        $this->addFlash('info', 'Vous avez supprimé la voiture avec succès!'); 
        return $this->redirectToRoute('app_voiture_index', [], Response::HTTP_SEE_OTHER);
    }
    
}
