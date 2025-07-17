<?php $currentRoute = $_GET['route'] ?? 'home'; ?>

<nav class="navbar navbar-expand-lg" data-bs-theme="dark">
	<div class="container-fluid">
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarColor01">
			<ul class="navbar-nav me-auto">
				<?php foreach ($menuItems as $name => $item): ?>
					<?php
						$isDropdown = isset($item['submenu']) && is_array($item['submenu']);
						$authOnly = $item['auth'] ?? null;

						$shouldShow = (
							($authOnly === true  && isset($_SESSION['user_id'])) ||
							($authOnly === false && !isset($_SESSION['user_id'])) ||
							($authOnly === null)
						);

						if (!$shouldShow) continue;

						$link = $isDropdown ? '#' : ($item['link'] ?? '#');
						$icon = $item['icon'] ?? '';
						$newWindow = $item['new_window'] ?? false;
						$activeClass = (!$isDropdown && $currentRoute === basename($link)) ? 'active' : '';
					?>

					<?php if ($isDropdown): ?>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="dropdown-<?= md5($name) ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								<?php if ($icon): ?><i class="bi <?= htmlspecialchars($icon) ?>"></i> <?php endif; ?>
								<?= htmlspecialchars($name) ?>
							</a>
							<ul class="dropdown-menu" aria-labelledby="dropdown-<?= md5($name) ?>">
								<?php foreach ($item['submenu'] as $subName => $subItem): 
									$subLink = $subItem['link'] ?? '#';
									$subIcon = $subItem['icon'] ?? '';
									$subNewWindow = $subItem['new_window'] ?? false;
									$subAuth = $subItem['auth'] ?? null;

									$showSub = (
										($subAuth === true  && isset($_SESSION['user_id'])) ||
										($subAuth === false && !isset($_SESSION['user_id'])) ||
										($subAuth === null)
									);

									if (!$showSub) continue;

									$activeSub = ($currentRoute === basename($subLink)) ? 'active' : '';
								?>
									<li>
										<a class="dropdown-item <?= $activeSub ?>" href="<?= htmlspecialchars($subLink) ?>" <?= $subNewWindow ? 'target="_blank" rel="noopener noreferrer"' : '' ?>>
											<?php if ($subIcon): ?><i class="bi <?= htmlspecialchars($subIcon) ?>"></i> <?php endif; ?>
											<?= htmlspecialchars($subName) ?>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</li>
					<?php else: ?>
						<li class="nav-item">
							<a class="nav-link <?= $activeClass ?>" href="<?= htmlspecialchars($link) ?>" <?= $newWindow ? 'target="_blank" rel="noopener noreferrer"' : '' ?>>
								<?php if ($icon): ?><i class="bi <?= htmlspecialchars($icon) ?>"></i> <?php endif; ?>
								<?= htmlspecialchars($name) ?>
							</a>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</nav>
