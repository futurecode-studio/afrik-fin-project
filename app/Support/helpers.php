<?php

use Illuminate\Support\Str;

if (! function_exists('plain_text')) {
    /**
     * Texte lisible depuis un champ riche (Quill/HTML) stocké en base.
     */
    function plain_text(?string $html, ?int $limit = null): string
    {
        $text = html_entity_decode(strip_tags($html ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/\s+/u', ' ', trim($text)) ?? '';

        if ($limit !== null && $limit > 0) {
            return Str::limit($text, $limit);
        }

        return $text;
    }
}

if (! function_exists('rich_html')) {
    /**
     * HTML riche pour affichage détail (tags sûrs uniquement).
     */
    function rich_html(?string $html): string
    {
        $html = $html ?? '';
        if ($html === '') {
            return '';
        }

        // Contenu déjà échappé (&lt;p&gt;…) → décoder une fois avant nettoyage.
        if (! preg_match('/<\/?[a-z][\s\S]*>/i', $html) && str_contains($html, '&lt;')) {
            $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        $html = preg_replace('#<(script|iframe|object|embed|form|input|button|style)\b[^>]*>.*?</\1>#is', '', $html) ?? '';
        $html = preg_replace('/\son\w+\s*=\s*(["\']).*?\1/iu', '', $html) ?? '';
        $html = preg_replace('/\s(href|src)\s*=\s*(["\'])\s*javascript:[^"\']*\2/iu', '', $html) ?? '';

        $allowed = '<h1><h2><h3><h4><h5><h6><p><br><ul><ol><li><strong><b><em><i><u><s><a><span><div><blockquote><code><pre><hr><table><thead><tbody><tr><th><td><img><figure><figcaption>';

        return strip_tags($html, $allowed);
    }
}

if (! function_exists('feature_enabled')) {
    /**
     * Feature flag (ex. services SGI/SGO encore en « bientôt »).
     */
    function feature_enabled(string $key, bool $default = false): bool
    {
        return \App\Models\FeatureFlag::isEnabled($key, $default);
    }
}
