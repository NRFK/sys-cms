<?php

// Ensure configuration and database connections are loaded first.
// These paths are relative to the 'platform' directory where this file resides.
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';

/**
 * A simple placeholder for a robust error logging mechanism.
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
 * @param array $config Global configuration settings, typically loaded from config.php.
 * Passed as an argument for better testability and explicit dependency.
 * @return void
 */
function renderAdmin(string $page = 'home', ?string $template = null, array $config): void {
    // --- RE-ADDED GLOBAL DECLARATIONS HERE ---
    // These variables are defined in config.php (global scope) but needed in this function's scope
    // and its included files.
    global $menuItems;
    global $conn;
    // $config does NOT need 'global' here because it's passed as a parameter.
    // ----------------------------------------

    // Sanitize $page: Allow only alphanumeric characters, underscores, dashes, and forward slashes.
    $page = preg_replace('/[^a-zA-Z0-9_\-\/]/', '', $page);
    $page = rtrim($page, '/');
    if (empty($page)) {
        $page = 'home'; // Default admin page if sanitization results in an empty string.
    }

    // --- Template Determination and Sanitization for Backend ---
    $selectedTemplate = '';
    if ($template !== null) {
        $selectedTemplate = $template;
    } elseif (isset($_GET['template'])) {
        $selectedTemplate = basename($_GET['template']);
    } else {
        $selectedTemplate = $config['admin_default_template'] ?? 'default';
    }

    // --- Template Whitelisting for Enhanced Security (Backend Only) ---
    $allowedTemplates = [
        'default',
        'another_admin_theme'
    ];

    if (!in_array($selectedTemplate, $allowedTemplates)) {
        logError("Security Alert: Attempted to load an invalid backend template: '{$selectedTemplate}'. Falling back to 'default'.");
        $selectedTemplate = 'default';
    }

    // --- FINAL PATHS CONFIRMED FOR 'platform/' AS THE ROOT OF CONTENT/TEMPLATES ---
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
        $pageFile = "{$pagesPath}/404.php";

        if (!file_exists($pageFile)) {
            include "{$templatePath}/header.php";
            echo "<div class='container mt-5 alert alert-danger'>404 - Page '" . htmlspecialchars($page) . "' not found.</div>";
            return;
        }
    }

    // Include the main layout file if it exists, otherwise fall back to header/page/footer.
    $layoutFile = "{$templatePath}/layout.php";
    if (file_exists($layoutFile)) {
        include $layoutFile;
    } else {
        include "{$templatePath}/header.php";
        include $pageFile;
        include "{$templatePath}/footer.php";
    }
}

/**
 * Formats a dot-separated username.
 */
function formatUsername(string $username): string {
    return ucwords(str_replace('.', ' ', $username));
}

/**
 * Splits a full name.
 */
function splitName(string $fullName): array {
    $parts = explode('.', $fullName, 2);
    return count($parts) < 2 ? ['firstname' => $fullName, 'lastname' => ''] : ['firstname' => $parts[0], 'lastname' => $parts[1]];
}

?>