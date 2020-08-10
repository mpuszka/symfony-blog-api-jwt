<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Article;
use App\Entity\Comment;

use App\Form\Comment\CommentType;

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
    public function article(Request $request, $id) 
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
        
        $comments   = $article->getComments();
        $form       = $this->createForm(CommentType::class);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $commentRequest = $form->getData();
            $entityManager  = $this->getDoctrine()->getManager();
            $comment        = new Comment;

            $comment->setTitle($commentRequest->getTitle());
            $comment->setBody($commentRequest->getBody());
            $comment->setCreatedDate(new \DateTime());
            $comment->setArticle($article);
            $comment->setUser($this->getUser());

            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Comment added!'
            );

            return $this->redirectToRoute('article', ['id' => $id]);
        }

        return $this->render('article/article.html.twig', [
            'article'   => $article,
            'comments'  => $comments,
            'form'      => $form->createView(),
        ]);
    }
}
