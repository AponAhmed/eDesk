const activeClass = 'bg-slate-100';
import axios from "axios";

export default class MessageView {
    constructor() {
        this.data = null;
        this.items = document.querySelectorAll('.mail-list-item')
        this.main = document.querySelector("#main");
        this.details = document.querySelector("#messageDetails");
        this.eventsSet();
    }

    eventsSet() {
        this.items.forEach(item => {
            item.addEventListener('click', e => {
                this.detailsTrigger(item);
            })
        });
    }

    removeCurrent() {
        this.items.forEach(item => {
            if (item.classList.contains(activeClass)) {
                item.classList.remove(activeClass);
            }
        });
    }

    detailsTrigger(item) {
        this.removeCurrent();
        item.classList.add(activeClass);
        this.main.classList.add('details-open');
        let id = item.getAttribute('data-id');
        this.getdata(id)
            .then(() => {
                this.randerView();
            });

    }
    randerView() {

        // <b>Name:</b> ${this.data.name}<br>
        // <b>Email:</b> ${this.data.email}<br>
        // <b>WhatsApp:</b> ${this.data.whatsapp}<br><br>
        this.details.innerHTML = `
        ${this.data.message}
      `;

    }

    async getdata(id) {
        await axios.post('/message', { id: id })
            .then(response => {
                this.data = response.data;
            });
    }
}
