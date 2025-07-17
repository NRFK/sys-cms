<?php
// platform/templates/backend/default/navbar.php

// Ensure $currentRoute is available for active class logic.
// It should be the 'page' variable passed to renderAdmin, or default to 'home'.
// This assumes $page variable is implicitly available in the template scope.
$currentRoute = $page ?? ($_GET['route'] ?? 'home');
?>

<nav class="navbar navbar-expand-lg" data-bs-theme="dark">
	<div class="container-fluid">
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarColor01">
			<ul class="navbar-nav me-auto">
				<?php
				// --- FIX FOR UNDEFINED VARIABLE AND FOREACH WARNINGS ---
				// This check is CRUCIAL. It ensures $menuItems is defined and is an array.
				if (isset($menuItems) && is_array($menuItems)):
				?>
					<?php foreach ($menuItems as $name => $item): ?>
						<?php
							$isDropdown = isset($item['submenu']) && is_array($item['submenu']);
							$authOnly = $item['auth'] ?? null;

							// Authentication status check (based on $_SESSION['user_id'])
							$isLoggedIn = isset($_SESSION['user_id']);

							$shouldShow = (
								($authOnly === true && $isLoggedIn) ||          // Show if 'auth' is true AND user is logged in
								($authOnly === false && !$isLoggedIn) ||       // Show if 'auth' is false AND user is NOT logged in (guest)
								($authOnly === null)                            // Show if 'auth' is null (everyone)
							);

							if (!$shouldShow) continue; // Skip this menu item if it shouldn't be shown

							$icon = $item['icon'] ?? '';
							$newWindow = $item['new_window'] ?? false;

							// --- CORRECTED LINK GENERATION ---
							// Link from config.php is just the route name (e.g., 'users', 'login') or a full URL.
							$itemLinkRoute = $item['link'] ?? '';

							$href = '#'; // Default href for dropdowns or if link is missing
							if (!$isDropdown && !empty($itemLinkRoute)) {
                                if (strpos($itemLinkRoute, 'http') === 0 || strpos($itemLinkRoute, '//') === 0) {
                                    // Handle external links (starting with http/https or //)
                                    $href = htmlspecialchars($itemLinkRoute);
                                } else {
                                    // Assume it's an internal admin route: prepend /admin/?route=
                                    $href = "/admin/?route=" . htmlspecialchars(ltrim($itemLinkRoute, '/'));
                                }
							}

							// --- CORRECTED ACTIVE CLASS LOGIC ---
							// Compare the current route directly with the item's defined route name (from config.php)
							$activeClass = (!$isDropdown && $currentRoute === ltrim($itemLinkRoute, '/')) ? 'active' : '';
						?>

						<?php if ($isDropdown): ?>
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" id="dropdown-<?= md5($name) ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">
									<?php if ($icon): ?><i class="bi <?= htmlspecialchars($icon) ?>"></i> <?php endif; ?>
									<?= htmlspecialchars($name) ?>
								</a>
								<ul class="dropdown-menu" aria-labelledby="dropdown-<?= md5($name) ?>">
									<?php foreach ($item['submenu'] as $subName => $subItem):
										$subItemLinkRoute = $subItem['link'] ?? '';
										$subIcon = $subItem['icon'] ?? '';
										$subNewWindow = $subItem['new_window'] ?? false;
										$subAuth = $subItem['auth'] ?? null;

										$showSub = (
											($subAuth === true && $isLoggedIn) ||
											($subAuth === false && !$isLoggedIn) ||
											($subAuth === null)
										);

										if (!$showSub) continue;

										// --- CORRECTED SUB-LINK GENERATION ---
                                        $subHref = '#';
                                        if (!empty($subItemLinkRoute)) {
                                            if (strpos($subItemLinkRoute, 'http') === 0 || strpos($subItemLinkRoute, '//') === 0) {
                                                // Handle external links
                                                $subHref = htmlspecialchars($subItemLinkRoute);
                                            } else {
                                                // Assume it's an internal admin route
                                                $subHref = "/admin/?route=" . htmlspecialchars(ltrim($subItemLinkRoute, '/'));
                                            }
                                        }

										// --- CORRECTED SUB-ACTIVE CLASS LOGIC ---
										$activeSub = (isset($currentRoute) && $currentRoute === ltrim($subItemLinkRoute, '/')) ? 'active' : '';
									?>
										<li>
											<a class="dropdown-item <?= $activeSub ?>" href="<?= $subHref ?>" <?= $subNewWindow ? 'target="_blank" rel="noopener noreferrer"' : '' ?>>
												<?php if ($subIcon): ?><i class="bi <?= htmlspecialchars($subIcon) ?>"></i> <?php endif; ?>
												<?= htmlspecialchars($subName) ?>
											</a>
										</li>
									<?php endforeach; ?>
								</ul>
							</li>
						<?php else: ?>
							<li class="nav-item">
								<a class="nav-link <?= $activeClass ?>" href="<?= $href ?>" <?= $newWindow ? 'target="_blank" rel="noopener noreferrer"' : '' ?>>
									<?php if ($icon): ?><i class="bi <?= htmlspecialchars($icon) ?>"></i> <?php endif; ?>
									<?= htmlspecialchars($name) ?>
								</a>
							</li>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php else: // Fallback if $menuItems is not set or not an array ?>
					<li class="nav-item"><span class="nav-link text-warning">Menu Not Configured</span></li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</nav>