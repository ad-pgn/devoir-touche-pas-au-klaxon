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
}