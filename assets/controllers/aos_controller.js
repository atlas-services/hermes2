// assets/controllers/aos_controller.js
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        // Attendre que AOS soit disponible
        const checkAOS = setInterval(() => {
            if (typeof AOS !== 'undefined') {
                clearInterval(checkAOS);
                AOS.init({
                    duration: 1000,
                    once: true,
                });
            }
        }, 100);
    }
}
