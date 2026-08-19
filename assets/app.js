import './stimulus_bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

window.toggleModeSelection = function () {
    const wrappers = document.querySelectorAll('.selection-checkbox-wrapper');
    const bouton = document.getElementById('btn-toggle-selection');
    const barreDeSuppression = document.getElementById('form-suppression-groupee');

    wrappers.forEach(function (element) {
        element.classList.toggle('is-collapsed');
    });

    if (bouton.textContent.trim() === 'Sélectionner') {
        bouton.textContent = 'Annuler';
        barreDeSuppression.classList.remove('is-collapsed');
    } else {
        bouton.textContent = 'Sélectionner';
        barreDeSuppression.classList.add('is-collapsed');
        document.querySelectorAll('.custom-checkbox').forEach(function (checkbox) {
            checkbox.checked = false;
        });
    }
};