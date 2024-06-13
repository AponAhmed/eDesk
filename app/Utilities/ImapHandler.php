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
        $flags = $this->sender->imap_options['flags'] ?? "/imap/ssl/novalidate-cert";//"/imap/ssl/validate-cert"
        $parameters = $this->getParameters();

        try {
            $this->server = new Server($host, $port, $flags, $parameters);
        } catch (\Exception $e) {
            error_log("Error initializing IMAP server: " . $e->getMessage());
        }
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


        $emails = [];
        
        try {
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
    
       
            foreach ($messages as $message) {
                
                try {
                    $rawMessage = $message->getRawMessage();
                    $bodyHtml = $message->getBodyHtml();
                    $bodyText = $message->getBodyText();
                
                    $body = !empty($bodyHtml) ? $bodyHtml : $bodyText;
                
                    if (!empty($body)) {
                        // Detect encoding
                        $encoding = $this->detectEncoding($body);
                        
                        if ($encoding) {
                            // Convert to UTF-8 using detected encoding
                            $body = mb_convert_encoding($body, 'UTF-8', $encoding);
                        } else {
                            // Attempt common encodings if detection fails
                            $encodings = ['ISO-8859-1', 'Windows-1252', 'UTF-8'];
                            foreach ($encodings as $fallbackEncoding) {
                                $convertedBody = @mb_convert_encoding($body, 'UTF-8', $fallbackEncoding);
                                if ($convertedBody !== false) {
                                    $body = $convertedBody;
                                    break;
                                }
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // Handle exception
                   $body="Not Decoded Message Body,";
                }
                
                $MessageData = [
                    'row' => $rawMessage,
                    'object' => $message,
                    'number' => $message->getNumber(),
                    'id' => $message->getId(),
                    'subject' => $message->getSubject(),
                    'from' => $message->getFrom(),
                    'to' => $message->getTo(),
                    'date' => $message->getDate() ? $message->getDate() : "",
                    'headers' => $message->getHeaders(),
                    'body' => $body,
                ];
                
                    // Your further processing logic here
                
                $emails[] = $MessageData;
                //dd($MessageData);
                // Move emails to another label if requested
                if ($movedBox !== false) {
                    $message->move($movedBox);
                }
                // Mark the message as seen (optional)
                if (isset($options['markSeen']) && $options['markSeen'] === true) {
                    $message->markAsSeen();
                }
    
                // If $count is specified and reached, break out of the loop
                if ($count > 0 && count($emails) >= $count) {
                    break;
                }
            }
    
            // Close the connection
            $connection->expunge(); // Expunge deleted messages
            $connection->close();

        } catch (\Ddeboer\Imap\Exception\AuthenticationFailedException $e) {
            echo 'Authentication failed: ', $e->getMessage();
        }

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
    
    /**
     * Detects the encoding of a given string.
     *
     * @param string $content
     * @return string|null
     */
    function detectEncoding($content) {
        $encoding = mb_detect_encoding($content, mb_detect_order(), true);
    
        // Log the detected encoding for debugging purposes
        error_log('Detected encoding: ' . ($encoding ?: 'none'));
    
        return $encoding;
    }

}
