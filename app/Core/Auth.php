<?php 

namespace App\Core;

use App\Models\UsuarioModel;

class Auth
{
    public static function attempt(string $email, string $password): bool
    {
        // Exemplo: busca na base de dados
        $user = UsuarioModel::where('email', $email)->first();

        if(!$user || !password_verify($password, $user->password))
        {
            return false;
        }

        $_SESSION['user_id'] = $user->id;
        
        return true;
    }

    public static function user()
    {
        if (!isset($_SESSION['user_id'])) return null;

        return UsuarioModel::find($_SESSION['user_id']);
    }

    public static function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function logout(): void
    {
        unset($_SESSION['user_id']);
        session_destroy();
    }

    public static function id(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }
}