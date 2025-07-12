<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Register;

class RegisterDosen extends Register
{
    protected function handleRegistration(array $data): User
    {
        // Buat user baru
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'nomor_induk' => $data['nomor_induk'],
            'password' => $data['password'],
        ]);

        // Assign Role ke User
        $user->assignRole('dosen');

        return $user;
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getUserNameFormComponent(),
                        // $this->getWhatsappFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        // $this->getRoleFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Email')
            ->email()
            ->required()
            ->maxLength(255)
            ->unique($this->getUserModel());
    }

    protected function getUserNameFormComponent(): Component
    {
        return TextInput::make('nomor_induk')
            ->label('Username (NIDN)')
            ->numeric()
            ->required()
            ->maxLength(255)
            ->unique($this->getUserModel());
    }

    protected function getNameFormComponent(): Component
    {
        return TextInput::make('name')
            ->label('Nama Dosen')
            ->required()
            ->maxLength(255)
            ->unique($this->getUserModel());
    }
    // protected function getWhatsappFormComponent(): Component
    // {
    //     return TextInput::make('whatsapp')
    //         ->label('Whatsapp')
    //         ->required()
    //         ->maxLength(255);
    // }

    // protected function getRoleFormComponent(): Component
    // {
    //     return Select::make('roles')
    //         ->preload()
    //         ->default('member')
    //         ->relationship('roles', 'name');
    // }
}
