import "./bootstrap";
import Alpine from "@alpinejs/csp";

document.addEventListener("alpine:init", () => {
    Alpine.data("darkModeHandler", () => ({
        darkMode: false,

        init() {
            // Cek localStorage atau preferensi sistem
            const savedTheme = localStorage.getItem("theme");
            const systemDark = window.matchMedia(
                "(prefers-color-scheme: dark)",
            ).matches;

            this.darkMode =
                savedTheme === "dark" || (!savedTheme && systemDark);

            // Terapkan class saat pertama kali load
            this.applyTheme();

            // Pantau perubahan variabel darkMode
            this.$watch("darkMode", () => {
                this.applyTheme();
            });
        },

        applyTheme() {
            if (this.darkMode) {
                document.documentElement.classList.add("dark");
                localStorage.setItem("theme", "dark");
            } else {
                document.documentElement.classList.remove("dark");
                localStorage.setItem("theme", "light");
            }
        },

        toggle() {
            this.darkMode = !this.darkMode;
        },
    }));
});

window.Alpine = Alpine;
Alpine.start();
