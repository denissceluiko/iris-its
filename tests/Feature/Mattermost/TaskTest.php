<?php

namespace Tests\Feature\Mattermost;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\MattermostRequest;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use DatabaseTransactions;
    use MattermostRequest;

    public function setUp()
    {
        parent::setUp();


    }

    /**
     * @test
     */
    public function can_create_task()
    {
//        $this->send('/t');
    }
}
