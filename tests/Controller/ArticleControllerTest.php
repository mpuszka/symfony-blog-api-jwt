<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;

class ArticleControllerTest extends WebTestCase
{   
    /**
     * Create client
     *
     * @var object
     */
    private $client;

    /**
     * User Repository
     *
     * @var object
     */
    private $userRepository;

    /**
     * Article repository
     *
     * @var [type]
     */
    private $articleRepository;

    /**
     * Id of tested article
     *
     * @var integer
     */
    private $articleId = 1;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->client               = static::createClient();
        $this->userRepository       = static::$container->get(UserRepository::class);
        $this->articleRepository    = static::$container->get(ArticleRepository::class);
    }

    /**
     * Test routes
     *
     * @return void
     */
    public function testRoutes(): void
    {
        $this->client->request('GET', '/login');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->client->request('GET', '/');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->client->request('GET', '/article/1');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

    }

    /**
     * Test login
     *
     * @return void
     */
    public function testLogin(): void
    {
        $crawler = $this->client->request('GET', '/login');
        $this->assertSelectorTextContains('html h1', 'Please sign in');
        
        $form = $crawler->selectButton('Sign in')->form();

        $form['email']      = 'fake@email.com';
        $form['password']   = 'fakepassword';

        $crawler = $this->client->submit($form);
    
        $this->assertTrue($this->client->getResponse()->isRedirect('/login'));
        
    }

    /**
     * Test articles
     *
     * @return void
     */
    public function testArticles(): void
    {
        $testUser       = $this->userRepository->findOneBy(['email' => 'emarr4@ow.ly']);
        $testArticle    = $this->articleRepository->find($this->articleId);

        $this->client->request('GET', '/');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->client->loginUser($testUser);
        $this->client->request('GET', '/');
        
        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString(
            $testArticle->getTitle(),
            $this->client->getResponse()->getContent()
        );
    }

    /**
     * Test single article
     *
     * @return void
     */
    public function testArticle(): void
    {
        $testUser       = $this->userRepository->findOneBy(['email' => 'emarr4@ow.ly']);
        $testArticle    = $this->articleRepository->find($this->articleId);

        $this->client->request('GET', '/');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->client->loginUser($testUser);
        $this->client->request('GET', '/article/' . $this->articleId);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('html h1', $testArticle->getTitle());
    }
}
