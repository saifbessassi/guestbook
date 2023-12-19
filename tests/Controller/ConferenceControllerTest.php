<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConferenceControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Give your feedback');
    }

    public function testCommentSubmission(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/conference/tunis-2020');

        $client->submitForm('Submit', [
            'comment[author]' => 'xxx',
            'comment[text]' => 'Feedback for test',
            'comment[email]' => 'xx@example.com',
            'comment[photo]' => dirname(__DIR__, 2).'/public/images/under-construction.gif'
        ]);

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('div:contains("There are 1 comments")');
    }

    public function testConferencePage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertCount(2, $crawler->filter('h4'));

        $client->clickLink('View');

        $this->assertPageTitleContains('Tunis');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Tunis 2020');
        $this->assertSelectorExists('div:contains("No comments have been posted yet for this conference.")');
    }
}
