
/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
import Dropdowns from './dropdown';
import popup from './popup';
import MessageView from './MessageView';
import Tab from './Tab';
import ConfirmBox from './Confirm';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

//Dropdowns
// Create an array of dropdown elements
const dropdownElements = document.querySelectorAll(".dropdown");
// Create an array to store instances of Dropdowns
const dropdownInstances = [];
// Loop through each dropdown element and create an instance
dropdownElements.forEach((element) => {
    const myDropdown = new Dropdowns({ element: element });
    dropdownInstances.push(myDropdown);
});

//Menu Toggle
let menuTaggler = document.querySelector('#menuTaggler');
let sidebar = document.querySelector("#sidebar");
// Check local storage for the sidebar status and set it if available
const sidebarStatus = localStorage.getItem('sidebarStatus');
if (sidebarStatus === 'open') {
    sidebar.classList.add('open');
}
menuTaggler.addEventListener('click', (e) => {
    // Toggle the 'open' class on the sidebar
    sidebar.classList.toggle('open');
    // Store the current sidebar status in local storage
    if (sidebar.classList.contains('open')) {
        localStorage.setItem('sidebarStatus', 'open');
    } else {
        localStorage.setItem('sidebarStatus', 'closed');
    }
});
//Initialize the popup window
new popup();
new MessageView();

//Tab
let tabContainers = document.querySelectorAll(".tab-wrap");
tabContainers.forEach(tab => {
    new Tab(tab);
});

//Settings Save
let settingsForm = document.querySelector("#settingsForm");
if (settingsForm) {

    settingsForm.addEventListener('submit', (e) => {
        e.preventDefault();
        let buttons = settingsForm.querySelectorAll('.update-btn');
        buttons.forEach((e) => {
            e.innerHTML = '<span class="working"></span>';
        });
        const formData = new FormData(settingsForm);

        // Convert FormData to a JavaScript object
        const formObject = {};
        formData.forEach((value, key) => {
            formObject[key] = value;
        });
        console.log(formData);
        axios.post('settings/update', formData)
            .then((response) => {
                console.log(response);
                buttons.forEach((e) => {
                    e.innerHTML = 'Settings Updated';
                });
                setTimeout(e => {
                    buttons.forEach((e) => {
                        e.innerHTML = 'Update Options';
                    });
                }, 2000)
            });
    });

}

//Notification handlers
setTimeout(function () {
    var successMessage = document.getElementById('success-message');
    if (successMessage) {
        successMessage.style.display = 'none';
    }

    var errorMessage = document.getElementById('error-message');
    if (errorMessage) {
        errorMessage.style.display = 'none';
    }
}, 5000);

//Collapse

window.more = function (_this) {
    let wrap = _this.parentNode;
    const collapse = wrap.querySelector(".colapse-able");
    collapse.classList.toggle("block");
}

//Auth Logout
let AuthLogoutBtn = document.getElementById("logoutGmail");
if (AuthLogoutBtn) {
    AuthLogoutBtn.addEventListener('click', e => {
        let target = e.target;
        target.innerHTML = '<span class="working"></span>';
        axios.get('/auth-logout').then((response) => {
            if (!response.data.error) {
                window.location.reload();
            }
        })
    });
}


//Select ALl
let checkAll = document.querySelector(".checkAll");
if (checkAll) {
    // Add an event listener to the "checkAll" checkbox
    checkAll.addEventListener("change", function () {
        // Get all checkboxes with the class "data-check"
        let dataCheckboxes = document.querySelectorAll(".data-check");
        // Loop through each "data-check" checkbox
        dataCheckboxes.forEach(function (checkbox) {
            // Set the checked state of the "data-check" checkboxes
            checkbox.checked = checkAll.checked;
        });
    });
}

//Multiple actions
let multiActions = document.querySelectorAll('.multiple-action-trigger');
if (multiActions) {
    multiActions.forEach(action => {
        action.addEventListener('click', (e) => {
            e.preventDefault();
            let action = e.target.getAttribute('data-action');
            let actionLabel = e.target.getAttribute('data-action-title');

            //Checked Ids
            // Get all checkboxes with class 'data-check'
            const checkboxes = document.querySelectorAll('.data-check');
            // Initialize an empty array to store checked checkbox values
            const checkedValues = [];
            // Loop through the checkboxes and check if each one is checked
            checkboxes.forEach((checkbox) => {
                if (checkbox.checked) {
                    checkedValues.push(checkbox.value);
                }
            });

            //console.log();
            new ConfirmBox({
                title: `Action Confirmation`,
                message: `Sure to ${actionLabel} on ${checkedValues.length} items`,
                yesCallback: () => {
                    axios.post('/multiple-action', {
                        action: action,
                        ids: checkedValues
                    }).then((response) => {
                        if (!response.data.error) {
                            // Loop through the checkboxes and uncheck them
                            checkboxes.forEach((checkbox) => {
                                checkbox.checked = false;
                            });
                            window.location.reload();
                        } else {
                            alert('action Failed');
                        }
                    });
                }
            });
        });
    });
}
