<?php namespace Messenger\Facades;

use Messenger\Facades\Response;

class ResponseTest extends \TestCase {

    public function testJsonReturns204WithNoData() {
        $response = Response::json([]);

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals(null, $response->getContent());
    }

    public function testJsonReturns200WithData() {
        $data = ["data"];
        $response = Response::json($data);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('["data"]', json_encode($data));
    }

}