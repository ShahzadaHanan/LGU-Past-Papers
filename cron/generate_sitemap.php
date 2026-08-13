<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

// Setup env
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

$db = new \App\Core\Database();
$appUrl = rtrim($_ENV['APP_URL'] ?? 'http://localhost:8080', '/');

$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

// Static Pages
$staticPages = [
    '/',
    '/departments',
    '/lectures',
    '/about-lgu',
    '/about-us',
    '/alumni',
    '/contact-us'
];

foreach ($staticPages as $page) {
    $xml .= "  <url>\n";
    $xml .= "    <loc>" . htmlspecialchars($appUrl . $page) . "</loc>\n";
    $xml .= "    <changefreq>daily</changefreq>\n";
    $xml .= "    <priority>0.8</priority>\n";
    $xml .= "  </url>\n";
}

// 1. Departments URLs
$departments = $db->fetchAll("SELECT slug FROM departments WHERE is_active = 1");
foreach ($departments as $dept) {
    $xml .= "  <url>\n";
    $xml .= "    <loc>" . htmlspecialchars($appUrl . '/department/' . $dept['slug']) . "</loc>\n";
    $xml .= "    <changefreq>weekly</changefreq>\n";
    $xml .= "    <priority>0.7</priority>\n";
    $xml .= "  </url>\n";
}

// 2. Sub-Departments (Degrees) URLs
$degrees = $db->fetchAll("
    SELECT sd.slug as degree_slug, d.slug as dept_slug 
    FROM sub_departments sd
    JOIN departments d ON sd.department_id = d.id
    WHERE sd.is_active = 1 AND d.is_active = 1
");
foreach ($degrees as $deg) {
    $xml .= "  <url>\n";
    $xml .= "    <loc>" . htmlspecialchars($appUrl . '/department/' . $deg['dept_slug'] . '/' . $deg['degree_slug']) . "</loc>\n";
    $xml .= "    <changefreq>weekly</changefreq>\n";
    $xml .= "    <priority>0.6</priority>\n";
    $xml .= "  </url>\n";
}

// 3. Papers URLs
$papers = $db->fetchAll("SELECT slug, updated_at FROM papers");
foreach ($papers as $paper) {
    $lastMod = date('Y-m-d', strtotime($paper['updated_at']));
    $xml .= "  <url>\n";
    $xml .= "    <loc>" . htmlspecialchars($appUrl . '/paper/' . $paper['slug']) . "</loc>\n";
    $xml .= "    <lastmod>{$lastMod}</lastmod>\n";
    $xml .= "    <changefreq>monthly</changefreq>\n";
    $xml .= "    <priority>0.5</priority>\n";
    $xml .= "  </url>\n";
}

$xml .= '</urlset>';

// Write to public/sitemap.xml
file_put_contents(dirname(__DIR__) . '/public/sitemap.xml', $xml);
echo "Sitemap generated successfully in public/sitemap.xml\n";
