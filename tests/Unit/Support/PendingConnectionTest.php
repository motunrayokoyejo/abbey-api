<?php

namespace Tests\Unit\Support;

use App\Enum\Status;
use App\Models\User;
use App\Support\PendingConnection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PendingConnectionTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected User $follower;

    protected PendingConnection $pendingConnection;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->follower = User::factory()->create();

        $this->pendingConnection = (new PendingConnection($this->user));
    }

    public function testSetStatus()
    {
        $this->pendingConnection->setStatus(Status::ACCEPTED);

        $this->assertEquals(Status::ACCEPTED, $this->pendingConnection->status);
    }

    public function testSetFollower()
    {
        $followerId = maskId($this->follower->uuid);

        $this->pendingConnection->setFollower($followerId);

        $this->assertEquals($this->follower->id, $this->pendingConnection->follower->id);
    }

    public function testBuildFollowerAttribute()
    {
        $followerId = maskId($this->follower->uuid);

        $this->pendingConnection->setFollower($followerId);

        $this->pendingConnection->setStatus(Status::BLOCKED);

        $attribute = $this->pendingConnection->buildFollowerAttribute();

        $this->assertEquals([
            'uuid'=> null,
            'status'=> Status::BLOCKED,
            'follower_id'=> $followerId,
            'user_id' => $this->user->id
        ], $attribute);
    }

    public function testExecute()
    {
        $connection = (new PendingConnection($this->user))
        ->setStatus(Status::BLOCKED)
        ->setFollower(maskId($this->follower->uuid))
        ->execute();

        self::assertNotNull($connection);

        self::assertEquals(Status::BLOCKED, $connection->status);

        self::assertEquals($this->follower->id, $connection->follower_id);
    }
}
