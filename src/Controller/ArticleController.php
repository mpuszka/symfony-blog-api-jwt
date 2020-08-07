<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Article;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="articles")
     */
    public function index()
    {   
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $articles = $this->getDoctrine()
                        ->getRepository(Article::class)
                        ->findAll();
        
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/article/{id}", name="article")
     */
    public function article($id) 
    {   
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $article = $this->getDoctrine()
                        ->getRepository(Article::class)
                        ->find($id);
        
        if (
            !isset($article) || 
            empty($article)
        ) {
            $this->addFlash(
                'error',
                'Article not found!'
            );

            return $this->redirectToRoute('articles');
        }

        return $this->render('article/article.html.twig', [
            'article' => $article,
        ]);
    }
}
