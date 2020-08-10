<?php

namespace App\Tests\Form;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class CommentFormTest extends WebTestCase
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
        $this->client           = static::createClient();
        $this->userRepository   = static::$container->get(UserRepository::class);
    }

    /**
     * Test comment form from view
     *
     * @return void
     */
    public function testCommentForm(): void 
    {
        $crawler = $this->client->request('GET', '/article/' . $this->articleId);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $testUser = $this->userRepository->findOneBy(['email' => 'emarr4@ow.ly']);
        $this->client->loginUser($testUser);
        
        $crawler = $this->client->request('GET', '/article/' . $this->articleId);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $buttonCrawlerNode = $crawler->selectButton('Add comment');
        $form = $buttonCrawlerNode->form();

        $form = $buttonCrawlerNode->form([
            'comment[title]' => 'Comment title',
            'comment[body]'  => 'Comment body',
        ]);

        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect('/article/' . $this->articleId));
    }
}
