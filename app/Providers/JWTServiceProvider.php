<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Service\JWTService;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

class JWTServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Configuration::class, function(){
            return Configuration::forSymmetricSigner(
                // You may use any HMAC variations (256, 384, and 512)
                new Sha256(),
                // replace the value below with a key of your own!
                InMemory::base64Encoded('mBC5v1sOKVvbdEitdSBenu59nfNfhwkedkJVNabosTw=')
                // You may also override the JOSE encoder/decoder if needed by providing extra arguments here
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
