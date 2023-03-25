<?php

namespace Tests\Feature;

use App\Enums\RouteNames;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class PointCalculationTest extends TestCase
{
    private $url;

    protected function setUp(): void
    {
        parent::setUp();

        $this->url = route(RouteNames::CALCULATE);
    }

    /**
     * A basic test example.
     */
    public function test_route_exists(): void
    {
        $response = $this->post(
            $this->url
        );
        $response->assertOk();
    }
}
