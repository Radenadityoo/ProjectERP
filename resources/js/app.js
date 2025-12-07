import './bootstrap';

(async function () {
	// Try to load a potential CSP-friendly Alpine build first, then fall back.
	let AlpineModule = null;
	try {
		AlpineModule = await import('alpinejs/dist/module.esm.js');
	} catch (e) {
		try {
			AlpineModule = await import('alpinejs');
		} catch (err) {
			console.error('Failed to load Alpine.js:', err);
		}
	}

	const Alpine = (AlpineModule && (AlpineModule.default || AlpineModule)) || null;
	if (Alpine) {
		window.Alpine = Alpine;
		Alpine.start();
	}
})();
