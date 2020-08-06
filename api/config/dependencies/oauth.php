<?php

use App\Http\Action\Auth\OAuthAction;
use Domain\OAuth\Entity\AccessToken\AccessTokenRepository;
use Domain\OAuth\Entity\AuthCode\AuthCodeRepository;
use Domain\OAuth\Entity\Client\ClientRepository;
use Domain\OAuth\Entity\RefreshToken\RefreshTokenRepository;
use Domain\OAuth\Entity\Scope\ScopeRepository;
use Domain\OAuth\Entity\User\UserRepository;
use Framework\Http\Psr7\ResponseFactory;
use Furious\Container\Container;
use League\OAuth2\Server;

/** @var Container $container */

$container->set(OAuthAction::class, function (Container $container) {
    return new OAuthAction(
        $container->get(Server\AuthorizationServer::class),
        $container->get(ResponseFactory::class)
    );
});

$container->set(Server\AuthorizationServer::class, function (Container $container) {
    $config = $container->get('config')['oauth'];

    $clientRepository = $container->get(Server\Repositories\ClientRepositoryInterface::class);
    $scopeRepository = $container->get(Server\Repositories\ScopeRepositoryInterface::class);
    $accessTokenRepository = $container->get(Server\Repositories\AccessTokenRepositoryInterface::class);
    $authCodeRepository = $container->get(Server\Repositories\AuthCodeRepositoryInterface::class);
    $refreshTokenRepository = $container->get(Server\Repositories\RefreshTokenRepositoryInterface::class);
    $userRepository = $container->get(Server\Repositories\UserRepositoryInterface::class);

    $server = new Server\AuthorizationServer(
        $clientRepository,
        $accessTokenRepository,
        $scopeRepository,
        new Server\CryptKey($config['private_key_path'], null, false),
        $config['encryption_key']
    );

    $grant = new Server\Grant\AuthCodeGrant(
        $authCodeRepository,
        $refreshTokenRepository,
        new DateInterval('PT10M')
    );

    $server->enableGrantType($grant, new DateInterval('PT1H'));

    $server->enableGrantType(new Server\Grant\ClientCredentialsGrant(), new DateInterval('PT1H'));

    $server->enableGrantType(new Server\Grant\ImplicitGrant(new DateInterval('PT1H')));

    $grant = new Server\Grant\PasswordGrant($userRepository, $refreshTokenRepository);
    $grant->setRefreshTokenTTL(new DateInterval('P1M'));
    $server->enableGrantType($grant, new DateInterval('PT1H'));

    $grant = new Server\Grant\RefreshTokenGrant($refreshTokenRepository);
    $grant->setRefreshTokenTTL(new DateInterval('P1M'));
    $server->enableGrantType($grant, new DateInterval('PT1H'));

    return $server;
});

$container->set(Server\ResourceServer::class, function (Container $container) {
    $config = $container->get('config')['oauth'];

    $accessTokenRepository = $container->get(Server\Repositories\AccessTokenRepositoryInterface::class);

    return new Server\ResourceServer(
        $accessTokenRepository,
        new Server\CryptKey($config['public_key_path'], null, false)
    );
});

$container->set(Server\Middleware\ResourceServerMiddleware::class, function (Container $container) {
    return new Server\Middleware\ResourceServerMiddleware(
        $container->get(Server\ResourceServer::class)
    );
});

$container->set(Server\Repositories\ClientRepositoryInterface::class, function (Container $container) {
    $config = $container->get('config')['oauth'];
    return new ClientRepository($config['clients']);
});

$container->set(Server\Repositories\ScopeRepositoryInterface::class, function (Container $container) {
    return new ScopeRepository();
});

$container->set(Server\Repositories\AuthCodeRepositoryInterface::class, function (Container $container) {
    return $container->get(AuthCodeRepository::class);
});

$container->set(Server\Repositories\AccessTokenRepositoryInterface::class, function (Container $container) {
    return $container->get(AccessTokenRepository::class);
});

$container->set(Server\Repositories\RefreshTokenRepositoryInterface::class, function (Container $container) {
    return $container->get(RefreshTokenRepository::class);
});

$container->set(Server\Repositories\UserRepositoryInterface::class, function (Container $container) {
    return $container->get(UserRepository::class);
});