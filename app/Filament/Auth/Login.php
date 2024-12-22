<?php
    namespace App\Filament\Auth;

    use Filament\Pages\Auth\Login as AuthLogin;

    class Login extends AuthLogin
    {
        public function mount() : void 
        {
            parent::mount();
            $this->form->fill([
                "email"=>"admin@gmail.com",
                "password"=>"admin12345",
                "remember"=>true,
            ]);
        }
    }
?>