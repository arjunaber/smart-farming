import "./bootstrap";
import Alpine from "@alpinejs/csp";
// 1. TAMBAHKAN IMPORT INI DI ATAS ALPINE
import "@dotlottie/player-component";

document.addEventListener("alpine:init", () => {
    // Kode darkModeHandler kamu tetap di sini, jangan diubah
    Alpine.data("darkModeHandler", () => ({
        darkMode: false,
        init() {
            const savedTheme = localStorage.getItem("theme");
            const systemDark = window.matchMedia(
                "(prefers-color-scheme: dark)",
            ).matches;
            this.darkMode =
                savedTheme === "dark" || (!savedTheme && systemDark);
            this.applyTheme();
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

    // 2. TAMBAHKAN STATE LOADING HANDLER VIA ALPINE
    Alpine.data("loginHandler", () => ({
        isLoading: false,
        submitForm(e) {
            // Cek jika validasi HTML form terpenuhi (email & password terisi)
            if (e.target.checkValidity()) {
                this.isLoading = true;
            }
        },
    }));
});

window.Alpine = Alpine;
Alpine.start();
