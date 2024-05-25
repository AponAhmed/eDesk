<?php

namespace App\Utilities;

use App\Interfaces\MailReceiver;
use App\Models\Sender;
use Ddeboer\Imap\Search\Flag\Unseen;
use Ddeboer\Imap\Search\Text\Body;
use Ddeboer\Imap\SearchExpression;
use Ddeboer\Imap\Server;

class ImapHandler implements MailReceiver
{
    private Sender $sender;
    private Server $server;

    public function __construct(Sender $sender)
    {
        $this->sender = $sender;
        $this->initializeServer();
    }

    private function initializeServer()
    {
        $host = $this->sender->imap_options['host'];
        $port = $this->sender->imap_options['port'];
        $flags = $this->sender->imap_options['flags'] ?? "/imap/ssl/validate-cert";
        $parameters = $this->getParameters();

        $this->server = new Server($host, $port, $flags, $parameters);
    }

    private function getParameters(): array
    {
        $security = $this->sender->imap_options['security'] ?? 'ssl';
        switch ($security) {
            case 'tls':
                return ['secure' => 'tls'];
            case 'none':
                return ['secure' => 'none'];
            case 'ssl':
            default:
                return ['secure' => 'ssl'];
        }
    }

    function search()
    {
        $search = new SearchExpression();
        $search->addCondition(new Unseen());
        //$search->addCondition(new To('me@here.com'));
        //$search->addCondition(new Body('contents'));
        //$search->addCondition(new From('aimarscau@hotmail.com'));
        return $search;
    }

    public function getEmails($count = 0,  $options = [])
    {
        $mailbox = $options['mailBox'] ?? 'INBOX';
        $order = $options['order'] ?? 'DESC';
        $moveToLabel = $options['moveToLabel'] ?? null;
        
        // Connect to the IMAP server
        $connection = $this->server->authenticate($this->sender->imap_options['account'], $this->sender->imap_options['password']);

        // Select the specified mailbox
        $mailbox = $connection->getMailbox($mailbox);

        //Move Box
        $movedBox = false;
        if (!empty($moveToLabel)) {
            $movedBox = trim($moveToLabel);
            $movedBox = $connection->getMailbox($movedBox);
        }



        // Search for recent emails
        $messages = $mailbox->getMessages(
            $this->search(),
            SORTDATE,
            true
        );

        // Process each message
        $emails = [];
        foreach ($messages as $message) {
            //Deboer Message
            $MessageData = [
                'row' => $message->getRawMessage(),
                'object' => $message,
                'number' => $message->getNumber(),
                'id' => $message->getId(),
                'subject' => $message->getSubject(),
                'from' => $message->getFrom(), // Message\EmailAddress getFrom()->getName(), getFrom()->getAddress()
                'to' => $message->getTo(), // array of Message\EmailAddress
                'date' => $message->getDate() ? $message->getDate() : "", // DateTimeImmutable
                'headers' => $message->getHeaders(),
                'body' => empty($message->getBodyHtml()) ? $message->getBodyText() : $message->getBodyHtml(),
            ];
            $emails[] = $MessageData;

            // If $count is specified and reached, break out of the loop
            if ($count > 0 && count($emails) >= $count) {
                break;
            }
        }

        // Move emails to another label if requested
        if ($movedBox !== false) {
            foreach ($messages as $message) {
                $message->move($movedBox);
            }
        }
        if (isset($options['markSeen']) && $options['markSeen'] === true) {
            $message->markAsSeen();
        }

        // Close the connection
        $connection->expunge(); // Expunge deleted messages
        $connection->close();

        return $emails;
    }

    public function checkConnection()
    {
        try {
            // Attempt to connect to the IMAP server
            $connection = $this->server->authenticate($this->sender->imap_options['account'], $this->sender->imap_options['password']);

            // Close the connection
            $connection->close();

            return true; // Connection successful
        } catch (\Exception $e) {
            return false; // Connection failed
        }
    }
}
