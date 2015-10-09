<?php
namespace MailHog\v1;

use GuzzleHttp\Client;
use stdClass;

class MailHogAPI {

    /**
     * @var Client
     */
    private $client;

    /**
     * MailHogAPIv2 constructor.
     *
     * @param string $host
     * @param int    $port
     */
    public function __construct($host = '127.0.0.1', $port = 8025) {
        $this->client = new Client(['base_uri' => 'http://' . $host . ':' . $port . '/api/v1/']);
    }


    /**
     * Get all the emails
     *
     * @return Email[]
     */
    public function getAllEmails() {
        $emails   = array();
        $response = $this->client->get('messages');

        if (200 == $response->getStatusCode()) {
            $items = json_decode($response->getBody());

            foreach ($items as $item) {
                $emails[] = $this->buildEmail($item);
            }
        }

        return $emails;
    }

    /**
     * Get last email sent
     *
     * @return FALSE|MailHogEmailData
     */
    public function getLastEmail() {
        return reset($this->getAllEmails());
    }

    /**
     * @param string $id
     *
     * @return Email|FALSE
     */
    public function getEmailById($id) {
        $email    = FALSE;
        $response = $this->client->get('messages/' . $id);

        if (200 == $response->getStatusCode()) {
            $email = $this->buildEmail(json_decode($response->getBody()));
        }

        return $email;
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function deleteEmail($id) {
        $response = $this->client->delete('messages/' . $id);

        return (200 == $response->getStatusCode());
    }


    /**
     * Delete all emails
     *
     * @return mixed
     */
    public function deleteAllEmails() {
        $response = $this->client->delete('messages');

        return (200 == $response->getStatusCode());
    }

    /**
     * @param stdClass $email_data
     *
     * @return Email
     */
    protected function buildEmail(stdClass $email_data) {
        return new Email($email_data);
    }
}