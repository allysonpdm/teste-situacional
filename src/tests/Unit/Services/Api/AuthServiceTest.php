<?php

namespace Tests\Unit\Services\Api;

use App\DataTransferObjects\Auth\Credentials;
use App\DataTransferObjects\Auth\Token;
use App\Models\User;
use App\Services\Api\AuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $authService;
    protected User $user;
    protected Credentials $credentials;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->credentials = new Credentials(
            email: $this->user->email,
            password: 'password',
        );
        $this->authService = new AuthService();
    }

    public function test_it_can_login_and_return_a_token()
    {

        $result = $this->authService->login($this->credentials);

        $this->assertInstanceOf(Token::class, $result);
    }

    public function test_it_throws_an_exception_when_login_fails()
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $this->expectExceptionMessage('Authentication failed.');

        $credentials = new Credentials(
            email: 'test@example.com',
            password: 'wrong-password',
        );

        $this->authService->login($credentials);
    }

    public function test_it_can_refresh_and_return_a_new_token()
    {
        $this->authService->login($this->credentials);
        $token = $this->authService->refresh();

        $this->assertInstanceOf(Token::class, $token);
    }
    public function test_it_can_return_the_current_authenticated_user()
    {
        $this->authService->login($this->credentials);
        $currentUser = $this->authService->me();

        $this->assertInstanceOf(User::class, $currentUser);
        $this->assertEquals($this->user->id, $currentUser->id);
    }
    public function test_it_can_logout_and_invalidate_the_token()
    {
        $this->authService->login($this->credentials);
        $result = $this->authService->logout();

        $this->assertTrue($result);
    }
}
