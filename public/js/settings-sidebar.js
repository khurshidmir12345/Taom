document.addEventListener("DOMContentLoaded", () => {
    // Get elements
    const settingsButton = document.querySelector(".bottom-nav-item:nth-child(3)")
    const settingsSidebar = document.getElementById("settings-sidebar")
    const settingsOverlay = document.getElementById("settings-overlay")
    const closeSettings = document.getElementById("close-settings")

    // Open settings sidebar when settings button is clicked
    if (settingsButton) {
        settingsButton.addEventListener("click", (e) => {
            e.preventDefault()
            settingsSidebar.classList.add("open")
            settingsOverlay.classList.add("open")
            document.body.style.overflow = "hidden" // Prevent scrolling
        })
    }

    // Close settings sidebar when close button is clicked
    if (closeSettings) {
        closeSettings.addEventListener("click", () => {
            settingsSidebar.classList.remove("open")
            settingsOverlay.classList.remove("open")
            document.body.style.overflow = "" // Enable scrolling
        })
    }

    // Close settings sidebar when overlay is clicked
    if (settingsOverlay) {
        settingsOverlay.addEventListener("click", () => {
            settingsSidebar.classList.remove("open")
            settingsOverlay.classList.remove("open")
            document.body.style.overflow = "" // Enable scrolling
        })
    }

    // Close settings sidebar when escape key is pressed
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape" && settingsSidebar.classList.contains("open")) {
            settingsSidebar.classList.remove("open")
            settingsOverlay.classList.remove("open")
            document.body.style.overflow = "" // Enable scrolling
        }
    })
})

