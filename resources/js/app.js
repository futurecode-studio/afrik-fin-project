import './bootstrap';

// Styles pour l'éditeur riche (Quill)
import 'quill/dist/quill.snow.css';

// Quill (utilisé dans certaines vues admin)
import Quill from 'quill';
window.Quill = Quill;

// SweetAlert2 — toasts / modals (admin, client, public)
import './sweetalert';

// IMPORTANT: ne pas importer/démarrer Alpine ici.
// Livewire 3 embarque déjà Alpine via @livewireScripts.
// Un second Alpine.start() casse wire:submit (spinner puis silence).
