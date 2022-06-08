<?php

namespace App\Controller;

use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


#[Route('/article')]
class ArticleController extends AbstractController
{
    
    #[Route('/', name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    { 
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }
    
    
    #[IsGranted("ROLE_USER")]
    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Article $article = null, Request $request, ArticleRepository $articleRepository): Response
    {
        if(!$article){
            $article = new Article();
            $article->setCreatedAt(new DateTimeImmutable('now'));
            $article->setAuthor($this->getUser());
        }
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $articleRepository->add($article, true);
            // dd($article);
            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'form' => $form
        ]);
        
        
    }
    
    
    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $articleRepository->add($article, true);
            
            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }
        
        $verifEdArt = 0;
        if($this->getUser() == $article->getAuthor()){
            $verifEdArt = 1;
        }
        
        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
            'verifEdArt' => $verifEdArt
        ]);
    } 
    



    
    #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $articleRepository->remove($article, true);
        }
        
        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }




    #[Route('/show/{id}', name: 'article_show', methods: ['GET'])]
    public function show(CommentRepository $commentRepository, Article $article, Request $request): Response
    {
        $session = $request->getSession();
        $idArticle = $article->getId();
        $session->set('article', $article);
        
        $verifDeArt = 0;
        if($this->getUser() == $article->getAuthor()){
            $verifDeArt = 1;
        }
        $commentArray = $commentRepository->findBy(['article'=> $idArticle]);
        
 


        
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'verifDeArt' => $verifDeArt,
            'commentArray' => $commentArray
        ]);
    }
}
