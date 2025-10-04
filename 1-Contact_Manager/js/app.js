document.addEventListener("DOMContentLoaded", () => {
    // === 1. Toggle Navigation (Hamburger Menu) ===
    // Select the hamburger menu button and the main navigation container
    const hamburgerMenu = document.getElementById('hamburgerMenu');
    const mainNav = document.getElementById('mainNav');

    // Add click event listener to toggle the 'open' class on the navigation menu
    hamburgerMenu.addEventListener('click', () => {
        mainNav.classList.toggle('open');
    });

    // === 2. Search Functionality ===
    // Select the search input, the container holding all contact cards, and the 'no contacts' message element
    const searchInput = document.getElementById("searchInput");
    const contactsList = document.getElementById("contactsList");
    const noContactsMessage = document.getElementById("noContactsMessage");

    // Add input event listener to filter contact cards dynamically based on the search query
    searchInput.addEventListener("input", () => {
        const query = searchInput.value.toLowerCase();
        const contactCards = contactsList.querySelectorAll(".contact-card");
        let visibleCount = 0;

        // Iterate over each contact card to check if the contact name matches the query
        contactCards.forEach(card => {
            const name = card.querySelector("h3").textContent.toLowerCase();

            // Show card if name includes query, otherwise hide it
            if (name.includes(query)) {
                card.style.display = "block";
                visibleCount++;
            } else {
                card.style.display = "none";
            }
        });

        // Display the 'no contacts' message only if no contacts match the search query
        noContactsMessage.style.display = visibleCount === 0 ? "block" : "none";
    });
});
