import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['item'];

    switch(event) {
        const item = event.currentTarget.closest('.list-items');
        this.switchActive(item);
    }

    async switchActive(item) {
        const locale = document.getElementsByTagName("tbody")[0].dataset.locale;
        const type = document.getElementsByTagName("tbody")[0].dataset.type; // Assurez-vous que chaque item a un data-item-id
        const id = item.dataset.itemId; // Assurez-vous que chaque item a un data-item-id


        await fetch('/' + locale + '/admin' +  '/switch-active', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: id, type: type }),
        });
    }

}
