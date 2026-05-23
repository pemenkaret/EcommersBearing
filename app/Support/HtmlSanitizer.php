<?php

namespace App\Support;

// Helper sanitasi HTML konten user/admin untuk cegah XSS (allowlist tag, hapus event handler & javascript: URI)
class HtmlSanitizer
{
    // Allowlist tag default untuk konten rich-text
    public const DEFAULT_ALLOWED_TAGS = '<p><br><strong><b><em><i><u><ul><ol><li><h1><h2><h3><h4><h5><h6><a><blockquote><span><div><img><table><thead><tbody><tr><th><td>';

    // Bersihkan HTML berbahaya dari $value, pertahankan allowlist tag
    public static function clean(?string $value, ?string $allowedTags = null): ?string
    {
        if ($value === null) {
            return null;
        }

        $allowedTags ??= self::DEFAULT_ALLOWED_TAGS;

        $clean = strip_tags($value, $allowedTags);
        $clean = preg_replace('/\s*on\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $clean);
        $clean = preg_replace('/javascript\s*:/i', '', $clean);

        return $clean;
    }
}
