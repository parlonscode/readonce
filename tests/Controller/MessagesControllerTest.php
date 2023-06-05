<?php

namespace App\Tests\Controller;

use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MessagesControllerTest extends WebTestCase
{
     /** @test */
    public function homepage_is_displayed_successfully(): void
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Read-once messages to friends and family');
        $this->assertPageTitleSame('ReadOnce');
    }

    /** @test */
    public function messages_are_saved_in_db(): void
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $client->submitForm('Send Message', [
            'message[email]' => 'johndoe@example.com',
            'message[body]' => 'Hello from the other side!'
        ]);

        $messageRepository = static::getContainer()->get(MessageRepository::class);
        $this->assertSame(1, $messageRepository->count([]));
        $message = $messageRepository->findOneByEmail('johndoe@example.com');
        $this->assertSame('Hello from the other side!', $message->getBody());
    }

    /** @test */
    public function user_is_redirected_to_homepage_with_success_message(): void
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $client->submitForm('Send Message', [
            'message[email]' => 'johndoe@example.com',
            'message[body]' => 'Hello from the other side!'
        ]);

        $crawler = $client->followRedirect();

        $this->assertRouteSame('app_home');
        $this->assertSame(
            'Message sent successfully.',
            $crawler->filter('div.text-success > small')->text()
        );
    }

    /** @test */
    public function emails_are_queued(): void
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $client->submitForm('Send Message', [
            'message[email]' => 'johndoe@example.com',
            'message[body]' => 'Hello from the other side!'
        ]);

        $this->assertQueuedEmailCount(1);
    }

    /** @test */
    public function email_content_is_correct(): void
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $client->submitForm('Send Message', [
            'message[email]' => 'johndoe@example.com',
            'message[body]' => 'Hello from the other side!'
        ]);

        $email = $this->getMailerMessage();

        $messageRepository = static::getContainer()->get(MessageRepository::class);
        $messageUuid = (string) $messageRepository->findOneByEmail('johndoe@example.com')->getUuid();

        $this->assertEmailHtmlBodyContains($email, 'You have a new read-once message!');
        $this->assertEmailHtmlBodyContains($email, "/messages/{$messageUuid}");
        $this->assertEmailTextBodyContains($email, 'You have a new read-once message!');
        $this->assertEmailTextBodyContains($email, "/messages/{$messageUuid}");
        $this->assertEmailAddressContains($email, 'sender', 'hello@readonce.com');
        $this->assertEmailHeaderSame($email, 'To', 'johndoe@example.com');
    }

    /** @test */
    public function message_email_and_body_should_not_be_blank(): void
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $client->submitForm('Send Message', [
            'message[email]' => '',
            'message[body]' => ''
        ]);

        $this->assertSelectorCount(2, 'div.invalid-feedback');
        $this->assertSelectorTextContains('div.invalid-feedback', 'This value should not be blank.');
    }

    /** @test */
    public function message_email_should_be_a_valid_email_address(): void
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $client->submitForm('Send Message', [
            'message[email]' => 'INVALID_EMAIL',
            'message[body]' => 'Hello from the other side!'
        ]);

        $this->assertSelectorCount(1, 'div.invalid-feedback');
        $this->assertSelectorTextContains('div.invalid-feedback', 'This value is not a valid email address.');
    }

    /** @test */
    public function message_is_soft_deleted_once_read(): void
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $client->submitForm('Send Message', [
            'message[email]' => 'johndoe@example.com',
            'message[body]' => 'Hello from the other side!'
        ]);

        $messageRepository = static::getContainer()->get(MessageRepository::class);
        $message = $messageRepository->findOneByEmail('johndoe@example.com');
        $messageUuid = (string) $message->getUuid();

        $client->request('GET', "/messages/{$messageUuid}");
        $this->assertSelectorTextContains('strong', $message->getBody());

        $client->request('GET', "/messages/{$messageUuid}");
        $this->assertSelectorTextContains('strong', 'It seems like this message has been deleted.');
    }
}
