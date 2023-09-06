export default class Dropdowns {
    constructor(options = {}) {
        // Default options
        const defaultOptions = {
            element: null,
        };

        // Merge default options with provided options
        this.options = { ...defaultOptions, ...options };

        // Find the button and dropdown content elements
        this.button = this.options.element.querySelector(".dropdown-toggler");
        this.content = this.options.element.querySelector(".dropdown-content");

        // Add a click event listener to toggle the "open" class
        this.button.addEventListener("click", () => {
            this.content.classList.toggle("open");
        });
    }
}
