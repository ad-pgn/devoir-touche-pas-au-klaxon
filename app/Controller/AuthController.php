<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\UserModel;
use Core\Auth;
use Core\Controller;
use Core\Flash;

/**
 * Authentification des utilisateurs.
 */
final class AuthController extends Controller
{
    /**
     * Affiche le formulaire de connexion.
     */
    public function formulaire(): string
    {
        if (Auth::check()) {
            $this->redirect('/');
        }

        return $this->render('auth/connexion', ['email' => '']);
    }

    /**
     * Traite la soumission du formulaire de connexion.
     */
    public function connexion(): string
    {
        $email      = trim((string) ($_POST['email'] ?? ''));
        $motDePasse = (string) ($_POST['mot_de_passe'] ?? '');

        if ($email === '' || $motDePasse === '') {
            return $this->render('auth/connexion', [
                'email'  => $email,
                'erreur' => 'Veuillez renseigner votre email et votre mot de passe.',
            ]);
        }

        $utilisateur = (new UserModel())->findByEmail($email);

        if ($utilisateur === null
            || !password_verify($motDePasse, (string) $utilisateur['mot_de_passe'])
        ) {
            return $this->render('auth/connexion', [
                'email'  => $email,
                'erreur' => 'Identifiants incorrects.',
            ]);
        }

        Auth::login($utilisateur);
        Flash::success('Bienvenue ' . $utilisateur['prenom'] . ' ' . $utilisateur['nom'] . '.');

        $this->redirect(Auth::isAdmin() ? '/admin' : '/');
    }

    /**
     * Ferme la session et revient à la page d'accueil.
     */
    public function deconnexion(): never
    {
        Auth::logout();
        Flash::success('Vous avez été déconnecté.');

        $this->redirect('/');
    }
}