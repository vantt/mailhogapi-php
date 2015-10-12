<?php
namespace MailHog\v2;

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
     *
     * @internal param Client $client
     */
    public function __construct($host = '127.0.0.1', $port = 8025) {
        $this->client = new Client(['base_uri' => 'http://' . $host . ':' . $port . '/api/v2/']);
    }


    /**
     * Get all the emails
     *
     * @param int $start
     * @param int $limit
     *
     * @return array
     */
    public function getAllEmails($start = 0, $limit = 0) {
        $emails = array();
        $query  = array();

        if ($start) {
            $query['start'] = $start;
        }

        if ($limit) {
            $query['limit'] = $limit;
        }

        $response = $this->client->get('messages', ['query' => $query]);

        if (200 == $response->getStatusCode()) {
            $result = json_decode($response->getBody());

            foreach ($result->items as $item) {
                $emails[] = $this->buildEmail($item);
            }
        }

        return $emails;
    }

    /**
     * Get last email sent
     *
     * @return Email
     */
    public function getLastEmail() {
        return reset($this->getAllEmails());
    }

    /**
     * @param string $keyword
     * @param int    $start
     * @param int    $limit
     *
     * @return array
     */
    public function searchInFrom($keyword, $start = 0, $limit = 0) {
        $emails = array();
        $query  = array('kind' => 'from', 'query' => $keyword);

        if ($start) {
            $query['start'] = $start;
        }

        if ($limit) {
            $query['limit'] = $limit;
        }

        $response = $this->client->get('search', ['query' => $query]);

        if (200 == $response->getStatusCode()) {
            $result = json_decode($response->getBody());

            foreach ($result->items as $item) {
                $emails[] = $this->buildEmail($item);
            }
        }

        return $emails;
    }

    /**
     * @param string $keyword
     * @param int    $start
     * @param int    $limit
     *
     * @return array
     */
    public function searchInTo($keyword, $start = 0, $limit = 0) {
        $emails = array();
        $query  = array('kind' => 'to', 'query' => $keyword);

        if ($start) {
            $query['start'] = $start;
        }

        if ($limit) {
            $query['limit'] = $limit;
        }

        $response = $this->client->get('search', ['query' => $query]);

        if (200 == $response->getStatusCode()) {
            $result = json_decode($response->getBody());

            foreach ($result->items as $item) {
                $emails[] = $this->buildEmail($item);
            }
        }

        return $emails;
    }

    /**
     * @param string $keyword
     * @param int    $start
     * @param int    $limit
     *
     * @return array
     */
    public function searchContaining($keyword, $start = 0, $limit = 0) {
        $emails = array();
        $query  = array('kind' => 'containing', 'query' => $keyword);

        if ($start) {
            $query['start'] = $start;
        }

        if ($limit) {
            $query['limit'] = $limit;
        }

        $response = $this->client->get('search', ['query' => $query]);

        if (200 == $response->getStatusCode()) {
            $result = json_decode($response->getBody());

            foreach ($result->items as $item) {
                $emails[] = $this->buildEmail($item);
            }
        }

        return $emails;
    }

    /**
     * @param string $id
     *
     * @return Email|FALSE
     */
    public function getEmailById($id) {
        throw new \BadFunctionCallException('This service has not be implemented.');
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function deleteEmail($id) {
        throw new \BadFunctionCallException('This service has not be implemented.');
    }


    /**
     * Delete all emails
     *
     * @return mixed
     */
    public function deleteAllEmails() {
        throw new \BadFunctionCallException('This service has not be implemented.');
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