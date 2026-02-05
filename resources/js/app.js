
// Import Bootstrap from npm package
import * as bootstrap from 'bootstrap';

// Import custom CSS for phone input
import '../css/phone-input.css';

// Expose bootstrap globally for legacy inline code that expects `window.bootstrap`
window.bootstrap = bootstrap;

// The following libraries are heavy and page-specific. We'll lazy-load them on demand.
// Expose small helpers and proxies on window so existing inline code can call them.

function createLazyLoader(importer) {
	let modPromise = null;
	return () => {
		if (!modPromise) modPromise = importer().then(m => m && m.default ? m.default : m);
		return modPromise;
	};
}

// Loaders
const loadFlatpickr = createLazyLoader(() => import('flatpickr'));
const loadChoices = createLazyLoader(() => import('choices.js'));
const loadSwal = createLazyLoader(() => import('sweetalert2'));
const loadQuill = createLazyLoader(() => import('quill'));
const loadSimpleMDE = createLazyLoader(() => import('easymde'));
const loadJSVectorMap = createLazyLoader(() => import('jsvectormap'));

// DataTables v2 can be used without legacy DOM plugins via the DataTable class.
// Expose it globally for inline scripts.
import DataTable from 'datatables.net-bs5';
import 'datatables.net-buttons-bs5';
// helper libs for export functionality
import 'jszip';
import 'pdfmake';

window.DataTable = DataTable;
globalThis.DataTable = DataTable;

// Expose convenient global loader helpers
window.loadFlatpickr = loadFlatpickr;
window.loadChoices = loadChoices;
window.loadSwal = loadSwal;
window.loadQuill = loadQuill;
window.loadSimpleMDE = loadSimpleMDE;
window.loadJSVectorMap = loadJSVectorMap;

// Create a lightweight proxy for SweetAlert2 so calls like Swal.fire(...) still work and
// load the real library on demand. The proxy methods return promises where appropriate.
function createLazyProxy(loader) {
	let modPromise = null;
	const ensure = () => {
		if (!modPromise) modPromise = loader();
		return modPromise;
	};

	return new Proxy({}, {
		get(_target, prop) {
			// Special-case then to avoid Promise-like behaviors when inspected
			if (prop === 'then') return undefined;
			return (...args) => ensure().then(mod => {
				const value = mod[prop];
				if (typeof value === 'function') return value.apply(mod, args);
				// If not a function, just return the value (wrapped in a promise)
				return value;
			});
		}
	});
}

window.Swal = createLazyProxy(loadSwal);
window.flatpickr = (...args) => loadFlatpickr().then(m => (m)(...args));
window.Choices = createLazyProxy(loadChoices);
window.Quill = createLazyProxy(loadQuill);
// Backward compatible global name: most code expects `new SimpleMDE(...)`.
// EasyMDE is a maintained fork with a compatible API.
window.SimpleMDE = createLazyProxy(loadSimpleMDE);
window.JSVectorMap = createLazyProxy(loadJSVectorMap);

import './charts.js';
import './main.js';
import './general.js';
import './sliders.js';
import './phone-input.js';
// Bundle Bootstrap Icons locally to ensure fonts are available
import 'bootstrap-icons/font/bootstrap-icons.css';

// Bundle Font Awesome locally
import '@fortawesome/fontawesome-free/css/all.min.css';

// EasyMDE styles (used by the SimpleMDE-compatible editor)
import 'easymde/dist/easymde.min.css';

