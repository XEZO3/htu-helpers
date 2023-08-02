<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
      VerifyEmail::toMailUsing(function ($notifiable, $url) {
         $urlParm = explode("/",$url);
            $id = $urlParm[6];
            $parameters = explode("?",$urlParm[7]);
            $key = $parameters[0];
            $andparms = explode("&",$parameters[1]);
            $expir = explode("=",$andparms[0])[1];
            $signature = explode("=",$andparms[1])[1];
          $fullUrl = "https://htu-helper.online/#/verify/$id/$key/$expir/$signature";
        return (new MailMessage)
            ->subject('Verify Email Address')
            ->from('verification@htu-helper.online', 'htu helper team')
            ->greeting('HTU Helper')
            ->action('Verify Email Address', $fullUrl)
            ->salutation('Regards, HTU Helper');
    });
    }
}
