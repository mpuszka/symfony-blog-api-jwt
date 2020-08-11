<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Article;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/articles", name="articles_api")
     */
    public function index()
    {
        $articles = $this->getDoctrine()
                        ->getRepository(Article::class)
                        ->findBy([], ['date' => 'desc']);

        $articles = array_map(function($item) {
            return [
                'id'        => $item->getId(),
                'title'     => $item->getTitle(),
                'body'      => $item->getBody(),
                'author'    => $item->getAuthor()->getFullName(),
                'date'      => $item->getDate()->format('Y-m-d')
            ];
        }, $articles);

        return $this->json([   
                'status'    => 200,
                'articles'  => $articles
        ]);
    }
}
