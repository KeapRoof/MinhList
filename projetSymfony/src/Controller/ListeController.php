<?php

namespace App\Controller;

use App\Entity\Liste;
use App\Form\ListeType;
use App\Repository\ListeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Contient;
use App\Repository\ContientRepository;
use App\Repository\UserRepository;

#[Route('/liste')]
class ListeController extends AbstractController
{
    #[Route('/', name: 'app_liste_index', methods: ['GET'])]
    public function index(Request $request, ListeRepository $listeRepository, ContientRepository $contientRepository, UserRepository $userRepository): Response
    {
        $liste = new Liste();
        $user = $this->getUser();
        $liste->setIdUser($user);
        $form = $this->createForm(ListeType::class, $liste);
        $form->handleRequest($request);
        
        $listes = $listeRepository->findAll();
            
        $user = $this->getUser();
        $listes = array_filter($listes, function($liste) use ($user) {
            return $liste->getIdUser() == $user;
        });

        $prix_max = $contientRepository->findMaxPriceByUser($user);
        $total = $contientRepository->sumPriceByUser($user);
        $min = $contientRepository->findMinPriceByUser($user);
        $avg = $contientRepository->findByAvgPriceByUser($user);
        $dept = $contientRepository->findByTypeDepense($user);

        return $this->render('liste/index.html.twig',[
            'listes' => $listeRepository->findAll(),
            'form' => $form,
            'prix_max' => $prix_max,
            'total' => $total,
            'prix_min' => $min,
            'avg' => $avg,
            'expensesByType' => $dept,
        ]);
        
    }
    
    #[Route('/', name: 'app_liste_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $liste = new Liste();
        $user = $this->getUser();
        $liste->setIdUser($user);
        $form = $this->createForm(ListeType::class, $liste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($liste);
            $entityManager->flush();

            return $this->redirectToRoute('app_liste_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('liste/new.html.twig', [
            'liste' => $liste,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_liste_show', methods: ['GET'])]
    public function show(Liste $liste): Response
    {
        return $this->render('liste/show.html.twig', [
            'liste' => $liste,
        ]);
    }
    
    // #[Route('/{id}', name: 'app_liste_show', methods: ['GET'])]
    // public function show(Liste $liste, ContientRepository $contientRepository): Response
    // {
    //     $articles = $contientRepository->findBy(['Dans' => $liste]);

    //     return $this->render('liste/show.html.twig', [
    //         'liste' => $liste,
    //         'articles' => $articles,
    //     ]);
    // }

    #[Route('/{id}/edit', name: 'app_liste_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Liste $liste, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ListeType::class, $liste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_liste_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('liste/edit.html.twig', [
            'liste' => $liste,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_liste_delete', methods: ['POST'])]
    public function delete(Request $request, Liste $liste, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$liste->getId(), $request->request->get('_token'))) {
            $entityManager->remove($liste);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_liste_index', [], Response::HTTP_SEE_OTHER);
    }



    // Ajoutez cette méthode pour traiter l'ajout à la liste
    #[Route('/add_to_list', name: 'app_add_to_list', methods: ['POST'])]
    public function addToCart(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérez les données envoyées par la requête AJAX
        $data = json_decode($request->getContent(), true);
    
        // Récupérez l'objet Liste correspondant à l'ID envoyé depuis la requête AJAX
        $liste = $this->getDoctrine()->getRepository(Liste::class)->find($data['listeId']);
    
        // Vérifiez si la liste existe
        if (!$liste) {
            return new Response('Liste not found', Response::HTTP_NOT_FOUND);
        }
    
        // Parcourez les articles sélectionnés et ajoutez-les à la liste dans la table "contient"
        foreach ($data['articleIds'] as $articleId) {
            $article = $this->getDoctrine()->getRepository(Article::class)->find($articleId);
       
            $existingContient = $this->getDoctrine()->getRepository(Contient::class)->findOneBy(['Dans' => $liste, 'Contenue' => $article]);
    


            if ($existingContient) {
                // Si l'article existe déjà, augmentez simplement la quantité
                $existingContient->setQuantity($existingContient->getQuantity() + $data['quantity']);
            } else {
                // Sinon, créez une nouvelle entrée dans la table "contient" avec la quantité spécifiée
                $contient = new Contient();
                $contient->setListe($liste);
                $contient->setArticle($article);
                $contient->setQuantity($data['quantity']);
        
                // Persistez l'entité contient
                $entityManager->persist($contient);
            }
        }
    
        // Flush pour enregistrer les changements dans la base de données
        $entityManager->flush();
    
        // Répondez à la requête AJAX avec un message de succès ou une redirection
        return new Response('Articles added to list successfully', Response::HTTP_OK);
    }
    

    // Ajoutez cette méthode pour traiter la suppression de la liste
    #[Route('/remove_from_list/{listeId}', name: 'app_remove_from_list', methods: ['POST'])]
    public function removelist(Request $request, EntityManagerInterface $entityManager, ListeRepository $listeRepository): Response
    {
        // Supprimez la liste de la base de données
        $liste = $listeRepository->find($request->get('listeId'));
        //Delete tout les contient de la liste
        $contient = $entityManager->getRepository(Contient::class)->findBy(['Dans' => $liste]);
        foreach ($contient as $contient) {
            $entityManager->remove($contient);
        }
        $entityManager->remove($liste);
        $entityManager->flush();
        return new Response('List has been deleted', Response::HTTP_OK);
    }

    
}