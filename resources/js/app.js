import './bootstrap';

// Styles pour l'éditeur riche (Quill)
import 'quill/dist/quill.snow.css';

// Quill sera utilisé dans certaines vues (initialisé via Alpine)
import Quill from 'quill';
window.Quill = Quill;

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
