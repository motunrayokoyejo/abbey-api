<?php

namespace Tests\Unit\Service;

use App\Enum\Status;
use App\Exceptions\AbbeyException;
use App\Models\Connection;
use App\Models\User;
use App\Services\ConnectionService;
use App\Support\PendingConnection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConnectionServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testMakeConnection()
    {
        $user = User::factory()->create();

        $pendingConnection = ConnectionService::makeConnection($user);

        $this->assertInstanceOf(PendingConnection::class, $pendingConnection);
        $this->assertNotNull($pendingConnection);
    }


    public function testUpdateConnectionThrowsExceptionWhenUserDoesNotHaveRelationship()
    {
        $this->expectException(AbbeyException::class);

        $this->expectExceptionMessage('Invalid relationship');

        $user = User::factory()->create();

        ConnectionService::updateConnectionStatus($user, 'nonexistent-uuid', Status::ACCEPTED);
    }

    public function testSearch()
    {
        $user = User::factory()->create();

        Connection::factory()->count(15)->create(['user_id' => $user->id]);

        $paginatedConnections = ConnectionService::search($user);

        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $paginatedConnections);
        $this->assertCount(10, $paginatedConnections->items());
    }


}
