//Custom Confirm Class
/**
 * @param Object {title,Message,yes,no,yesCallback,noCallback}
 */
export default class ConfirmBox {
    constructor({ ...option }) {
        this.param = option.param || {};
        this.title = option.title || "Confirm";
        this.message = option.message || "Are you sure?";
        this.yes = option.yes || "Yes";
        this.no = option.no || "No";
        this.yesCallback = option.yesCallback || function () { };
        this.noCallback = option.noCallback || function () { };
        this.confirm();
    }

    confirm() {
        this.Ui();
        this.eventHandler();
    }

    Ui() {
        //Create Element
        let modal = document.createElement("div");
        modal.classList.add("confirm-modal");

        let modalBody = document.createElement("div");
        modalBody.classList.add("confirm-modal-body");

        let modalHeader = document.createElement("div");
        modalHeader.classList.add("confirm-modal-header");

        let modalTitle = document.createElement("div");
        modalTitle.classList.add("confirm-modal-title");
        modalTitle.innerHTML = this.title;

        let modalMessage = document.createElement("div");
        modalMessage.classList.add("confirm-modal-message");
        modalMessage.innerHTML = this.message;

        let modalFooter = document.createElement("div");
        modalFooter.classList.add("confirm-modal-footer");

        let modalYes = document.createElement("div");
        modalYes.classList.add("confirm-modal-yes");
        modalYes.innerHTML = this.yes;

        let modalNo = document.createElement("div");
        modalNo.classList.add("confirm-modal-no");
        modalNo.innerHTML = this.no;

        let modalClose = document.createElement("div");
        modalClose.classList.add("confirm-modal-close");
        modalClose.innerHTML = "&times;";
        //Append Element to Modal
        modal.appendChild(modalBody);
        modalBody.appendChild(modalHeader);
        modalHeader.appendChild(modalTitle);
        modalHeader.appendChild(modalClose);

        modalBody.appendChild(modalMessage);
        modalBody.appendChild(modalFooter);
        modalFooter.appendChild(modalYes);
        modalFooter.appendChild(modalNo);
        //Append Modal to Body
        document.body.appendChild(modal);
        //Append Event Listener to Close Button
        this.modalClose = modalClose;
        this.modalYes = modalYes;
        this.modalNo = modalNo;
        this.modal = modal;
    }

    //Event And Callback Handler
    eventHandler() {
        this.modalClose.addEventListener("click", () => {
            this.modal.remove();
        });
        //Append Event Listener to Yes Button
        this.modalYes.addEventListener("click", () => {
            this.yesCallback(this.param);
            this.modal.remove();
        });
        //Append Event Listener to No Button
        this.modalNo.addEventListener("click", () => {
            this.noCallback(this.param);
            this.modal.remove();
        });
    }
}
