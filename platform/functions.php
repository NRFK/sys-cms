<?php

// Ensure configuration and database connections are loaded first.
// These paths are relative to the 'platform' directory where this file resides.
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';

/**
 * A simple placeholder for a robust error logging mechanism.
 * In a production environment, this should log to a file, a service (e.g., Sentry),
 * or a system log, rather than directly displaying errors to the user.
 *
 * @param string $message The error message to log.
 */
function logError(string $message): void {
    error_log("[CMS Error] " . $message);
}

/**
 * Renders an admin page, specifically for the backend administration panel.
 * All paths for templates and pages are assumed to be relative to the 'platform' directory.
 *
 * @param string $page The requested page name (e.g., 'dashboard', 'users/list'). Defaults to 'home'.
 * @param string|null $template The specific template name to use within the 'backend' theme group.
 * If null, it attempts to get from $_GET['template'], then config, then defaults to 'default'.
 * @param array $config Global configuration settings, typically loaded from config.php.
 * Passed as an argument for better testability and explicit dependency.
 * @return void
 */
function renderAdmin(string $page = 'home', ?string $template = null, array $config): void {
    // Sanitize $page: Allow only alphanumeric characters, underscores, dashes, and forward slashes.
    $page = preg_replace('/[^a-zA-Z0-9_\-\/]/', '', $page);
    $page = rtrim($page, '/');
    if (empty($page)) {
        $page = 'home'; // Default admin page if sanitization results in an empty string.
    }

    // --- Template Determination and Sanitization for Backend ---
    $selectedTemplate = '';
    if ($template !== null) {
        // If a template name is explicitly passed as an argument (e.g., 'default' from admin/index.php),
        // we trust it and use it directly. Assuming it's just the theme name.
        $selectedTemplate = $template;
    } elseif (isset($_GET['template'])) {
        // If template comes from GET, sanitize it using basename() for security.
        // This makes sure only the name (e.g., 'another_admin_theme') is extracted.
        $selectedTemplate = basename($_GET['template']);
    } else {
        // Fallback to a default backend template name from config, or 'default'.
        // Assuming $config['admin_default_template'] or similar exists.
        $selectedTemplate = $config['admin_default_template'] ?? 'default';
    }

    // --- Template Whitelisting for Enhanced Security (Backend Only) ---
    // This list should contain only the NAMES of your backend themes, NOT including 'backend/'.
    $allowedTemplates = [
        'default',
        'another_admin_theme' // Add other backend theme names here (e.g., 'dark_theme')
    ];

    if (!in_array($selectedTemplate, $allowedTemplates)) {
        logError("Security Alert: Attempted to load an invalid backend template: '{$selectedTemplate}'. Falling back to 'default'.");
        $selectedTemplate = 'default'; // Always fall back to 'default' for backend themes.
        // Optionally: http_response_code(400); if you want to be strict.
    }

    $templatePath = __DIR__ . "/templates/backend/{$selectedTemplate}";
    
    $pagesPath = __DIR__ . "/pages";

    // Validate if the selected template directory actually exists.
    if (!is_dir($templatePath)) {
        logError("Configuration Error: Backend template directory not found at '{$templatePath}'. Check your file system and permissions.");
        http_response_code(500);
        echo "<div class='container mt-5 alert alert-danger'>Server Error: The requested backend template could not be found. Please contact support.</div>";
        return;
    }

    // Attempt to find the requested page file.
    $pageFile = "{$pagesPath}/{$page}.php";
    $pageFound = false;

    if (file_exists($pageFile)) {
        $pageFound = true;
    } else {
        $indexFile = "{$pagesPath}/{$page}/index.php";
        if (file_exists($indexFile)) {
            $pageFile = $indexFile;
            $pageFound = true;
        }
    }

    // --- Critical Security Check: Prevent Directory Traversal ---
    // This check is crucial to ensure PHP only loads files from within the intended 'pages' directory.
    if ($pageFound) {
        $realPageFile = realpath($pageFile);
        $realPagesPath = realpath($pagesPath);

        if ($realPageFile === false || $realPagesPath === false || strpos($realPageFile, $realPagesPath) !== 0) {
            http_response_code(400);
            logError("Security Alert: Directory traversal detected for page: '{$pageFile}'. Original request: '{$page}'. Real path: '{$realPageFile}'.");
            die("Invalid page path detected. Access denied.");
        }
    }
    // --- End Security Check ---

    // If the page still hasn't been found, load the 404 page.
    if (!$pageFound) {
        http_response_code(404);
        $pageFile = "{$pagesPath}/404.php"; // Path to your custom 404 error page.

        // If the custom 404.php doesn't exist, provide a generic fallback message.
        if (!file_exists($pageFile)) {
            include "{$templatePath}/header.php";
            echo "<div class='container mt-5 alert alert-danger'>404 - Page '" . htmlspecialchars($page) . "' not found.</div>";
            include "{$templatePath}/footer.php";
            return;
        }
    }

    // Include the main layout file if it exists, otherwise fall back to header/page/footer.
    $layoutFile = "{$templatePath}/layout.php";
    if (file_exists($layoutFile)) {
        include $layoutFile; // layout.php is expected to include $pageFile inside it
    } else {
        include "{$templatePath}/header.php";
        include $pageFile; // This is where the actual page content is pulled in.
        include "{$templatePath}/footer.php";
    }
}

/**
 * Formats a dot-separated username (e.g., "john.doe") into a human-readable format.
 *
 * @param string $username The username string.
 * @return string The formatted name.
 */
function formatUsername(string $username): string {
    return ucwords(str_replace('.', ' ', $username));
}

/**
 * Splits a full name (expected to be dot-separated like "firstname.lastname")
 * into an associative array with 'firstname' and 'lastname' keys.
 *
 * @param string $fullName The full name string.
 * @return array An associative array with 'firstname' and 'lastname'.
 */
function splitName(string $fullName): array {
    $parts = explode('.', $fullName, 2);
    return count($parts) < 2 ? ['firstname' => $fullName, 'lastname' => ''] : ['firstname' => $parts[0], 'lastname' => $parts[1]];
}

?>