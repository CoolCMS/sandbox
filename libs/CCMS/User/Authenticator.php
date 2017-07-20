<?php
namespace CCMS\User;

use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;
use Nette\Security\IIdentity;

class Authenticator implements IAuthenticator
{
	/** @var UserManager */
	private $userManager;

	public function __construct(UserManager $userManager)
	{
		$this->userManager = $userManager;
	}

	/**
	 * Performs an authentication against e.g. database.
	 * and returns IIdentity on success or throws AuthenticationException
	 * @param array $credentials
	 * @return IIdentity
	 * @throws AuthenticationException
	 */
	function authenticate(array $credentials)
	{
		list($email, $password) = $credentials;
		/** @var FALSE|User $user */
		$user = $this->userManager->isValidLogin($email, $password);
		if ($user) {
			return new Identity($user->getId(), [$user->getRole()], $this->userManager->createIdentityArray($user));
		} else {
			throw new AuthenticationException();
		}
	}
} 