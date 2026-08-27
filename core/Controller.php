<?php

declare(strict_types=1);

namespace Core;

use RuntimeException;

/**
 * Contrôleur de base dont héritent tous les contrôleurs de l'application.
 *
 * Fournit le rendu des vues dans un layout et l'échappement des données
 * affichées. Les méthodes de rendu retournent une chaîne : c'est le
 * routeur qui se charge de l'envoyer au navigateur.
 */
abstract class Controller
{
    /**
     * Répertoire racine des templates.
     */
    protected string $templatePath;

    public function __construct()
    {
        $this->templatePath = dirname(__DIR__) . '/templates/';
    }

    /**
     * Rend une vue à l'intérieur d'un layout.
     *
     * @param string               $view   Chemin de la vue, sans extension,
     *                                     relatif à templates/ (ex. 'home/index').
     * @param array<string, mixed> $data   Variables mises à disposition de la vue.
     * @param string               $layout Nom du layout, sans extension.
     *
     * @throws RuntimeException Si la vue ou le layout est introuvable.
     */
    protected function render(string $view, array $data = [], string $layout = 'main'): string
    {
        $content = $this->renderFile($view, $data);

        return $this->renderFile('layout/' . $layout, ['content' => $content]);
    }

    /**
     * Rend une vue sans layout. Utile pour les fragments et les partials.
     *
     * @param array<string, mixed> $data
     *
     * @throws RuntimeException Si la vue est introuvable.
     */
    protected function renderPartial(string $view, array $data = []): string
    {
        return $this->renderFile($view, $data);
    }

    /**
     * Inclut un fichier de template et capture sa sortie.
     *
     * L'inclusion se fait depuis une méthode d'instance : les templates
     * ont donc accès à $this et peuvent appeler $this->e().
     *
     * @param array<string, mixed> $data
     *
     * @throws RuntimeException Si le fichier est introuvable.
     */
    private function renderFile(string $view, array $data): string
    {
        $file = $this->templatePath . $view . '.php';

        if (!is_file($file)) {
            throw new RuntimeException('Template introuvable : ' . $view);
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $file;

        return (string) ob_get_clean();
    }

    /**
     * Formate un groupe date-heure en date courte (JJ/MM/AA).
     */
    public function date(string $gdh): string
    {
        return date('d/m/y', (int) strtotime($gdh));
    }

    /**
     * Formate un groupe date-heure en heure (HH:MM).
     */
    public function heure(string $gdh): string
    {
        return date('H:i', (int) strtotime($gdh));
    }

    /**
     * Échappe une valeur destinée à être affichée dans du HTML.
     *
     * À utiliser systématiquement dans les templates pour toute donnée
     * provenant de la base ou d'une saisie utilisateur.
     */
    public function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Redirige vers une URL interne et interrompt l'exécution.
     *
     * @param string $path Chemin relatif à la racine de l'application,
     *                     commençant par un slash (ex. '/trajets').
     */
    protected function redirect(string $path): never
    {
        header('Location: ' . BASE_URL . $path);
        exit;
    }

    /**
     * Exige qu'un utilisateur soit connecté.
     *
     * Redirige vers le formulaire de connexion dans le cas contraire.
     */
    protected function requireAuth(): void
    {
        if (!Auth::check()) {
            Flash::error('Vous devez être connecté pour accéder à cette page.');
            $this->redirect('/connexion');
        }
    }

    /**
     * Exige que l'utilisateur connecté soit administrateur.
     */
    protected function requireAdmin(): void
    {
        $this->requireAuth();

        if (!Auth::isAdmin()) {
            Flash::error('Accès réservé à l\'administrateur.');
            $this->redirect('/');
        }
    }

    /**
     * Exige que l'utilisateur connecté soit l'auteur d'un trajet.
     */
    protected function requireOwner(int $utilisateurId): void
    {
        $this->requireAuth();

        if (!Auth::owns($utilisateurId)) {
            Flash::error('Vous ne pouvez modifier que vos propres trajets.');
            $this->redirect('/');
        }
    }

    /**
     * Retourne l'utilisateur connecté pour les templates.
     *
     * @return array<string, mixed>|null
     */
    public function utilisateur(): ?array
    {
        return Auth::user();
    }

    /**
     * Indique aux templates si l'utilisateur connecté est administrateur.
     */
    public function estAdmin(): bool
    {
        return Auth::isAdmin();
    }
}