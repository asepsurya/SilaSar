/*
-------------------------------------------------------------------------
* Template Name    : Snow - Tailwind CSS Admin & Dashboard Template     * 
* Author           : Webonzer                                           *
* Version          : 1.0.0                                              *
* Created          : March 2023                                         *
* File Description : Main JS file of the template                       *
*------------------------------------------------------------------------
*/

document.addEventListener("alpine:init", () => {
    const closeSidebarOnDesktop = () => window.innerWidth >= 1024;
    const closeRightSidebarOnWide = () => window.innerWidth >= 1536;

    Alpine.data("collapse", () => ({
        collapse: false,

        collapseSidebar() {
            this.collapse = !this.collapse;
        },
    }));
    Alpine.data("dropdown", (initialOpenState = false) => ({
        open: initialOpenState,

        toggle() {
            this.open = !this.open;
        },
    }));
    Alpine.data("modals", (initialOpenState = false) => ({
        open: initialOpenState,

        toggle() {
            this.open = !this.open;
        },
    }));

    // main - custom functions
    Alpine.data("main", (value) => ({}));

    Alpine.store("app", {
        // Light and dark Mode
        mode: Alpine.$persist("light"),
        menu: "",
        layout: "",
        navbar: "",
        toggleMode(val) {
            if (!val) {
                val = this.mode || "light"; // light And Dark
            }

            this.mode = val;
        },

        // sidebar
        sidebar: false,
        toggleSidebar() {
            this.sidebar = !this.sidebar;
        },

        //right sidebar
        rightsidebar: false,
        rightSidebar() {
            this.rightsidebar = !this.rightsidebar;
        },

        syncResponsiveState() {
            if (closeSidebarOnDesktop()) {
                this.sidebar = false;
            }

            if (closeRightSidebarOnWide()) {
                this.rightsidebar = false;
            }
        },
    });

    Alpine.store("app").syncResponsiveState();
    window.addEventListener("resize", () => Alpine.store("app").syncResponsiveState());
});
