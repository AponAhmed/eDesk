import axios from 'axios'; // Import Axios

// POPUP
export default class Popup {
    constructor(ApiResponse, r) {
        this.selectorClass = "popup";
        this.appendSelector = "body";
        this.dom = null;
        this.domExistingHtml = null;
        this.scriptTags = []; // Store added script tags

        this.init();
    }

    init() {
        const popUpTog = document.querySelectorAll("." + this.selectorClass);
        const _this = this;

        popUpTog.forEach(function (el) {
            el.addEventListener("click", function (e) {
                _this.dom = this;
                _this.domExistingHtml = _this.dom.innerHTML;
                _this.dom.innerHTML = "<span class='working'></span>";
                e.preventDefault();
                const url = el.getAttribute("href");
                const w = el.getAttribute("data-w");
                let cls = el.getAttribute("data-class");
                cls = cls || "";
                let ccs = "";
                if (w) {
                    ccs = "width:" + w + "px";
                }

                // Use Axios for the GET request
                axios.get(url + "?ajx")
                    .then(function (response) {
                        return response.data; // Get the response data
                    })
                    .then(function (data) {
                        _this.dom.innerHTML = _this.domExistingHtml;
                        const uID = Date.now();
                        const popupHtml =
                            `<div class='popup-wrap pop-${uID}'><div class='popup-body ${cls}' style='${ccs}'>
                <span class='closePopup'></span><div class='popup-inner'>${data}</div></div></div>`;

                        document.querySelector(_this.appendSelector).insertAdjacentHTML('beforeend', popupHtml);
                        const scriptTags = document.querySelectorAll(`${_this.appendSelector} script`);
                        // Store script tags
                       
                        // Execute each script
                        scriptTags.forEach(script => {
                            const newScript = document.createElement('script');
                            newScript.textContent = script.textContent;
                            script.parentNode.replaceChild(newScript, script);
                            _this.scriptTags.push(newScript);
                        });

                        const popUpForm = document.querySelector(".pop-" + uID + " form.ajx");
                        if (popUpForm) {
                            popUpForm.addEventListener("submit", function (e) {
                                e.preventDefault();
                                const btn = e.target.querySelector('button[type="submit"]');
                                const exHtml = btn.innerHTML;
                                btn.innerHTML = "<span class='working'></span>";

                                // Form Submit by Axios
                                const submitRoute = popUpForm.getAttribute("action");
                                axios.post(submitRoute, new FormData(popUpForm))
                                    .then(function (res) {
                                        //LoadData(); // Define the LoadData() function
                                        window.location.reload();
                                        document.querySelector(".pop-" + uID).remove();
                                        btn.innerHTML = exHtml;
                                    })
                                    .catch(function (error) {
                                        //console.log(error);
                                        //console.log(error.response.data.message);
                                        const errorMessage = document.createElement('span');
                                        errorMessage.classList.add('text-red-400');
                                        errorMessage.textContent = error.response.data.message;
                                        btn.parentNode.appendChild(errorMessage);
                                        // ntf(error, "error"); //error.response.headers);
                                        btn.innerHTML = exHtml;
                                    });
                            });
                        }

                        const closePopupBtn = document.querySelector(".pop-" + uID + " .closePopup");
                        closePopupBtn.addEventListener("click", function () {
                            const popupWrap = this.closest(".popup-wrap");
                            if (popupWrap) {
                                // Remove added script tags
                                _this.scriptTags.forEach(script => {
                                    script.remove();
                                });
                                _this.scriptTags = []; // Reset script tags
                                popupWrap.remove();

                            }
                        });
                    })
                    .catch(function (error) {
                        console.log(error);

                        // ntf(error, "error"); //error.response.headers);
                    });
            });
        });
    }
}
