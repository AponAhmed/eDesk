export default class Tab {
    constructor(dom) {
        this.dom = dom;
        this.init();
        this.target = false;//Target ID
    }
    init() {
        this.lis = this.dom.querySelectorAll('li');
        this.pans = this.dom.querySelectorAll('.tab-pan');

        this.lis.forEach((node) => {
            node.addEventListener('click', () => {
                this.removeActive();
                this.target = node.getAttribute('data-id');
                this.target = this.dom.querySelector("#" + this.target);
                node.classList.add('active');
                this.target.classList.add('active');
            });
        });

    }
    removeActive() {
        this.lis.forEach((node) => {
            node.classList.remove('active');
        });
        this.pans.forEach((node) => {
            node.classList.remove('active');
        });
    }
}

