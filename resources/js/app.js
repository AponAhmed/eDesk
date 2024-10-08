/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from "axios";
import Dropdowns from "./dropdown";
import popup from "./popup";
import MessageView from "./MessageView";
import Tab from "./Tab";
import ConfirmBox from "./Confirm";
import Gemini from "./AI/Gemini";
import Notification from "@aponahmed/notify";
//import { tooltip } from "./Tooltip";
import { tooltip } from "@aponahmed/tooltip";


window.ConfirmBox = ConfirmBox;
window.Gemini = Gemini;
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

tooltip(".tooltip", { color: "#fff" });

window.notify = (message, type) => {
    new Notification({
        message, type,
    })
}

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
let menuTaggler = document.querySelector("#menuTaggler");
let sidebar = document.querySelector("#sidebar");
// Check local storage for the sidebar status and set it if available
const sidebarStatus = localStorage.getItem("sidebarStatus");
if (sidebarStatus === "open") {
    if (window.innerWidth > 768) {
        sidebar.classList.add("open");
    }
}

function handleSwipe() {
    let touchstartX = 0;
    let touchendX = 0;
    let isSwiping = false;
    let startZone = 0; // 0: none, 1: open zone, 2: close zone

    function checkDirection() {
        console.log(`touchstartX: ${touchstartX}, touchendX: ${touchendX}`);
        if (startZone === 1 && touchendX - touchstartX > 50) {
            // Swipe right to open
            //console.log('Swiping right to open');
            sidebar.classList.add("open");
            localStorage.setItem("sidebarStatus", "open");
        } else if (startZone === 2 && touchstartX - touchendX > 50) {
            // Swipe left to close
            //console.log('Swiping left to close');
            sidebar.classList.remove("open");
            localStorage.setItem("sidebarStatus", "closed");
        }
    }

    document.addEventListener("touchstart", (e) => {
        const touch = e.touches[0];
        touchstartX = touch.clientX;
        //console.log(`Touch start at: ${touchstartX}`);
        if (touchstartX <= 50) {
            isSwiping = true;
            startZone = 1; // open zone
            //console.log('Entering open zone');
        } else if (touchstartX <= 170) {
            isSwiping = true;
            startZone = 2; // close zone
            //console.log('Entering close zone');
        }
    });

    document.addEventListener("touchmove", (e) => {
        if (isSwiping) {
            const touch = e.touches[0];
            touchendX = touch.clientX;
            //console.log(`Touch move at: ${touchendX}`);
        }
    });

    document.addEventListener("touchend", (e) => {
        if (isSwiping) {
            //console.log(`Touch end at: ${touchendX}`);
            checkDirection();
            isSwiping = false;
            startZone = 0;
            console.log('Swipe ended');
        }
    });
}

// Call the function to enable swipe detection
if (window.innerWidth < 768) {
    handleSwipe();
}

menuTaggler.addEventListener("click", (e) => {
    // Toggle the 'open' class on the sidebar
    sidebar.classList.toggle("open");
    // Store the current sidebar status in local storage
    if (sidebar.classList.contains("open")) {
        localStorage.setItem("sidebarStatus", "open");
    } else {
        localStorage.setItem("sidebarStatus", "closed");
    }
});

//Initialize the popup window
new popup();
const messageViewer = new MessageView();

//Tab
let tabContainers = document.querySelectorAll(".tab-wrap");
tabContainers.forEach((tab) => {
    new Tab(tab);
});

//Settings Save
let settingsForm = document.querySelector("#settingsForm");
if (settingsForm) {
    settingsForm.addEventListener("submit", (e) => {
        e.preventDefault();
        let buttons = settingsForm.querySelectorAll(".update-btn");
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
        axios.post("settings/update", formData).then((response) => {
            console.log(response);
            buttons.forEach((e) => {
                e.innerHTML = "Settings Updated";
            });
            setTimeout((e) => {
                buttons.forEach((e) => {
                    e.innerHTML = "Update Options";
                });
            }, 2000);
        });
    });
}

//Notification handlers
setTimeout(function () {
    var successMessage = document.getElementById("success-message");
    if (successMessage) {
        successMessage.style.display = "none";
    }

    var errorMessage = document.getElementById("error-message");
    if (errorMessage) {
        errorMessage.style.display = "none";
    }
}, 5000);

