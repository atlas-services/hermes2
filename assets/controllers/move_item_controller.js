import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['item'];

    moveUp(event) {
        const item = event.currentTarget.closest('.list-item');
        const previousItem = item.previousElementSibling;

        if (previousItem) {
            item.parentNode.insertBefore(item, previousItem);
             this.updatePositions();
        }
    }

    moveDown(event) {
        const item = event.currentTarget.closest('.list-item');
        const nextItem = item.nextElementSibling;

        if (nextItem) {
            item.parentNode.insertBefore(nextItem, item);
            this.updatePositions();
        }
    }

    async updatePositions() {
        const positions = [];
        const type = document.getElementsByTagName("tbody")[0].dataset.type; // Assurez-vous que chaque item a un data-item-id
        const locale = document.getElementsByTagName("tbody")[0].dataset.locale;
        this.itemTargets.forEach((item, index) => {
            const id = item.dataset.itemId; // Assurez-vous que chaque item a un data-item-id
            positions.push({ id: id, position: index + 1 });
        });

        await fetch('/' + locale + '/admin/' + type + '/update-positions', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ positions: positions, type: type }),
        });
    }

}
