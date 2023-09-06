
/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
import Dropdowns from './dropdown';
import popup from './popup';
import MessageView from './MessageView';
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
