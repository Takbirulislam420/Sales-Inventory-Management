<?php

namespace App;

use App\Models\MailProvider;
use Illuminate\Support\Facades\Config;

class MailConfigaration
{
    public static function configure(){
        $mailProviderModel= MailProvider::active();

        if($mailProviderModel){
            Config::set('mail.driver',$mailProviderModel->driver);
            Config::set('mail.host',$mailProviderModel->host);
            Config::set('mail.port',$mailProviderModel->port);
            Config::set('mail.username',$mailProviderModel->user_name);
            Config::set('mail.password',$mailProviderModel->password);
            Config::set('mail.from.address',$mailProviderModel->from_name);
            Config::set('mail.from.name',$mailProviderModel->name);
        }
    }
}
