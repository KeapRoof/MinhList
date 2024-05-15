<?php

namespace App\Controller;

use App\Entity\Contient;
use App\Form\ContientType;
use App\Repository\ContientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Liste;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\RedirectResponse;




#[Route('/contient')]
class ContientController extends AbstractController
{
    #[Route('/', name: 'app_contient_index', methods: ['GET'])]
    public function index(ContientRepository $contientRepository): Response
    {
        return $this->render('contient/index.html.twig', [
            'contients' => $contientRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_contient_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contient = new Contient();
        $form = $this->createForm(ContientType::class, $contient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contient);
            $entityManager->flush();

            return $this->redirectToRoute('app_contient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contient/new.html.twig', [
            'contient' => $contient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contient_show', methods: ['GET'])]
    public function show(Contient $contient): Response
    {
        return $this->render('contient/show.html.twig', [
            'contient' => $contient,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_contient_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contient $contient, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContientType::class, $contient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_contient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contient/edit.html.twig', [
            'contient' => $contient,
            'form' => $form,
        ]);
    }


    #[Route('/add-to-list/{listeId}', name: 'app_contient_add_to_list', methods: ['POST'])]
    public function addToCart(Request $request, EntityManagerInterface $entityManager, int $listeId): JsonResponse
{
    $requestData = json_decode($request->getContent(), true);
    $articlesData = $requestData['articles'];

    // Récupérer la liste
    $liste = $entityManager->getRepository(Liste::class)->find($listeId);

    if (!$liste) {
        return new JsonResponse(['error' => 'Liste non trouvée'], Response::HTTP_NOT_FOUND);
    }

    foreach ($articlesData as $articleData) {
        $articleId = $articleData['articleId'];
        $quantity = $articleData['quantity'];

        // Recherche de l'article dans la liste
        $contient = $entityManager->getRepository(Contient::class)->findOneBy(['Dans' => $liste, 'Contenue' => $articleId]);

        if ($contient) {
            // Si l'article existe déjà dans la liste, mettre à jour la quantité
            $contient->setQuantite($contient->getQuantite() + $quantity);
        } else {
            // Sinon, créer un nouvel élément dans la liste
            $article = $entityManager->getRepository(Article::class)->find($articleId);

            if (!$article) {
                return new JsonResponse(['error' => 'Article non trouvé'], Response::HTTP_NOT_FOUND);
            }

            $contient = new Contient();
            $contient->setContenue($article);
            $contient->setQuantite($quantity);
            $contient->setDans($liste);
            $contient->setAcheter(0);

            $entityManager->persist($contient);
        }
    }

    $entityManager->flush();

    return new JsonResponse(['success' => true]);
}


    // #[Route('/{id}', name: 'app_contient_delete', methods: ['POST'])]
    // public function delete(Request $request, Contient $contient, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$contient->getId(), $request->request->get('_token'))) {
    //         $entityManager->remove($contient);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_contient_index', [], Response::HTTP_SEE_OTHER);
    // }

    #[Route('/{id}/delete', name: 'app_contient_delete', methods: ['POST'])]
    public function delete(Request $request, Contient $contient, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contient->getId(), $request->request->get('_token'))) {
            $entityManager->remove($contient);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_liste_show', ['id' => $contient->getListe()->getId()], Response::HTTP_SEE_OTHER);
    }


    // liste de tout les articles dans contient
    #[Route('/liste/{id}', name: 'app_contient_liste', methods: ['GET'])]
    public function liste(Liste $liste): Response
    {
        return $this->render('liste/show.html.twig', [
            'liste' => $liste,
        ]);
    }
    




    #[Route('/{id}/increase-quantity', name: 'app_contient_increase_quantity', methods: ['GET','POST'])]
    public function increaseQuantity(Request $request, Contient $contient, EntityManagerInterface $entityManager): Response
    {
        $contient->setQuantite($contient->getQuantite() + 1);
        $entityManager->flush();

        //return $this->redirectToRoute('app_liste_show', ['id' => $contient->getListe()->getId()], Response::HTTP_SEE_OTHER);

        return $this->redirect($request->headers->get('referer'));
    }


    #[Route('/{id}/decrease-quantity', name: 'app_contient_decrease_quantity', methods: ['GET','POST'])]
    public function decreaseQuantity(Request $request, Contient $contient, EntityManagerInterface $entityManager): Response
    {
        $contient->setQuantite($contient->getQuantite() - 1);
        $entityManager->flush();

        //return $this->redirectToRoute('app_liste_show', ['id' => $contient->getListe()->getId()], Response::HTTP_SEE_OTHER);

        return $this->redirect($request->headers->get('referer'));
    }


    // pour supprimer un article de la liste
    #[Route('/{id}/delete-article', name: 'app_contient_delete_article', methods: ['GET','POST'])]
    public function deleteArticle(Request $request, Contient $contient, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($contient);
        $entityManager->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    // pour marquer un article comme acheté
    #[Route('/{id}/mark-as-bought', name: 'app_contient_buy', methods: ['GET','POST'])]
    public function markAsBought(Request $request, Contient $contient, EntityManagerInterface $entityManager): Response
    {
        // l'inverse de la valeur actuelle
        $contient->setAcheter(!$contient->isAcheter());
        $entityManager->flush();

        return $this->redirect($request->headers->get('referer'));
    }

}
