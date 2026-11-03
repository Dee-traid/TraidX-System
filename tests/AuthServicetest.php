<?php

use PHPUnit\Framework\TestCase;
use App\Services\AuthService;
use App\Repositories\UserRepository;

class AuthServiceTest extends TestCase
{
    private $userRepositoryMock;
    private AuthService $authService;

    protected function setUp(): void
    {
        // Create a test double/mock for the UserRepository class
        $this->userRepositoryMock = $this->createMock(UserRepository::class);
        
        // Pass the mocked repository into your service layer instance
        $this->authService = new AuthService($this->userRepositoryMock);
    }

    /**
     * Verify that registration throws an exception if required values are missing
     */
    public function testRegisterThrowsExceptionOnMissingInputFields()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("All registration fields are strictly required.");

        // Provide empty properties array payload
        $invalidPayload = [
            'name' => '',
            'email' => 'test@example.com',
            'password' => ''
        ];

        $this->authService->register($invalidPayload);
    }

    /**
     * Verify that registration fails if the email format is structurally invalid
     */
    public function testRegisterThrowsExceptionForInvalidEmailFormatting()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Please provide a valid email formatting address.");

        $invalidPayload = [
            'name' => 'John Doe',
            'email' => 'not-a-valid-email',
            'password' => 'securePassword123'
        ];

        $this->authService->register($invalidPayload);
    }

    /**
     * Test successful user registration workflow when input parameters are fully valid
     */
    public function testRegisterSucceedsWithValidData()
    {
        $validPayload = [
            'name' => 'Joshua Emmanuel',
            'email' => 'joshua@example.com',
            'password' => 'strongSecretPassword'
        ];

        // Tell our mock repository to report that this email does NOT exist yet
        $this->userRepositoryMock->method('findByEmail')
            ->with($validPayload['email'])
            ->willReturn(null);

        // Tell our mock repository to return true when saving the user record
        $this->userRepositoryMock->method('save')
            ->willReturn(true);

        // Run the service action engine
        $result = $this->authService->register($validPayload);

        // Assert structural return types and state matches
        $this->assertIsArray($result);
        $this->assertStringStartsWith('user-', $result['id']);
        $this->assertEquals($validPayload['name'], $result['name']);
        $this->assertEquals($validPayload['email'], $result['email']);
    }
}