//Collapse

window.more = function (_this) {
    let wrap = _this.parentNode;
    const collapse = wrap.querySelector(".colapse-able");
    collapse.classList.toggle("block");
};

//Auth Logout
let AuthLogoutBtn = document.getElementById("logoutGmail");
if (AuthLogoutBtn) {
    AuthLogoutBtn.addEventListener("click", (e) => {
        let target = e.target;
        target.innerHTML = '<span class="working"></span>';
        axios.get("settings/auth-logout").then((response) => {
            if (!response.data.error) {
                window.location.reload();
            }
        });
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
let multiActions = document.querySelectorAll(".multiple-action-trigger");
if (multiActions) {
    multiActions.forEach((action) => {
        action.addEventListener("click", (e) => {
            e.preventDefault();
            let action = e.target.getAttribute("data-action");
            let actionLabel = e.target.getAttribute("data-action-title");

            //Checked Ids
            // Get all checkboxes with class 'data-check'
            const checkboxes = document.querySelectorAll(".data-check");
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
                    axios
                        .post("/" + SUBAPP + "/multiple-action", {
                            action: action,
                            ids: checkedValues,
                        })
                        .then((response) => {
                            if (!response.data.error) {
                                // Loop through the checkboxes and uncheck them
                                checkboxes.forEach((checkbox) => {
                                    checkbox.checked = false;
                                });
                                window.location.reload();
                            } else {
                                alert("action Failed");
                            }
                        });
                },
            });
        });
    });
}

// JavaScript using Axios
document.addEventListener("DOMContentLoaded", function () {
    let deleteall = document.getElementById("delete-all");
    if (deleteall) {
        deleteall.addEventListener("click", () => {
            //console.log();
            new ConfirmBox({
                title: `Action Confirmation`,
                message: `Sure delete all items in this box`,
                yesCallback: () => {
                    axios
                        .post("/" + SUBAPP + "/delete-all", {
                            box: deleteall.getAttribute("data-box"),
                        })
                        .then((response) => {
                            if (!response.data == 0) {
                                //console.log(response.data);
                                window.location.reload();
                            } else {
                                alert("action Failed");
                            }
                        });
                },
            });
        });
    }
    setInterval(() => {
        updateCountBadge();
    }, 10000);

    updateCountBadge();

});

window.messageView = (messageId, module) => {
    messageViewer.exViewDetails(messageId, module);
    return messageId;
}

function updateCountBadge() {
    axios
        .get("/get-count")
        .then((axiosResponse) => {
            let data = axiosResponse.data;

            // Sum counts of all box types for 'edesk'
            let edeskCount = Object.values(data.edesk).reduce((acc, val) => acc + val, 0);
            // Sum counts of all box types for 'gdesk'
            let gdeskCount = Object.values(data.gdesk).reduce((acc, val) => acc + val, 0);
            // Update badge for 'edesk'
            if (edeskCount > 0) {
                updateBadge("edeskmenu", edeskCount);
            }
            // Update badge for 'gdesk'
            if (gdeskCount > 0) {
                updateBadge("gdeskmenu", gdeskCount);
            }

            // If SUBAPP is 'edesk' or 'gdesk', update badges for specific sub-application
            if (SUBAPP == "edesk" || SUBAPP == "gdesk") {
                let subData = data[SUBAPP];
                // Iterate over the keys in the response data
                for (var key in subData) {
                    if (subData.hasOwnProperty(key)) {
                        if (subData[key] > 0) {
                            updateBadge("box-" + key, subData[key], true);
                        }
                    }
                }
            }

            // Check if AndroidInterface is present
            if (typeof AndroidInterface !== 'undefined') {
                // Post notifications for new 'edesk' messages
                if (data.new && data.new.edesk) {
                    data.new.edesk.forEach((message) => {
                        AndroidInterface.postNotification(message.name, message.id, message.subject, 'edesk');
                    });
                }

                // Post notifications for new 'gdesk' messages
                if (data.new && data.new.gdesk) {
                    data.new.gdesk.forEach((message) => {
                        AndroidInterface.postNotification(message.name, message.id, message.subject, 'gdesk');
                    });
                }
            }
        })
        .catch((error) => {
            console.error(error);
        });
}


