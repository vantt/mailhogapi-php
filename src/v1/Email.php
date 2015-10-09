<?php
namespace MailHog\v1;

use stdClass;

class Email {

    private $email;

    /**
     * MailHogEmail constructor.
     *
     * @param $email
     */
    public function __construct(stdClass $email) {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getId() {
        return $this->email->ID;
    }

    /**
     * @return array();
     */
    public function getFromEmailAddresses() {
        return $this->email->Content->Headers->From;
    }

    /**
     * @return array();
     */
    public function getToEmailAddresses() {
        return $this->email->Content->Headers->To;
    }

    /**
     * @return string
     */
    public function getReplyToEmailAddress() {
        return $this->email->Content->Headers->{'Reply-To'};
    }

    /**
     * @return string
     */
    public function getReturnPathEmailAddress() {
        return $this->email->Content->Headers->{'Return-Path'};
    }

    /**
     * @return string|NULL
     */
    public function getHTMLBody() {

        $parts = $this->email->MIME->Parts;
        $part  = NULL;

        // usually, HTML mime part is located at the 2 array item
        // check its header to make sure it is an html body
        if (!empty($parts[2]->Headers->{'Content-Type'})) {
            foreach ($parts[2]->Headers->{'Content-Type'} as $header_line) {
                if (FALSE !== strpos($header_line, 'html')) {
                    $part = $parts[2];
                    break;
                }
            }
        }

        // if there is no part defined,
        // try to loop through all parts and find the correct html part
        if (!$part) {
            foreach($parts as $_part) {
                if (!empty($_part->Headers->{'Content-Type'})) {
                    foreach ($_part->Headers->{'Content-Type'} as $header_line) {
                        if (FALSE !== strpos($header_line, 'html')) {
                            $part = $_part;
                            break;
                        }
                    }
                }
            }
        }

        // if html part found
        if ($part) {
            return $part->Body;
        }

        return NULL;
    }

    /**
     * @return string|NULL
     */
    public function getTextBody() {
        $parts = $this->email->MIME->Parts;
        $part  = NULL;

        // usually, HTML mime part is located at the 2 array item
        // check its header to make sure it is an html body
        if (!empty($parts[1]->Headers->{'Content-Type'})) {
            foreach ($parts[1]->Headers->{'Content-Type'} as $header_line) {
                if (FALSE !== strpos($header_line, 'plain')) {
                    $part = $parts[1];
                    break;
                }
            }
        }

        // if there is no part defined,
        // try to loop through all parts and find the correct html part
        if (!$part) {
            foreach($parts as $_part) {
                if (!empty($_part->Headers->{'Content-Type'})) {
                    foreach ($_part->Headers->{'Content-Type'} as $header_line) {
                        if (FALSE !== strpos($header_line, 'plain')) {
                            $part = $_part;
                            break;
                        }
                    }
                }
            }
        }

        // if html part found
        if ($part) {
            return $part->Body;
        }

        return NULL;
    }
}