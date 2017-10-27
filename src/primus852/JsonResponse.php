<?php

namespace primus852;


class JsonResponse
{

    public $data, $status, $headers;

    /**
     * JsonResponse constructor.
     * @param null $data
     * @param int $status
     * @param array $headers
     */
    public function __construct($data = null, $status = 200, $headers = array())
    {

        $this->data = $data;
        $this->status = $status;
        $this->headers = $headers;

        if (null === $data) {
            $this->data = new \ArrayObject();
        }

        $this->returnResponse();

    }

    /**
     *
     */
    private function returnResponse(){

        if (!empty($this->headers)) {
            foreach ($this->headers as $header) {
                header($header);
            }
        } else {
            header('Content-type: application/json');
        }

        http_response_code($this->status);

        echo json_encode($this->data);

        return true;

    }

}