<?php

namespace App;

use App\Entity\Comment;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SpamCheker {
    private $endpoint;

    public function __construct(
        private HttpClientInterface $client,
        #[Autowire('%env(AKISMET_KEY)%')] private string $akismetkey
    ) {
        $this->endpoint = sprintf('https://rest.akismet.com/1.1/comment-check');
    }

    public function getSpamScore(Comment $comment, array $context): int {
        $response = $this->client->request('POST', $this->endpoint, [
            'body' => array_merge($context, [
                'api_key' => $this->akismetkey,
                'blog' => 'https://127.0.0.1:8000',
                'comment_type' => 'comment',
                'comment_author' => $comment->getAuthor(),
                'comment_author_email' => $comment->getEmail(),
                'comment_content' => $comment->getText(),
                'comment_date_gmt' => $comment->getCreatedAt()->format('c'),
                'blog_lang' => 'en',
                'blog_charset' => 'UTF-8',
                'is_test' => true,
            ])
        ]);

        $headers = $response->getHeaders();
        if('discard' === ($headers['x-akismet-pro-tip'][0] ?? '')) {
            return 1;
        }

        $content = $response->getContent();
        
        if(isset($headers['x-akismet-debug-help'][0])) {
            throw new \RuntimeException(sprintf('Unable to check for spam: %s (%s).', $content, $headers['x-akismet-debug-help'][0]));
        }

        return 'true' === $content ? 1:0;
    }
}