function updateBadge(itemId, count, id = false) {
    if (id) {
        var menuItems = document.querySelectorAll(`#${itemId}`);
    } else {
        var menuItems = document.querySelectorAll(`.${itemId}`);
    }
    // Create a badge element
    menuItems.forEach(menuItem => {
        var existingBadge = menuItem.querySelector(".badge");
        if (existingBadge) {
            existingBadge.innerText = count;
        } else {
            var badge = document.createElement("span");
            // Add Tailwind classes
            badge.classList.add(
                "badge",
                "bg-red-500",
                "text-white",
                "rounded-full",
                "px-1",
                "ml-2",
                "text-xs",
                "absolute",
                "right-1",
                "top-1"
            );
            badge.innerText = count;
            // Append the badge to the menu item
            menuItem.appendChild(badge);
        }
    });
}

// Check if the variable is a DOM object
function isDOMObject(variable) {
    return variable instanceof Element || variable instanceof HTMLDocument;
}


function markdownToPlainText(markdown) {
    // Remove headings
    markdown = markdown.replace(/^#+\s+(.*)/gm, '$1\n');

    // Remove bold and italic formatting
    markdown = markdown.replace(/\*\*(.*?)\*\*/g, '$1');
    markdown = markdown.replace(/\*(.*?)\*/g, '$1');

    // Convert unordered lists to numbered lists
    let counter = 1;
    markdown = markdown.replace(/^[\-\+\*]\s+(.*)/gm, (match, p1) => `${counter++}. ${p1}\n`);

    // Remove blockquotes
    markdown = markdown.replace(/^\>\s+(.*)/gm, '$1\n');

    // Remove inline code
    markdown = markdown.replace(/`([^`]+)`/g, '$1');

    // Remove images
    markdown = markdown.replace(/\!\[(.*?)\]\((.*?)\)/g, '$1');

    // Split markdown into lines
    let lines = markdown.split('\n');

    // Filter to remove line breaks after numbered list items
    lines = lines.filter((line, index) => {
        if (index > 0 && /^\d+\./.test(lines[index - 1])) {
            return false;
        }
        return true;
    });

    // Add line breaks before first item and after last item
    lines.unshift('');
    lines.push('');

    // Join lines back together
    markdown = lines.join('\n');

    return markdown.trim();
}


//Ai Reply Generate
window.generateReply = function (provider, btn, query, hints, outputTextArea, temperature, language = "English", tone) {
    let exhtml = btn.innerHTML;
    btn.innerHTML = "Generating... ";

    let AiName = provider || AiSettings.ai;

    if (isDOMObject(query)) {
        query = query.value;
    }
    if (isDOMObject(hints)) {
        hints = hints.value;
        hints = hints.trim().replace(/\s+/g, ' ');
    }
    var aboutCompany = AiSettings.about.replace(/\\n/g, '\n');
    aboutCompany = `\n **Our capabilities and information. ** \n\n ${aboutCompany} `;

    if (hints == '') {
        hints = aboutCompany;
    }

    let finalQuery = `${query} \n\n *follow below Hints:*\n No needed Signature part for email, like 'Best regards', '[Your Company Name]'.. . \n ${hints}`;


    // console.log(finalQuery);
    // return;

    let ai;
    switch (AiName) {
        case 'gemini':
            ai = new Gemini({ key: AiSettings.key, modelName: AiSettings.model, temperature: temperature });
            ai.execute(finalQuery, []).then(response => {
                const plainTextContent = markdownToPlainText(response);
                outputTextArea.value = removeEmailSignature(plainTextContent);
                //console.log(response);
                btn.innerHTML = exhtml.replace('Generate', 'Re-generate');
            });
            break;
        default:
            try {
                // Define the data to be sent in the request body
                const data = {
                    prompt: finalQuery,
                    //url: 'ai-content-generator',
                    language: language
                };
                if (tone) {
                    data.tone = tone;
                }

                // Make POST request using Axios
                axios.post('/ai', data, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    }
                }).then(response => {
                    let responseData = response.data;
                    if (responseData.error == true) {
                        alert(responseData.message);
                    } else {
                        outputTextArea.value = removeEmailSignature(responseData.body);
                        //console.log(response);
                        btn.innerHTML = exhtml.replace('Generate', 'Re-generate');
                    }
                })
            } catch (error) {
                // Handle errors
                console.error('Error:', error.message);
            }
            break;
    }
}

const signaturePhrases = [
    'Best regards,',
    'Thank you.',
    'Thanks,',
    'Sincerely,',
    'Yours faithfully,',
    'Warm regards,',
    'With gratitude,',
    'Kind regards,',
    'Yours truly,',
    'Cheers,',
    'Take care,',
    'Looking forward to hearing from you,',
    'Until next time,',
    'Respectfully,',
    'Best,',
    'Regards,'
];


function removeEmailSignature(emailContent) {
    emailContent = removeSubjectLine(emailContent);

    let signPrefix = AiSettings.signPrefix;
    let signPrefixLines = signPrefix.split('\n');
    // Merge arrays and keep unique elements
    const allPrefix = Array.from(new Set(signaturePhrases.concat(signPrefixLines)));
    // Split the email content by lines
    let lines = emailContent.split('\n');
    let signatureIndex = -1;

    // Loop through the lines to find the signature
    for (let i = lines.length - 1; i >= 0; i--) {
        let line = lines[i].trim();

        // Check if the line matches any of the signature phrases
        if (allPrefix.some(phrase => line.startsWith(phrase))) {
            signatureIndex = i;
            break; // Stop further processing as signature is found
        }
    }

    // Remove the signature and everything after it if found
    if (signatureIndex !== -1) {
        lines = lines.slice(0, signatureIndex);
    }

    // Join the remaining lines back into a string
    let cleanedContent = lines.join('\n').trim();



    return cleanedContent;
}

function removeSubjectLine(email) {
    // Use a regular expression to match the "Subject:" line
    let subjectRegex = /^Subject:.*\n/gm;
    // Replace the matched "Subject:" line with an empty string
    let modifiedEmail = email.replace(subjectRegex, '');
    return modifiedEmail;
}

document.addEventListener('DOMContentLoaded', function () {
    //Ai Settings 
    let aiPorivider = document.getElementById("aiProvider");
    if (aiPorivider) {
        aiSettingsFieldManage(aiPorivider.value);
        aiPorivider.addEventListener("change", function (e) {
            aiSettingsFieldManage(aiPorivider.value);
        });
    }
});

function aiSettingsFieldManage(prov) {
    // Get all elements with class "freebox-settings"
    var freeboxSettings = document.querySelectorAll('.freebox-settings');

    // Get all elements with class "gemini-settings"
    var geminiSettings = document.querySelectorAll('.gemini-settings');

    switch (prov) {
        case "freebox":
            // Loop through all elements with class "gemini-settings" and add class "hidden"
            geminiSettings.forEach(function (element) {
                element.classList.add('hidden');
            });

            // Loop through all elements with class "freebox-settings" and remove class "hidden"
            freeboxSettings.forEach(function (element) {
                element.classList.remove('hidden');
            });
            break;
        case "gemini":
            // Loop through all elements with class "freebox-settings" and add class "hidden"
            freeboxSettings.forEach(function (element) {
                element.classList.add('hidden');
            });

            // Loop through all elements with class "gemini-settings" and remove class "hidden"
            geminiSettings.forEach(function (element) {
                element.classList.remove('hidden');
            });
            break;
    }
}




const toggleClassName = "theme-toggle--toggled";
const toggleButton = document.querySelector("button.theme-toggle");

// Function to apply the theme based on a boolean value or the saved preference
window.themeToggle = function (dark) {
    let doc = document.documentElement;
    if (dark === undefined) {
        doc.classList.toggle('dark');
    } else {
        if (dark) {
            doc.classList.add("dark");
            toggleButton.classList.remove(toggleClassName);
        } else {
            toggleButton.classList.add(toggleClassName);
            if (doc.classList.contains("dark"))
                doc.classList.remove("dark");
        }
    }
    // Save the current theme mode to localStorage
    localStorage.setItem('theme', doc.classList.contains('dark') ? 'dark' : 'light');
}

// Event listener to toggle the theme and update localStorage
toggleButton.addEventListener("click", (e) => {
    e.currentTarget.classList.toggle(toggleClassName);
    window.themeToggle(); // Toggle the theme
});

// Apply the saved theme on page load
document.addEventListener("DOMContentLoaded", () => {
    if (typeof AndroidInterface === 'undefined') {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            window.themeToggle(true);
        } else {
            window.themeToggle(false);
        }
    }
});
